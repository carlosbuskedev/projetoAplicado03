<?php

namespace App\Controllers\Frontend;

use App\Controllers\BaseController;

class Usuarios extends BaseController
{
    public function index(): string
    {
        return view('usuarios');
    }
}
