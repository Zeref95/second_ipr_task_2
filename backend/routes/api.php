<?php

use App\Http\Controllers\CityController;
use App\Http\Controllers\MovieController;


use App\Http\Controllers\MovieSessionController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('v1')->middleware('auth.apikey')->group(function () {
    Route::get('/cities', [CityController::class, 'index']);
    Route::get('/movies', [MovieController::class, 'index']);
    Route::get('/get-movies-by-date', [MovieController::class, 'getMoviesByDate']);
    Route::get('/movie-session', [MovieSessionController::class, 'index']);
    Route::post('/order', [OrderController::class, 'store']);
});
