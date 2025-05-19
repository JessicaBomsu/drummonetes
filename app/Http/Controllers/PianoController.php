<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PianoController extends Controller
{
    public function index()
    {
        return view('piano');
    }
}