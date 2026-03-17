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
use Livewire\Component;
use Livewire\WithFileUploads;

class CourseForm extends Component
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

    // Department assignment
    public array $selectedDepartments = [];

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
            'selectedDepartments' => 'array',
            'videos' => 'array',
            'videos.*.title' => 'required|string|max:255',
            'videoFiles.*' => 'nullable|file|mimes:mp4,avi,mov,wmv|max:512000',
            'questions' => 'array',
            'questions.*.question_text' => 'required|string',
            'questions.*.option_a' => 'required|string',
            'questions.*.option_b' => 'required|string',
            'questions.*.option_c' => 'required|string',
            'questions.*.option_d' => 'required|string',
            'questions.*.correct_option' => 'required|in:a,b,c,d',
        ];
    }

    protected $messages = [
        'title.required' => 'Eğitim başlığı zorunludur.',
        'category_id.required' => 'Kategori seçimi zorunludur.',
        'end_date.after_or_equal' => 'Bitiş tarihi, başlangıç tarihinden sonra olmalıdır.',
        'videos.*.title.required' => 'Video başlığı zorunludur.',
        'videoFiles.*.max' => 'Video dosyası en fazla 500MB olabilir.',
        'videoFiles.*.mimes' => 'Video dosyası mp4, avi, mov veya wmv formatında olmalıdır.',
        'videoFiles.*.uploaded' => 'Video yüklenemedi. Dosya boyutu çok büyük olabilir.',
        'questions.*.question_text.required' => 'Soru metni zorunludur.',
        'questions.*.option_a.required' => 'A şıkkı zorunludur.',
        'questions.*.option_b.required' => 'B şıkkı zorunludur.',
        'questions.*.option_c.required' => 'C şıkkı zorunludur.',
        'questions.*.option_d.required' => 'D şıkkı zorunludur.',
        'questions.*.correct_option.required' => 'Doğru cevap seçimi zorunludur.',
    ];

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
            $this->selectedDepartments = $course->departments->pluck('id')->toArray();

            $this->videos = $course->videos->map(fn ($v) => [
                'id' => $v->id,
                'title' => $v->title,
                'existing_path' => $v->video_path,
                'sort_order' => $v->sort_order,
            ])->toArray();

            $this->questions = $course->questions->map(fn ($q) => [
                'id' => $q->id,
                'question_text' => $q->question_text,
                'option_a' => $q->option_a,
                'option_b' => $q->option_b,
                'option_c' => $q->option_c,
                'option_d' => $q->option_d,
                'correct_option' => $q->correct_option,
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

    public function addQuestion(): void
    {
        $this->questions[] = [
            'id' => null,
            'question_text' => '',
            'option_a' => '',
            'option_b' => '',
            'option_c' => '',
            'option_d' => '',
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

        $data = [
            'title' => $this->title,
            'description' => $this->description ?: null,
            'category_id' => $this->category_id,
            'start_date' => $this->start_date ?: null,
            'end_date' => $this->end_date ?: null,
            'exam_duration_minutes' => $this->exam_duration_minutes,
            'passing_score' => $this->passing_score,
            'max_attempts' => $this->max_attempts,
            'is_mandatory' => $this->is_mandatory,
            'status' => $this->status,
        ];

        if ($this->courseId) {
            $course = Course::findOrFail($this->courseId);
            $course->update($data);
        } else {
            $data['created_by'] = Auth::id();
            $course = Course::create($data);
            $this->courseId = $course->id;
        }

        // Sync departments
        $course->departments()->sync($this->selectedDepartments);

        // Auto-enroll staff in selected departments
        $enrollmentCount = $this->syncEnrollments($course);

        // ── Video Sync ──
        $this->syncVideos($course);

        // ── Soru Sync ──
        $existingQuestionIds = [];
        foreach ($this->questions as $index => $qData) {
            $questionData = [
                'course_id' => $course->id,
                'question_text' => $qData['question_text'],
                'option_a' => $qData['option_a'],
                'option_b' => $qData['option_b'],
                'option_c' => $qData['option_c'],
                'option_d' => $qData['option_d'],
                'correct_option' => $qData['correct_option'],
                'sort_order' => $index + 1,
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

        $message = $this->courseId ? 'Eğitim güncellendi.' : 'Eğitim oluşturuldu.';
        if ($enrollmentCount > 0) {
            $message .= " {$enrollmentCount} personele atandı.";
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
                'sort_order' => $index + 1,
            ];

            // Dosya yükleme — ayrı $videoFiles property'den al
            $file = $this->videoFiles[$index] ?? null;
            if ($file) {
                $path = $file->store('videos', 'local');
                $record['video_path'] = $path;
            }

            if (!empty($videoData['id'])) {
                // Mevcut videoyu güncelle
                $courseVideo = CourseVideo::find($videoData['id']);
                if ($courseVideo) {
                    $courseVideo->update($record);
                }
            } else {
                // Yeni video — dosya zorunlu
                if ($file) {
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
                $path = $file->store('resources', 'local');
                $record['file_path'] = $path;
                $record['file_size'] = $file->getSize();
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
        $newEnrollments = 0;

        if (empty($this->selectedDepartments)) {
            return $newEnrollments;
        }

        $staffIds = User::where('is_active', true)
            ->role('staff')
            ->whereIn('department_id', $this->selectedDepartments)
            ->pluck('id');

        $existingEnrollments = Enrollment::where('course_id', $course->id)
            ->pluck('user_id');

        $newStaffIds = $staffIds->diff($existingEnrollments);

        foreach ($newStaffIds as $staffId) {
            Enrollment::create([
                'user_id' => $staffId,
                'course_id' => $course->id,
                'status' => 'not_started',
                'current_attempt' => 1,
            ]);

            // E-posta bildirimi
            $user = User::find($staffId);
            $user?->notify(new CourseAssignedNotification($course));

            $newEnrollments++;
        }

        Enrollment::where('course_id', $course->id)
            ->where('status', 'not_started')
            ->whereNotIn('user_id', $staffIds)
            ->delete();

        return $newEnrollments;
    }

    public function render()
    {
        $categories = Category::orderBy('name')->get();
        $departments = Department::where('is_active', true)->orderBy('name')->get();

        return view('livewire.admin.course-form', compact('categories', 'departments'));
    }
}
