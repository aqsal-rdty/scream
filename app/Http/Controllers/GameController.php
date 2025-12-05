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

        $reward = '';
        if ($request->score >= 1000) {
            $reward = 'Quest';
        } elseif ($request->score >= 800) {
            $reward = 'Gold';
        } elseif ($request->score >= 500) {
            $reward = 'Silver';
        } elseif ($request->score >= 200) {
            $reward = 'Bronze';
        }

        Score::create([
            'name' => $request->name,
            'score' => $request->score,
            'reward' => $reward,
        ]);

        return response()->json(['success' => true]);
    }

    public function leaderboard()
    {
        $scores = Score::orderBy('score', 'desc')->take(10)->get(['name', 'score', 'reward']);
        return response()->json($scores);
    }

    public function opening()
    {
        $leaderboard = Score::orderBy('score', 'desc')->take(10)->get(['name', 'score', 'reward']);
        return view('game.opening', ['leaderboard' => $leaderboard]);
    }
}
