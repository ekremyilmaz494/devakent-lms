<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;

class CourseController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $enrollments = $user->enrollments()
            ->with(['course.category', 'course.questions', 'certificate', 'examAttempts'])
            ->latest()
            ->get();

        return view('staff.courses.index', compact('enrollments'));
    }

    public function show(Course $course)
    {
        $enrollment = Enrollment::where('user_id', auth()->id())
            ->where('course_id', $course->id)
            ->first();

        abort_unless($enrollment, 403, 'Bu eğitime erişim yetkiniz yok.');

        return view('staff.courses.show', [
            'courseId' => $course->id,
            'course' => $course,
            'enrollment' => $enrollment,
        ]);
    }
}
