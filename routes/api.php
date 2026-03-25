<?php

use App\Http\Controllers\Api\CinemaController;
use App\Http\Controllers\Api\MovieSearchController;
use App\Http\Controllers\Api\WeatherController;
use Illuminate\Support\Facades\Route;

Route::get('/movies/search', [MovieSearchController::class, 'index'])->name('api.movies.search');
Route::get('/cinemas/nearby', [CinemaController::class, 'index'])->name('api.cinemas.nearby');
Route::get('/weather', [WeatherController::class, 'show'])->name('api.weather.show');
