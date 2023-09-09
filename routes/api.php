<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GoogleLoginController;
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


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'getUser'])->name('getUser');
});


Route::controller(AuthController::class)->group(function () {
    Route::post('/register', 'register')->name('register');
    Route::post('/login', 'login')->name('login');
});


Route::middleware(['web'])->group(function () {
    Route::get('/auth/{provider}/redirect', [GoogleLoginController::class, 'redirect']);
    Route::get('/auth/{provider}/callback', [GoogleLoginController::class, 'callback']);
});
