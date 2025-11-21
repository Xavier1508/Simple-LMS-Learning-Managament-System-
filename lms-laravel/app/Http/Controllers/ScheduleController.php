<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class ScheduleController extends Controller
{
    public function index(): View
    {
        return view('schedule');
    }
}
