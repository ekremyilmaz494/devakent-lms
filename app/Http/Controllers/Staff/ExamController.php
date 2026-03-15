<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;

class ExamController extends Controller
{
    public function index()
    {
        return view('staff.exams.index');
    }
}
