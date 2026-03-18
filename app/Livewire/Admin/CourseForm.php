<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use App\Models\Course;
use App\Models\CourseResource;
use App\Models\CourseVideo;
use App\Models\Department;
use App\Models\Enrollment;
use App\Models\User;
use App\Notifications\CourseAssignedNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;

class CourseForm extends AdminComponent
{
    use WithFileUploads;

    public ?int $courseId = null;

    // Course fields
    public string $title = '';
    public string $description = '';
    public ?int $category_id = null;
    public string $start_date = '';
    public string $end_date = '';
    public int $exam_duration_minutes = 30;
    public int $passing_score = 70;
    public int $max_attempts = 3;
    public bool $is_mandatory = false;
    public string $status = 'draft';

    // Yeni alanlar
    public $thumbnailFile = null;
    public ?string $thumbnailPath = null;
    public bool $shuffle_questions = false;
    public bool $exam_required = true;
    public ?int $prerequisite_course_id = null;
    public ?int $repeat_period_months = null;
    public string $language = 'tr';
    public string $instructor = '';
    public string $tagsInput = ''; // virgülle ayrılmış tag girişi

    // Department assignment
    public array $selectedDepartments = [];

    // Per-person assignment (Alpine manages UI, synced on save)
    public array $selectedPersonnel = [];

    // Videos (çoklu) — metadata array'i
    public array $videos = [];
    public array $deletedVideoIds = [];

    // Video dosyaları — ayrı property (Livewire file upload gereksinimi)
    public $videoFiles = [];

    // Questions
    public array $questions = [];

    // Resources/Materyaller
    public array $resources = [];
    public $resourceFiles = [];
    public array $deletedResourceIds = [];

    protected function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'exam_duration_minutes' => 'required|integer|min:5|max:180',
            'passing_score' => 'required|integer|min:1|max:100',
            'max_attempts' => 'required|integer|min:1|max:10',
            'is_mandatory' => 'boolean',
            'status' => 'required|in:draft,published',
            'shuffle_questions' => 'boolean',
            'exam_required' => 'boolean',
            'prerequisite_course_id' => 'nullable|exists:courses,id',
            'repeat_period_months' => 'nullable|integer|min:1|max:120',
            'language' => 'required|string|max:10',
            'instructor' => 'nullable|string|max:255',
            'thumbnailFile' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'selectedDepartments' => 'array',
            'videos' => 'array',
            'videos.*.title' => 'required|string|max:255',
            'videos.*.url' => 'nullable|url:http,https',
            'videoFiles.*' => 'nullable|file|mimes:mp4,avi,mov,wmv|max:512000',
            'resources' => 'array',
            'resources.*.title' => 'nullable|string|max:255',
            'resources.*.url' => 'nullable|url:http,https',
            'questions' => 'array',
            'questions.*.question_type' => 'required|in:multiple_choice,true_false,open_ended',
            'questions.*.question_text' => 'required|string',
            'questions.*.option_a' => 'required_if:questions.*.question_type,multiple_choice|nullable|string',
            'questions.*.option_b' => 'required_if:questions.*.question_type,multiple_choice|nullable|string',
            'questions.*.option_c' => 'nullable|string',
            'questions.*.option_d' => 'nullable|string',
            'questions.*.correct_option' => 'required_unless:questions.*.question_type,open_ended|nullable|in:a,b,c,d',
        ];
    }

    protected function messages(): array
    {
        return [
            'title.required'                      => __('lms.val_title_required'),
            'category_id.required'                => __('lms.val_category_required'),
            'end_date.after_or_equal'             => __('lms.val_end_date'),
            'videos.*.title.required'             => __('lms.val_video_title'),
            'videoFiles.*.max'                    => __('lms.val_video_size'),
            'videoFiles.*.mimes'                  => __('lms.val_video_mimes'),
            'videoFiles.*.uploaded'               => __('lms.val_video_upload'),
            'questions.*.question_text.required'  => __('lms.val_question_text'),
            'questions.*.option_a.required'       => __('lms.val_option_required', ['letter' => 'A']),
            'questions.*.option_b.required'       => __('lms.val_option_required', ['letter' => 'B']),
            'questions.*.option_c.required'       => __('lms.val_option_required', ['letter' => 'C']),
            'questions.*.option_d.required'       => __('lms.val_option_required', ['letter' => 'D']),
            'questions.*.correct_option.required' => __('lms.val_correct_option'),
        ];
    }

    public function mount(?int $courseId = null): void
    {
        if ($courseId) {
            $course = Course::with(['departments', 'questions', 'videos', 'resources'])->findOrFail($courseId);
            $this->courseId = $course->id;
            $this->title = $course->title;
            $this->description = $course->description ?? '';
            $this->category_id = $course->category_id;
            $this->start_date = $course->start_date ? $course->start_date->format('Y-m-d') : '';
            $this->end_date = $course->end_date ? $course->end_date->format('Y-m-d') : '';
            $this->exam_duration_minutes = $course->exam_duration_minutes;
            $this->passing_score = $course->passing_score;
            $this->max_attempts = $course->max_attempts;
            $this->is_mandatory = $course->is_mandatory;
            $this->status = $course->status;
            $this->thumbnailPath = $course->thumbnail;
            $this->shuffle_questions = $course->shuffle_questions ?? false;
            $this->exam_required = $course->exam_required ?? true;
            $this->prerequisite_course_id = $course->prerequisite_course_id;
            $this->repeat_period_months = $course->repeat_period_months;
            $this->language = $course->language ?? 'tr';
            $this->instructor = $course->instructor ?? '';
            $this->tagsInput = $course->tags ? implode(', ', $course->tags) : '';
            $this->selectedDepartments = $course->departments->pluck('id')->toArray();
            $this->selectedPersonnel   = Enrollment::where('course_id', $courseId)
                ->pluck('user_id')->map(fn ($id) => (int) $id)->toArray();

            $this->videos = $course->videos->map(fn ($v) => [
                'id' => $v->id,
                'title' => $v->title,
                'existing_path' => $v->video_path,
                'url' => $v->url ?? '',
                'description' => $v->description ?? '',
                'sort_order' => $v->sort_order,
            ])->toArray();

            $this->questions = $course->questions->map(fn ($q) => [
                'id'            => $q->id,
                'question_type' => $q->question_type ?? 'multiple_choice',
                'question_text' => $q->question_text,
                'option_a'      => $q->option_a ?? '',
                'option_b'      => $q->option_b ?? '',
                'option_c'      => $q->option_c ?? '',
                'option_d'      => $q->option_d ?? '',
                'correct_option' => $q->correct_option ?? 'a',
            ])->toArray();

            $this->resources = $course->resources->map(fn ($r) => [
                'id' => $r->id,
                'title' => $r->title,
                'type' => $r->type,
                'existing_path' => $r->file_path,
                'url' => $r->url,
            ])->toArray();
        }
    }

    // ── Video Yönetimi ──

    public function addVideo(): void
    {
        $this->videos[] = [
            'id' => null,
            'title' => '',
            'existing_path' => null,
            'url' => '',
            'description' => '',
            'sort_order' => count($this->videos) + 1,
        ];
    }

    public function removeVideo(int $index): void
    {
        if (!empty($this->videos[$index]['id'])) {
            $this->deletedVideoIds[] = $this->videos[$index]['id'];
        }
        unset($this->videos[$index]);
        unset($this->videoFiles[$index]);
        $this->videos = array_values($this->videos);
        $this->videoFiles = array_values($this->videoFiles);
    }

    public function moveVideoUp(int $index): void
    {
        if ($index > 0) {
            // Videos metadata
            $temp = $this->videos[$index - 1];
            $this->videos[$index - 1] = $this->videos[$index];
            $this->videos[$index] = $temp;

            // Video files
            $tempFile = $this->videoFiles[$index - 1] ?? null;
            $this->videoFiles[$index - 1] = $this->videoFiles[$index] ?? null;
            $this->videoFiles[$index] = $tempFile;
        }
    }

    public function moveVideoDown(int $index): void
    {
        if ($index < count($this->videos) - 1) {
            // Videos metadata
            $temp = $this->videos[$index + 1];
            $this->videos[$index + 1] = $this->videos[$index];
            $this->videos[$index] = $temp;

            // Video files
            $tempFile = $this->videoFiles[$index + 1] ?? null;
            $this->videoFiles[$index + 1] = $this->videoFiles[$index] ?? null;
            $this->videoFiles[$index] = $tempFile;
        }
    }

    // ── Materyal/Kaynak Yönetimi ──

    public function addResource(): void
    {
        $this->resources[] = [
            'id' => null,
            'title' => '',
            'type' => 'pdf',
            'existing_path' => null,
            'url' => '',
        ];
    }

    public function removeResource(int $index): void
    {
        if (!empty($this->resources[$index]['id'])) {
            $this->deletedResourceIds[] = $this->resources[$index]['id'];
        }
        unset($this->resources[$index]);
        unset($this->resourceFiles[$index]);
        $this->resources = array_values($this->resources);
    }

    // ── Soru Yönetimi ──

    public function addQuestion(string $type = 'multiple_choice'): void
    {
        $this->questions[] = [
            'id'            => null,
            'question_type' => $type,
            'question_text' => '',
            'option_a'      => $type === 'true_false' ? 'Doğru' : '',
            'option_b'      => $type === 'true_false' ? 'Yanlış' : '',
            'option_c'      => '',
            'option_d'      => '',
            'correct_option' => 'a',
        ];
    }

    public function removeQuestion(int $index): void
    {
        unset($this->questions[$index]);
        $this->questions = array_values($this->questions);
    }

    public function moveQuestionUp(int $index): void
    {
        if ($index > 0) {
            $temp = $this->questions[$index - 1];
            $this->questions[$index - 1] = $this->questions[$index];
            $this->questions[$index] = $temp;
        }
    }

    public function moveQuestionDown(int $index): void
    {
        if ($index < count($this->questions) - 1) {
            $temp = $this->questions[$index + 1];
            $this->questions[$index + 1] = $this->questions[$index];
            $this->questions[$index] = $temp;
        }
    }

    // ── Kaydet ──

    public function save()
    {
        $this->validate();

        // Yeni eklenen videolar için dosya veya URL zorunlu
        foreach ($this->videos as $index => $videoData) {
            $hasFile = isset($this->videoFiles[$index]) && $this->videoFiles[$index] !== null && $this->videoFiles[$index] !== '';
            $hasUrl  = !empty($videoData['url']);
            if (empty($videoData['id']) && !$hasFile && !$hasUrl) {
                $this->addError("videoFiles.{$index}", __('lms.val_video_file_required'));
                return;
            }
        }

        // Thumbnail yükleme
        if ($this->thumbnailFile) {
            $this->thumbnailPath = $this->thumbnailFile->store('thumbnails', 'public');
        }

        // Tags parse
        $tags = array_values(array_filter(array_map('trim', explode(',', $this->tagsInput))));

        $data = [
            'title' => $this->title,
            'description' => $this->description ?: null,
            'category_id' => $this->category_id,
            'thumbnail' => $this->thumbnailPath,
            'start_date' => $this->start_date ?: null,
            'end_date' => $this->end_date ?: null,
            'exam_duration_minutes' => $this->exam_duration_minutes,
            'passing_score' => $this->passing_score,
            'max_attempts' => $this->max_attempts,
            'shuffle_questions' => $this->shuffle_questions,
            'exam_required' => $this->exam_required,
            'prerequisite_course_id' => $this->prerequisite_course_id ?: null,
            'repeat_period_months' => $this->repeat_period_months ?: null,
            'language' => $this->language,
            'instructor' => $this->instructor ?: null,
            'tags' => !empty($tags) ? $tags : null,
            'is_mandatory' => $this->is_mandatory,
            'status' => $this->status,
        ];

        [$course, $enrollmentCount] = DB::transaction(function () use ($data) {
            if ($this->courseId) {
                $course = Course::findOrFail($this->courseId);
                $course->update($data);
            } else {
                $data['created_by'] = Auth::id();
                $course = Course::create($data);
            }

            // Derive departments from selected personnel's department_id
            $derivedDeptIds = empty($this->selectedPersonnel)
                ? []
                : User::whereIn('id', $this->selectedPersonnel)
                    ->whereNotNull('department_id')
                    ->pluck('department_id')->unique()->filter()->values()->toArray();
            $this->selectedDepartments = $derivedDeptIds;
            $course->departments()->sync($derivedDeptIds);

            // Enroll specifically selected personnel
            $enrollmentCount = $this->syncEnrollments($course);

            // ── Video Sync ──
            $this->syncVideos($course);

            // ── Soru Sync ──
            $existingQuestionIds = [];
            foreach ($this->questions as $index => $qData) {
                $type = $qData['question_type'] ?? 'multiple_choice';
                $questionData = [
                    'course_id'     => $course->id,
                    'question_type' => $type,
                    'question_text' => $qData['question_text'],
                    'option_a'      => $type !== 'open_ended' ? ($qData['option_a'] ?: ($type === 'true_false' ? 'Doğru' : null)) : null,
                    'option_b'      => $type !== 'open_ended' ? ($qData['option_b'] ?: ($type === 'true_false' ? 'Yanlış' : null)) : null,
                    'option_c'      => $type === 'multiple_choice' ? ($qData['option_c'] ?: null) : null,
                    'option_d'      => $type === 'multiple_choice' ? ($qData['option_d'] ?: null) : null,
                    'correct_option' => $type === 'open_ended' ? null : ($qData['correct_option'] ?? 'a'),
                    'sort_order'    => $index + 1,
                ];

                if (!empty($qData['id'])) {
                    $course->questions()->where('id', $qData['id'])->update($questionData);
                    $existingQuestionIds[] = $qData['id'];
                } else {
                    $newQ = $course->questions()->create($questionData);
                    $existingQuestionIds[] = $newQ->id;
                    $this->questions[$index]['id'] = $newQ->id;
                }
            }

            // Delete removed questions
            $course->questions()->whereNotIn('id', $existingQuestionIds)->delete();

            // ── Kaynak/Materyal Sync ──
            $this->syncResources($course);

            return [$course, $enrollmentCount];
        });

        $this->courseId = $course->id;

        Cache::forget('admin.course_status_counts');

        $message = $this->courseId ? __('lms.course_updated_msg') : __('lms.course_created_msg');
        if ($enrollmentCount > 0) {
            $message .= ' ' . __('lms.enrollment_added', ['count' => $enrollmentCount]);
        }
        session()->flash('success', $message);

        return $this->redirect(route('admin.courses.index'), navigate: false);
    }

    private function syncVideos(Course $course): void
    {
        // Silinen videoları kaldır
        if (!empty($this->deletedVideoIds)) {
            CourseVideo::whereIn('id', $this->deletedVideoIds)
                ->where('course_id', $course->id)
                ->delete();
        }

        // Her video için kaydet/güncelle
        foreach ($this->videos as $index => $videoData) {
            $record = [
                'course_id' => $course->id,
                'title' => $videoData['title'],
                'url' => $videoData['url'] ?: null,
                'description' => $videoData['description'] ?: null,
                'sort_order' => $index + 1,
            ];

            // Dosya yükleme — ayrı $videoFiles property'den al
            $file = $this->videoFiles[$index] ?? null;
            if ($file) {
                try {
                    $path = $file->store('videos', 'local');
                    $record['video_path'] = $path;
                } catch (\Exception $e) {
                    $this->addError("videoFiles.{$index}", 'Video yüklenirken hata oluştu: ' . $e->getMessage());
                    return;
                }
            }

            if (!empty($videoData['id'])) {
                // Mevcut videoyu güncelle
                $courseVideo = CourseVideo::find($videoData['id']);
                if ($courseVideo) {
                    $courseVideo->update($record);
                }
            } else {
                // Yeni video — dosya veya URL olmalı
                if ($file || !empty($videoData['url'])) {
                    CourseVideo::create($record);
                }
            }
        }
    }

    private function syncResources(Course $course): void
    {
        // Silinen kaynakları kaldır
        if (!empty($this->deletedResourceIds)) {
            CourseResource::whereIn('id', $this->deletedResourceIds)
                ->where('course_id', $course->id)
                ->delete();
        }

        foreach ($this->resources as $index => $resData) {
            $record = [
                'course_id' => $course->id,
                'title' => $resData['title'],
                'type' => $resData['type'],
                'url' => $resData['url'] ?: null,
                'sort_order' => $index + 1,
            ];

            $file = $this->resourceFiles[$index] ?? null;
            if ($file) {
                try {
                    $path = $file->store('resources', 'local');
                    $record['file_path'] = $path;
                    $record['file_size'] = $file->getSize();
                } catch (\Exception $e) {
                    $this->addError("resourceFiles.{$index}", 'Kaynak yüklenirken hata oluştu: ' . $e->getMessage());
                    return;
                }
            }

            if (!empty($resData['id'])) {
                CourseResource::where('id', $resData['id'])->update($record);
            } else {
                CourseResource::create($record);
            }
        }
    }

    private function syncEnrollments(Course $course): int
    {
        $newEnrollments  = 0;
        $targetIds       = collect($this->selectedPersonnel)->map(fn ($id) => (int) $id);

        // Seçimden çıkarılan (henüz başlamamış) kayıtları sil
        Enrollment::where('course_id', $course->id)
            ->where('status', 'not_started')
            ->when($targetIds->isNotEmpty(), fn ($q) => $q->whereNotIn('user_id', $targetIds))
            ->when($targetIds->isEmpty(), fn ($q) => $q)
            ->delete();

        if ($targetIds->isEmpty()) {
            return $newEnrollments;
        }

        $existingIds = Enrollment::where('course_id', $course->id)
            ->pluck('user_id')->map(fn ($id) => (int) $id);

        $newIds = $targetIds->diff($existingIds);

        if ($newIds->isEmpty()) {
            return $newEnrollments;
        }

        // Bildirim için toplu yükle
        $newUsers = User::whereIn('id', $newIds)->get()->keyBy('id');

        foreach ($newIds as $userId) {
            $enrollment = Enrollment::firstOrCreate(
                ['user_id' => $userId, 'course_id' => $course->id],
                ['status' => 'not_started', 'current_attempt' => 1]
            );
            if ($enrollment->wasRecentlyCreated) {
                $newUsers[$userId]?->notify(new CourseAssignedNotification($course));
                $newEnrollments++;
            }
        }

        return $newEnrollments;
    }

    public function render()
    {
        $categories  = Cache::remember('categories.all', now()->addHours(6),
            fn () => Category::orderBy('name')->get()
        );
        $departments = Cache::remember('departments.all', now()->addHours(6),
            fn () => Department::where('is_active', true)
                ->with(['users' => fn ($q) => $q->where('is_active', true)->orderBy('first_name')])
                ->withCount(['users as staff_count' => fn ($q) => $q->where('is_active', true)])
                ->orderBy('name')
                ->get()
        );

        $allCourses = Course::where('id', '!=', $this->courseId ?? 0)
            ->orderBy('title')
            ->get(['id', 'title']);

        return view('livewire.admin.course-form', compact('categories', 'departments', 'allCourses'));
    }
}
