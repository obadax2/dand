<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;

// Route for the welcome page
Route::get('/', function () {
    return view('welcome'); // Adjust to render the Blade view
})->name('home');


Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register.form');
Route::post('/register', [RegisterController::class, 'register'])->name('register');
Route::get('/verify-code', function () {
    return view('auth.verify_code');
})->name('verify.code.form');
Route::post('/verify-email', [RegisterController::class, 'verifyEmail'])->name('verify.email');