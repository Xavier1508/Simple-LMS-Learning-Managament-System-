<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function index(): View
    {
        return view('attendance');
    }
}
