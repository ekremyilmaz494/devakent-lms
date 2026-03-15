<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class QuestionController extends Controller
{
    public function index()
    {
        return view('admin.questions.index');
    }
}
