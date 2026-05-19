<?php

namespace App\Controllers;

class Jornada extends BaseController
{
    public function index(): string
    {
        return view('jornada');
    }
}
