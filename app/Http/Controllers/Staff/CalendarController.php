<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;

class CalendarController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $enrollments = $user->enrollments()
            ->with(['course.category'])
            ->get();

        $events = [];

        foreach ($enrollments as $enrollment) {
            $course = $enrollment->course;
            $color = $course->category?->color ?? '#14B8A6';

            if ($course->start_date) {
                $events[] = [
                    'date' => $course->start_date->format('Y-m-d'),
                    'title' => $course->title,
                    'type' => 'start',
                    'color' => $color,
                    'status' => $enrollment->status,
                    'course_id' => $course->id,
                    'category' => $course->category?->name ?? 'Genel',
                ];
            }

            if ($course->end_date) {
                $events[] = [
                    'date' => $course->end_date->format('Y-m-d'),
                    'title' => $course->title,
                    'type' => 'deadline',
                    'color' => $color,
                    'status' => $enrollment->status,
                    'course_id' => $course->id,
                    'category' => $course->category?->name ?? 'Genel',
                    'is_past' => $course->end_date->isPast(),
                ];
            }
        }

        $upcoming = $enrollments
            ->filter(fn ($e) => $e->course->end_date && $e->course->end_date->isFuture() && $e->status !== 'completed')
            ->sortBy(fn ($e) => $e->course->end_date)
            ->take(5);

        return view('staff.calendar', compact('events', 'upcoming'));
    }
}
