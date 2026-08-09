<?php

namespace App\Controllers\Frontend;

use App\Controllers\BaseController;

class WeeklyDiagnostic extends BaseController
{
    public function index(): string
    {
        return view('weekly_diagnostic');
    }
}
