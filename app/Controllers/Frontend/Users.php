<?php

namespace App\Controllers\Frontend;

use App\Controllers\BaseController;

class Users extends BaseController
{
    public function index(): string
    {
        return view('users');
    }
}
