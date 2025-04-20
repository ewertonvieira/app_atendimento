<?php

namespace App\Http\Controllers\Tecnico;

use App\Http\Controllers\Controller;

class TecnicoController extends Controller
{
    public function index()
    {
        return view('tecnico.dashboard');
    }
}
