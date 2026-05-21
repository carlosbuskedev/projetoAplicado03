<?php

namespace App\Controllers\Frontend;

use App\Controllers\BaseController;

class Painel extends BaseController
{
    public function index(): string
    {
        return view('painel');
    }
}
