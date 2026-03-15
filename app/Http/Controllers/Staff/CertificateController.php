<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;

class CertificateController extends Controller
{
    public function index()
    {
        return view('staff.certificates');
    }
}
