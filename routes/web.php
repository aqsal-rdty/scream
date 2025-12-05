<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;

Route::get('/', function () {
    return redirect('/opening');
});

Route::get('/opening', [GameController::class, 'opening'])->name('opening');
Route::get('/game', [GameController::class, 'index'])->name('game');
Route::post('/submit-score', [GameController::class, 'submitScore'])->name('submit.score');
Route::get('/leaderboard', [GameController::class, 'leaderboard'])->name('leaderboard');