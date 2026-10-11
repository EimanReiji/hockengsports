<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


Route::get('/', function () {
    return view('welcome');
});

// Login Page
Route::get('/', [AuthController::class, 'showLogin'])
    ->name('login');


// Login Process
Route::post('/login', [AuthController::class, 'login'])
    ->name('loginUser');


// Register Process
Route::post('/register', [AuthController::class, 'register'])
    ->name('signupUser');


// Logout
Route::get('/logout', [AuthController::class, 'logout'])
    ->name('logout');


