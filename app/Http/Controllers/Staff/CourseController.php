<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;

class CourseController extends Controller
{
    public function index()
    {
        return view('staff.courses.index');
    }

    public function show($id)
    {
        return view('staff.courses.show', ['courseId' => $id]);
    }
}
