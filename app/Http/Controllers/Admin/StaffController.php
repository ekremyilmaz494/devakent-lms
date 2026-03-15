<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class StaffController extends Controller
{
    public function index()
    {
        return view('admin.staff.index');
    }

    public function create()
    {
        return view('admin.staff.create');
    }
}
