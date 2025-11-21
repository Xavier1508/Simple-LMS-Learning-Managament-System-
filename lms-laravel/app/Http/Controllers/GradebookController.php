<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class GradebookController extends Controller
{
    public function index(): View
    {
        return view('gradebook');
    }
}
