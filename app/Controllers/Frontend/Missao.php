<?php

namespace App\Controllers\Frontend;

use App\Controllers\BaseController;

class Missao extends BaseController
{
    public function index(): string
    {
        return view('missao');
    }
}
