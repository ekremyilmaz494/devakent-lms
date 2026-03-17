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

    public function show($id)
    {
        $course = Course::with(['category', 'questions'])->findOrFail($id);

        // Verify user is enrolled in this course
        $enrollment = Enrollment::where('user_id', auth()->id())
            ->where('course_id', $id)
            ->first();

        abort_unless($enrollment, 403, 'Bu eğitime erişim yetkiniz yok.');

        return view('staff.courses.show', [
            'courseId' => $id,
            'course' => $course,
            'enrollment' => $enrollment,
        ]);
    }
}
