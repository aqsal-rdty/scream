<?php

namespace App\Http\Controllers;

use App\Models\Score;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GameController extends Controller
{
    public function index()
    {
        return view('game.index');
    }

    public function submitScore(Request $request)
    {
        $request->validate([
            'score' => 'required|integer',
            'name' => 'required|string'
        ]);

        Score::create([
            'name' => $request->name,
            'score' => $request->score
        ]);

        return response()->json(['success' => true]);
    }

    public function leaderboard()
    {
        $data = Score::orderBy('score', 'desc')
            ->take(10)
            ->get(['name', 'score', 'created_at']);

        return response()->json($data);
    }
}
