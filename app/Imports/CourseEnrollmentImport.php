<?php

namespace App\Imports;

use App\Models\Enrollment;
use App\Models\User;
use App\Notifications\CourseAssignedNotification;
use App\Models\Course;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CourseEnrollmentImport implements ToCollection, WithHeadingRow
{
    public int $enrolled = 0;
    public int $skipped = 0;
    public array $errors = [];

    private Course $course;

    public function __construct(Course $course)
    {
        $this->course = $course;
    }

    public function collection(Collection $rows): void
    {
        foreach ($rows as $index => $row) {
            $rowNum = $index + 2;

            $email  = trim($row['eposta'] ?? $row['e_posta'] ?? $row['email'] ?? '');
            $regNo  = trim($row['sicil_no'] ?? $row['registration_number'] ?? '');

            if (empty($email) && empty($regNo)) {
                $this->errors[] = "Satır {$rowNum}: E-posta veya sicil no gerekli";
                $this->skipped++;
                continue;
            }

            $user = null;
            if ($email) {
                $user = User::where('email', $email)->where('is_active', true)->first();
            }
            if (!$user && $regNo) {
                $user = User::where('registration_number', $regNo)->where('is_active', true)->first();
            }

            if (!$user) {
                $this->errors[] = "Satır {$rowNum}: Kullanıcı bulunamadı" . ($email ? " ({$email})" : " (sicil: {$regNo})");
                $this->skipped++;
                continue;
            }

            $enrollment = Enrollment::firstOrCreate(
                ['user_id' => $user->id, 'course_id' => $this->course->id],
                ['status' => 'not_started', 'current_attempt' => 1]
            );

            if ($enrollment->wasRecentlyCreated) {
                $user->notify(new CourseAssignedNotification($this->course));
                $this->enrolled++;
            } else {
                $this->skipped++;
            }
        }
    }
}
