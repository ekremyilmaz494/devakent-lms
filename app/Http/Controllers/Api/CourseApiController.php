<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CourseApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $courses = Course::with('category')
            ->where('status', 'published')
            ->when($request->search, function ($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%");
            })
            ->withCount('enrollments')
            ->orderByDesc('created_at')
            ->paginate($request->per_page ?? 15);

        return response()->json($courses);
    }

    public function show(int $id): JsonResponse
    {
        $course = Course::with(['category', 'departments', 'videos'])
            ->withCount(['enrollments', 'questions'])
            ->findOrFail($id);

        return response()->json($course);
    }

    public function myEnrollments(Request $request): JsonResponse
    {
        $enrollments = Enrollment::with(['course.category'])
            ->where('user_id', $request->user()->id)
            ->withCount([
                'examAttempts',
                'examAttempts as passed_exams_count' => fn ($q) => $q->where('is_passed', true),
            ])
            ->orderByDesc('created_at')
            ->paginate($request->per_page ?? 15);

        return response()->json($enrollments);
    }

    public function enrollmentDetail(Request $request, int $enrollmentId): JsonResponse
    {
        $enrollment = Enrollment::with([
            'course.category',
            'course.videos',
            'examAttempts' => fn ($q) => $q->orderBy('attempt_number'),
            'videoProgress',
            'certificate',
        ])
        ->where('user_id', $request->user()->id)
        ->findOrFail($enrollmentId);

        return response()->json($enrollment);
    }
}
