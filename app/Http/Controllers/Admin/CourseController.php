<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

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
}
