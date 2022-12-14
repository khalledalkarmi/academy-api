<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SocialiteController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/register', [AuthController::class, 'register']);

Route::post('/login', [AuthController::class, 'login']);

Route::get('/logout', [AuthController::class, 'logout']);


Route::post('/login/callback', [SocialiteController::class, 'handleProviderCallback']);


Route::get('/getMas', [\App\Http\Controllers\ChatsController::class, 'fetchMessages']);
Route::post('/sendMas', [\App\Http\Controllers\ChatsController::class, 'sendMessage']);
