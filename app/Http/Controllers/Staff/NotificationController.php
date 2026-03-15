<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;

class NotificationController extends Controller
{
    public function index()
    {
        return view('staff.notifications');
    }
}
