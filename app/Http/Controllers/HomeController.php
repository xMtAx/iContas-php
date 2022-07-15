<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Mostra página inicial
     *
     * @return view
     */
    public function index()
    {
        return view('index');
    }
}
