<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/register', [AuthController::class, 'register'])
    ->middleware('throttle:register')->middleware('cloudflare');

Route::post('/login', [AuthController::class, 'login'])
    ->middleware('throttle:register')->middleware('cloudflare');;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    Route::get('/news', [\App\Http\Controllers\Api\NewsController::class, 'index']);
    Route::get('/news/{news}', [\App\Http\Controllers\Api\NewsController::class, 'show']);
});
