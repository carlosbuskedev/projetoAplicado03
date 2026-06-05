<?php

namespace App\Controllers\Frontend;

use App\Controllers\BaseController;

class Leveling extends BaseController
{
    public function index(): string
    {
        return view('leveling');
    }
}
