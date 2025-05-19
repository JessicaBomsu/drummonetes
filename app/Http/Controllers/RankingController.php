<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Ranking;

class RankingController extends Controller
{
    public function index()
    {
        $ranking = Ranking::orderByDesc('pontuacao')->take(10)->get();

        return view('ranking', compact('ranking'));
    }

}
