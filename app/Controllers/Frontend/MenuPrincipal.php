<?php

namespace App\Controllers\Frontend;

use App\Controllers\BaseController;

class MenuPrincipal extends BaseController
{
    public function index(): string
    {
        return view('menu_principal');
    }
}
