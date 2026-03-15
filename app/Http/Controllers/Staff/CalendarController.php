<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;

class CalendarController extends Controller
{
    public function index()
    {
        return view('staff.calendar');
    }
}
