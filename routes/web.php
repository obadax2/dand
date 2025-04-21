<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
// Route for the welcome page
Route::get('/', function () {
    return view('welcome'); // Adjust to render the Blade view
})->name('home');


Route::middleware(['auth'])->group(function () { 
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users/{user}/role', [UserController::class, 'updateRole'])->name('users.role.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
});


Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register.form');
Route::post('/register', [RegisterController::class, 'register'])->name('register');
Route::get('/verify-code', function () {
    return view('auth.verify_code');
})->name('verify.code.form');
Route::post('/verify-email', [RegisterController::class, 'verifyEmail'])->name('verify.email');