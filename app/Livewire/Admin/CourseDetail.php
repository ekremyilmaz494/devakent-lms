<?php

namespace App\Livewire\Admin;

use App\Models\Course;
use App\Models\Department;
use App\Models\Enrollment;
use App\Models\ExamAnswer;
use App\Models\ExamAttempt;
use App\Models\Question;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class CourseDetail extends AdminComponent
{
    use WithPagination;

    public int    $courseId;
    public string $activeTab = 'overview';

    // Shared filters
    public string $search           = '';
    public string $departmentFilter = '';
    public string $statusFilter     = '';

    // Sorting
    public string $sortField     = 'name';
    public string $sortDirection = 'asc';

    // Video tab
    public ?int $selectedVideoId = null;

    // Exam results tab extra filters
    public string $examFilter = '';
    public string $minScore   = '';
    public string $maxScore   = '';

    // Questions tab
    public string $questionSort = 'hardest';

    private static array $allowedSortFields = [
        'name', 'department', 'status', 'enrolled_at',
        'pre_score', 'post_score', 'improvement',
    ];

    public function placeholder(): \Illuminate\View\View
    {
        return view('livewire.placeholders.skeleton');
    }

    public function mount(int $courseId): void
    {
        $this->courseId = $courseId;
    }

    public function switchTab(string $tab): void
    {
        $allowed = ['overview', 'videos', 'participants', 'results', 'questions'];
        if (!in_array($tab, $allowed)) return;

        $this->activeTab = $tab;
        $this->resetPage();

        if ($tab !== 'videos') {
            $this->selectedVideoId = null;
        }
    }

    public function sortBy(string $field): void
    {
        if (!in_array($field, self::$allowedSortFields)) return;

        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField     = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function selectVideo(int $videoId): void
    {
        $this->selectedVideoId = ($this->selectedVideoId === $videoId) ? null : $videoId;
    }

    public function retranscodeVideo(int $videoId): void
    {
        $video = \App\Models\CourseVideo::where('id', $videoId)
            ->where('course_id', $this->courseId)
            ->firstOrFail();

        abort_unless($video->video_path, 403, 'Video dosyası bulunamadı.');

        $video->update(['transcode_status' => 'pending']);
        \App\Jobs\TranscodeVideoJob::dispatch($videoId);

        session()->flash('success', '"' . $video->title . '" yeniden işleme kuyruğuna eklendi.');
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingDepartmentFilter(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    // -------------------------------------------------------------------------
    // RENDER
    // -------------------------------------------------------------------------

    public function render()
    {
        $course = $this->getCourse();

        $tabData = match ($this->activeTab) {
            'overview'     => $this->getOverviewData($course),
            'videos'       => $this->getVideosData(),
            'participants' => $this->getParticipantsData(),
            'results'      => $this->getExamResultsData(),
            'questions'    => $this->getQuestionsData(),
            default        => $this->getOverviewData($course),
        };

        return view('livewire.admin.course-detail', array_merge(
            ['course' => $course],
            $tabData
        ));
    }

    // -------------------------------------------------------------------------
    // COURSE (shared across all tabs)
    // -------------------------------------------------------------------------

    private function getCourse(): Course
    {
        return Course::with(['category', 'departments'])
            ->withCount([
                'enrollments',
                'enrollments as completed_count' => fn ($q) => $q->where('status', 'completed'),
                'enrollments as in_progress_count' => fn ($q) => $q->where('status', 'in_progress'),
                'enrollments as not_started_count' => fn ($q) => $q->where('status', 'not_started'),
                'questions',
                'videos',
            ])
            ->findOrFail($this->courseId);
    }

    // -------------------------------------------------------------------------
    // TAB 1: OVERVIEW
    // -------------------------------------------------------------------------

    private function getOverviewData(Course $course): array
    {
        $total     = $course->enrollments_count ?? 0;
        $completed = $course->completed_count ?? 0;
        $rate      = $total > 0 ? round($completed / $total * 100, 1) : 0;

        $preAvg  = round((float) (ExamAttempt::whereHas('enrollment', fn ($q) => $q->where('course_id', $this->courseId))
            ->where('exam_type', 'pre_exam')
            ->whereNotNull('finished_at')
            ->avg('score') ?? 0), 1);

        $postAvg = round((float) (ExamAttempt::whereHas('enrollment', fn ($q) => $q->where('course_id', $this->courseId))
            ->where('exam_type', 'post_exam')
            ->whereNotNull('finished_at')
            ->avg('score') ?? 0), 1);

        $videoStats = $course->videos()->selectRaw('COUNT(*) as cnt, SUM(video_duration_seconds) as total_seconds')->first();

        // Anket istatistikleri
        $surveyStats = \App\Models\CourseSurveyResponse::where('course_id', $this->courseId)
            ->selectRaw('
                COUNT(*) as total_responses,
                ROUND(AVG(rating_overall), 1) as avg_overall,
                ROUND(AVG(rating_content), 1) as avg_content,
                ROUND(AVG(rating_usefulness), 1) as avg_usefulness
            ')->first();
        $surveyDuration = \App\Models\CourseSurveyResponse::where('course_id', $this->courseId)
            ->selectRaw('rating_duration, COUNT(*) as cnt')
            ->groupBy('rating_duration')
            ->pluck('cnt', 'rating_duration')
            ->toArray();

        return compact('total', 'completed', 'rate', 'preAvg', 'postAvg', 'videoStats', 'surveyStats', 'surveyDuration');
    }

    // -------------------------------------------------------------------------
    // TAB 2: VIDEOS
    // -------------------------------------------------------------------------

    private function getVideosData(): array
    {
        $videos = Course::find($this->courseId)?->videos()->orderBy('sort_order')->get() ?? collect();

        if ($this->selectedVideoId === null && $videos->isNotEmpty()) {
            $this->selectedVideoId = $videos->first()->id;
        }

        $selectedVideo = $this->selectedVideoId
            ? $videos->firstWhere('id', $this->selectedVideoId)
            : null;

        return ['videos' => $videos, 'selectedVideo' => $selectedVideo];
    }

    // -------------------------------------------------------------------------
    // TAB 3: PARTICIPANTS
    // -------------------------------------------------------------------------

    private function getParticipantsData(): array
    {
        // Correlated subqueries for pre/post scores
        $preSub = ExamAttempt::select('score')
            ->whereColumn('enrollment_id', 'enrollments.id')
            ->where('exam_type', 'pre_exam')
            ->whereNotNull('finished_at')
            ->orderByDesc('finished_at')
            ->limit(1);

        $postSub = ExamAttempt::select('score')
            ->whereColumn('enrollment_id', 'enrollments.id')
            ->where('exam_type', 'post_exam')
            ->whereNotNull('finished_at')
            ->orderByDesc('finished_at')
            ->limit(1);

        $query = Enrollment::query()
            ->where('course_id', $this->courseId)
            ->with(['user.department'])
            ->addSelect([
                'enrollments.*',
                'pre_score'  => $preSub,
                'post_score' => $postSub,
            ])
            ->when($this->search, fn ($q) =>
                $q->whereHas('user', fn ($u) =>
                    $u->where(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', "%{$this->search}%")
                      ->orWhere('first_name', 'like', "%{$this->search}%")
                      ->orWhere('last_name',  'like', "%{$this->search}%")
                )
            )
            ->when($this->departmentFilter, fn ($q) =>
                $q->whereHas('user', fn ($u) => $u->where('department_id', $this->departmentFilter))
            )
            ->when($this->statusFilter, fn ($q) => $q->where('enrollments.status', $this->statusFilter));

        // Sorting with correlated subqueries to avoid JOIN column ambiguity
        $dir = $this->sortDirection;
        $query = match ($this->sortField) {
            'name'        => $query->join('users', 'users.id', '=', 'enrollments.user_id')
                                   ->orderBy('users.first_name', $dir)
                                   ->select('enrollments.*', DB::raw("(SELECT score FROM exam_attempts WHERE enrollment_id = enrollments.id AND exam_type = 'pre_exam' AND finished_at IS NOT NULL ORDER BY finished_at DESC LIMIT 1) as pre_score"), DB::raw("(SELECT score FROM exam_attempts WHERE enrollment_id = enrollments.id AND exam_type = 'post_exam' AND finished_at IS NOT NULL ORDER BY finished_at DESC LIMIT 1) as post_score")),
            'department'  => $query->join('users', 'users.id', '=', 'enrollments.user_id')
                                   ->leftJoin('departments', 'departments.id', '=', 'users.department_id')
                                   ->orderBy('departments.name', $dir)
                                   ->select('enrollments.*', DB::raw("(SELECT score FROM exam_attempts WHERE enrollment_id = enrollments.id AND exam_type = 'pre_exam' AND finished_at IS NOT NULL ORDER BY finished_at DESC LIMIT 1) as pre_score"), DB::raw("(SELECT score FROM exam_attempts WHERE enrollment_id = enrollments.id AND exam_type = 'post_exam' AND finished_at IS NOT NULL ORDER BY finished_at DESC LIMIT 1) as post_score")),
            'status'      => $query->orderBy('enrollments.status', $dir),
            'enrolled_at' => $query->orderBy('enrollments.created_at', $dir),
            'pre_score'   => $query->orderByRaw("(SELECT score FROM exam_attempts WHERE enrollment_id = enrollments.id AND exam_type = 'pre_exam' AND finished_at IS NOT NULL ORDER BY finished_at DESC LIMIT 1) {$dir}"),
            'post_score'  => $query->orderByRaw("(SELECT score FROM exam_attempts WHERE enrollment_id = enrollments.id AND exam_type = 'post_exam' AND finished_at IS NOT NULL ORDER BY finished_at DESC LIMIT 1) {$dir}"),
            'improvement' => $query->orderByRaw("(
                (SELECT score FROM exam_attempts WHERE enrollment_id = enrollments.id AND exam_type = 'post_exam' AND finished_at IS NOT NULL ORDER BY finished_at DESC LIMIT 1)
                -
                (SELECT score FROM exam_attempts WHERE enrollment_id = enrollments.id AND exam_type = 'pre_exam' AND finished_at IS NOT NULL ORDER BY finished_at DESC LIMIT 1)
            ) {$dir}"),
            default       => $query->orderBy('enrollments.created_at', 'desc'),
        };

        $enrollments = $query->paginate(15);

        // Summary stats (separate queries to avoid pagination affecting counts)
        $baseQuery = Enrollment::where('course_id', $this->courseId)
            ->when($this->departmentFilter, fn ($q) =>
                $q->whereHas('user', fn ($u) => $u->where('department_id', $this->departmentFilter))
            );

        $summaryStats = [
            'total'       => (clone $baseQuery)->count(),
            'not_started' => (clone $baseQuery)->where('status', 'not_started')->count(),
            'in_progress' => (clone $baseQuery)->where('status', 'in_progress')->count(),
            'completed'   => (clone $baseQuery)->where('status', 'completed')->count(),
        ];

        $departments = Department::where('is_active', true)->orderBy('name')->get();

        return compact('enrollments', 'summaryStats', 'departments');
    }

    // -------------------------------------------------------------------------
    // TAB 4: EXAM RESULTS
    // -------------------------------------------------------------------------

    private function getExamResultsData(): array
    {
        $query = Enrollment::query()
            ->where('course_id', $this->courseId)
            ->when($this->departmentFilter, fn ($q) =>
                $q->whereHas('user', fn ($u) => $u->where('department_id', $this->departmentFilter))
            )
            ->when($this->examFilter === 'pre_only', fn ($q) =>
                $q->whereHas('examAttempts', fn ($a) => $a->where('exam_type', 'pre_exam')->whereNotNull('finished_at'))
            )
            ->when($this->examFilter === 'post_only', fn ($q) =>
                $q->whereHas('examAttempts', fn ($a) => $a->where('exam_type', 'post_exam')->whereNotNull('finished_at'))
            )
            ->when($this->examFilter === 'both', fn ($q) =>
                $q->whereHas('examAttempts', fn ($a) => $a->where('exam_type', 'pre_exam')->whereNotNull('finished_at'))
                  ->whereHas('examAttempts', fn ($a) => $a->where('exam_type', 'post_exam')->whereNotNull('finished_at'))
            )
            ->when($this->minScore !== '', fn ($q) => $q->whereHas('examAttempts', fn ($a) =>
                $a->where('exam_type', 'post_exam')->whereNotNull('finished_at')->where('score', '>=', (float) $this->minScore)
            ))
            ->when($this->maxScore !== '', fn ($q) => $q->whereHas('examAttempts', fn ($a) =>
                $a->where('exam_type', 'post_exam')->whereNotNull('finished_at')->where('score', '<=', (float) $this->maxScore)
            ));

        // Aggregate stats via DB subqueries — no full load into PHP memory
        $enrollmentSubquery = (clone $query)->select('id');

        $postStats = ExamAttempt::whereIn('enrollment_id', $enrollmentSubquery)
            ->where('exam_type', 'post_exam')
            ->whereNotNull('finished_at')
            ->selectRaw('AVG(score) as avg_post, MAX(score) as max_post, MIN(score) as min_post, SUM(CASE WHEN is_passed = 1 THEN 1 ELSE 0 END) as pass_count, COUNT(*) as total')
            ->first();

        $preAvg = ExamAttempt::whereIn('enrollment_id', $enrollmentSubquery)
            ->where('exam_type', 'pre_exam')
            ->whereNotNull('finished_at')
            ->avg('score');

        $avgImprovement = ExamAttempt::from('exam_attempts as ea_post')
            ->join('exam_attempts as ea_pre', fn ($j) =>
                $j->on('ea_pre.enrollment_id', '=', 'ea_post.enrollment_id')
                  ->where('ea_pre.exam_type', '=', 'pre_exam')
                  ->whereNotNull('ea_pre.finished_at')
            )
            ->whereIn('ea_post.enrollment_id', $enrollmentSubquery)
            ->where('ea_post.exam_type', 'post_exam')
            ->whereNotNull('ea_post.finished_at')
            ->selectRaw('AVG(ea_post.score - ea_pre.score) as avg_improve')
            ->first()?->avg_improve;

        $examStats = [
            'avg_pre'      => round((float) ($preAvg ?? 0), 1),
            'avg_post'     => round((float) ($postStats->avg_post ?? 0), 1),
            'avg_improve'  => round((float) ($avgImprovement ?? 0), 1),
            'highest_post' => (float) ($postStats->max_post ?? 0),
            'lowest_post'  => (float) ($postStats->min_post ?? 0),
            'pass_rate'    => ($postStats->total ?? 0) > 0
                ? round(($postStats->pass_count ?? 0) / $postStats->total * 100, 1)
                : 0,
        ];

        // Paginate display rows (25 per page) — only loads current page into memory
        $resultsPaginator = $query
            ->with([
                'user.department',
                'examAttempts' => fn ($q) => $q->whereNotNull('finished_at')->orderBy('finished_at'),
            ])
            ->paginate(25, ['*'], 'resultsPage');

        // Map current page to result rows
        $rows = $resultsPaginator->getCollection()->map(function ($enrollment) {
            $preAttempt  = $enrollment->examAttempts->where('exam_type', 'pre_exam')->sortByDesc('finished_at')->first();
            $postAttempt = $enrollment->examAttempts->where('exam_type', 'post_exam')->sortByDesc('finished_at')->first();

            $preScore  = $preAttempt  ? (float) $preAttempt->score  : null;
            $postScore = $postAttempt ? (float) $postAttempt->score : null;
            $improvement = ($preScore !== null && $postScore !== null) ? round($postScore - $preScore, 1) : null;

            return [
                'enrollment'   => $enrollment,
                'user'         => $enrollment->user,
                'pre_attempt'  => $preAttempt,
                'post_attempt' => $postAttempt,
                'pre_score'    => $preScore,
                'post_score'   => $postScore,
                'improvement'  => $improvement,
                'is_passed'    => $postAttempt?->is_passed ?? false,
            ];
        });

        // Sorting on current page
        $dir = $this->sortDirection;
        $rows = match ($this->sortField) {
            'name'        => $dir === 'asc'
                                ? $rows->sortBy(fn ($r) => $r['user']?->first_name . ' ' . $r['user']?->last_name)
                                : $rows->sortByDesc(fn ($r) => $r['user']?->first_name . ' ' . $r['user']?->last_name),
            'department'  => $dir === 'asc'
                                ? $rows->sortBy(fn ($r) => $r['user']?->department?->name ?? '')
                                : $rows->sortByDesc(fn ($r) => $r['user']?->department?->name ?? ''),
            'pre_score'   => $dir === 'asc' ? $rows->sortBy('pre_score') : $rows->sortByDesc('pre_score'),
            'post_score'  => $dir === 'asc' ? $rows->sortBy('post_score') : $rows->sortByDesc('post_score'),
            'improvement' => $dir === 'asc' ? $rows->sortBy('improvement') : $rows->sortByDesc('improvement'),
            default       => $rows,
        };

        $resultsPaginator->setCollection($rows->values());
        $resultRows  = $resultsPaginator->getCollection();
        $departments = Department::where('is_active', true)->orderBy('name')->get();

        return compact('resultRows', 'resultsPaginator', 'examStats', 'departments');
    }

    // -------------------------------------------------------------------------
    // TAB 5: QUESTIONS (MCQ only)
    // -------------------------------------------------------------------------

    private function getQuestionsData(): array
    {
        $questions = Question::where('course_id', $this->courseId)
            ->orderBy('sort_order')
            ->get();

        if ($questions->isEmpty()) {
            return ['questionData' => collect()];
        }

        // Get all answer distributions in one query
        $distributions = ExamAnswer::select(
                'question_id',
                'selected_option',
                DB::raw('COUNT(*) as answer_count'),
                DB::raw('SUM(CASE WHEN is_correct = 1 THEN 1 ELSE 0 END) as correct_count')
            )
            ->whereIn(
                'exam_attempt_id',
                ExamAttempt::select('id')
                    ->whereHas('enrollment', fn ($q) => $q->where('course_id', $this->courseId))
                    ->whereNotNull('finished_at')
            )
            ->whereIn('question_id', $questions->pluck('id'))
            ->groupBy('question_id', 'selected_option')
            ->get()
            ->groupBy('question_id');

        $enriched = $questions->map(function ($question) use ($distributions) {
            $dist          = $distributions->get($question->id, collect());
            $totalAnswers  = $dist->sum('answer_count');
            $correctCount  = $dist->sum('correct_count');
            $correctRate   = $totalAnswers > 0 ? round($correctCount / $totalAnswers * 100, 1) : null;

            $options = [];
            foreach (['a', 'b', 'c', 'd'] as $letter) {
                $row = $dist->firstWhere('selected_option', $letter);
                $cnt = (int) ($row?->answer_count ?? 0);
                $options[$letter] = [
                    'text'       => $question->{"option_{$letter}"},
                    'count'      => $cnt,
                    'percentage' => $totalAnswers > 0 ? round($cnt / $totalAnswers * 100, 1) : 0,
                    'is_correct' => $question->correct_option === $letter,
                ];
            }

            return [
                'question'      => $question,
                'total_answers' => (int) $totalAnswers,
                'correct_rate'  => $correctRate,
                'options'       => $options,
            ];
        });

        // Sort by difficulty
        $enriched = match ($this->questionSort) {
            'hardest'    => $enriched->sortBy(fn ($q) => $q['correct_rate'] ?? 101)->values(),
            'easiest'    => $enriched->sortByDesc(fn ($q) => $q['correct_rate'] ?? -1)->values(),
            'sort_order' => $enriched->values(),
            default      => $enriched->values(),
        };

        return ['questionData' => $enriched];
    }
}
