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
            'score' => 'required|integer'
        ]);

        $userId = Auth::check() ? Auth::id() : null;

        Score::create([
            'user_id' => $userId,
            'score' => $request->score
        ]);

        return response()->json(['success' => true]);
    }

    public function leaderboard()
    {
        $data = Score::with('user')
            ->orderBy('score', 'desc')
            ->take(10)
            ->get()
            ->map(function($item){
                return [
                    'name' => $item->user ? $item->user->name : 'Anon',
                    'score' => $item->score,
                    'created_at' => $item->created_at->toDateTimeString()
                ];
            });

        return response()->json($data);
    }
}
