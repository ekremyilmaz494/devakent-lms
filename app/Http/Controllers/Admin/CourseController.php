<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;

class CourseController extends Controller
{
    public function index()
    {
        return view('admin.courses.index');
    }

    public function create()
    {
        return view('admin.courses.create');
    }

    public function edit(Course $course)
    {
        return view('admin.courses.edit', compact('course'));
    }
}
