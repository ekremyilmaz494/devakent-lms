<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    public function index()
    {
        return view('staff.profile');
    }
}
