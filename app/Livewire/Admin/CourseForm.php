<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use App\Models\Course;
use App\Models\Department;
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
    public $video_file = null;
    public ?string $existing_video_path = null;
    public ?int $video_duration_seconds = null;
    public string $start_date = '';
    public string $end_date = '';
    public int $exam_duration_minutes = 30;
    public int $passing_score = 70;
    public int $max_attempts = 3;
    public bool $is_mandatory = false;
    public string $status = 'draft';

    // Department assignment
    public array $selectedDepartments = [];

    // Questions
    public array $questions = [];

    protected function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'video_file' => $this->courseId ? 'nullable|file|mimes:mp4,avi,mov,wmv|max:512000' : 'nullable|file|mimes:mp4,avi,mov,wmv|max:512000',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'exam_duration_minutes' => 'required|integer|min:5|max:180',
            'passing_score' => 'required|integer|min:1|max:100',
            'max_attempts' => 'required|integer|min:1|max:10',
            'is_mandatory' => 'boolean',
            'status' => 'required|in:draft,published',
            'selectedDepartments' => 'array',
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
        'video_file.max' => 'Video dosyası en fazla 500MB olabilir.',
        'video_file.mimes' => 'Video dosyası mp4, avi, mov veya wmv formatında olmalıdır.',
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
            $course = Course::with(['departments', 'questions'])->findOrFail($courseId);
            $this->courseId = $course->id;
            $this->title = $course->title;
            $this->description = $course->description ?? '';
            $this->category_id = $course->category_id;
            $this->existing_video_path = $course->video_path;
            $this->video_duration_seconds = $course->video_duration_seconds;
            $this->start_date = $course->start_date ? $course->start_date->format('Y-m-d') : '';
            $this->end_date = $course->end_date ? $course->end_date->format('Y-m-d') : '';
            $this->exam_duration_minutes = $course->exam_duration_minutes;
            $this->passing_score = $course->passing_score;
            $this->max_attempts = $course->max_attempts;
            $this->is_mandatory = $course->is_mandatory;
            $this->status = $course->status;
            $this->selectedDepartments = $course->departments->pluck('id')->toArray();

            $this->questions = $course->questions->map(fn ($q) => [
                'id' => $q->id,
                'question_text' => $q->question_text,
                'option_a' => $q->option_a,
                'option_b' => $q->option_b,
                'option_c' => $q->option_c,
                'option_d' => $q->option_d,
                'correct_option' => $q->correct_option,
            ])->toArray();
        }
    }

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

        // Handle video upload
        if ($this->video_file) {
            $path = $this->video_file->store('videos', 'public');
            $data['video_path'] = $path;
        }

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

        // Sync questions
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

        session()->flash('success', $this->courseId ? 'Eğitim güncellendi.' : 'Eğitim oluşturuldu.');

        return $this->redirect(route('admin.courses.index'), navigate: false);
    }

    public function render()
    {
        $categories = Category::orderBy('name')->get();
        $departments = Department::where('is_active', true)->orderBy('name')->get();

        return view('livewire.admin.course-form', compact('categories', 'departments'));
    }
}
