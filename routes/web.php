<?php

use App\Http\Controllers\MovieController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

// These are the normal page routes the browser opens.
Route::get('/', fn () => redirect()->route('movies.index'))->name('home');
Route::get('/movies', [MovieController::class, 'index'])->name('movies.index');
Route::get('/movies/{movie:slug}', [MovieController::class, 'show'])->name('movies.show');
Route::post('/movies/{movie:slug}/reviews', [ReviewController::class, 'store'])->name('movies.reviews.store');
