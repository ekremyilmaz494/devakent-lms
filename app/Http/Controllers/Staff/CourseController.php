<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $search = $request->get('search', '');

        $enrollments = $user->enrollments()
            ->with(['course.category', 'course.questions', 'certificate', 'examAttempts'])
            ->when($search, fn ($q) => $q->whereHas('course', fn ($q2) => $q2->where('title', 'like', "%{$search}%")))
            ->latest()
            ->get();

        return view('staff.courses.index', compact('enrollments', 'search'));
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
