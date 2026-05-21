<?php

namespace App\Controllers\Frontend;

use App\Controllers\BaseController;

class Principal extends BaseController
{
    public function index(): string
    {
        return view('principal');
    }
}
