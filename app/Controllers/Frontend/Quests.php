<?php

namespace App\Controllers\Frontend;

use App\Controllers\BaseController;

class Quests extends BaseController
{
    public function index(): string
    {
        return view('quests');
    }
}
