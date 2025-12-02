<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;

// Redirect ke opening
Route::get('/', function () {
    return redirect('/opening');
});

// halaman opening
Route::get('/opening', function () {
    return view('game.opening');
});

// halaman game (TANPA LOGIN)
Route::get('/game', [GameController::class, 'index'])->name('game');

// API
Route::post('/submit-score', [GameController::class, 'submitScore'])->name('submit.score');
Route::get('/leaderboard', [GameController::class, 'leaderboard'])->name('leaderboard');