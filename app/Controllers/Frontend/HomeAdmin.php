<?php

namespace App\Controllers\Frontend;

use App\Controllers\BaseController;

class HomeAdmin extends BaseController
{
    public function index(): string
    {
        return view('home_admin');
    }
}
