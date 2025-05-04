<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PollController;
use App\Http\Controllers\AdminController;
use App\Http\Middleware\CheckUserBanStatus;
use App\Http\Controllers\StoryController;

// Route for the welcome page
Route::get('/', function () {
    return view('welcome'); // Adjust to render the Blade view
})->name('home');

// Use your middleware in route groups
Route::middleware(['auth', CheckUserBanStatus::class])->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users/{user}/role', [UserController::class, 'updateRole'])->name('users.role.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::post('/polls', [PollController::class, 'store'])->name('polls.store');
    Route::post('/users/{user}/ban', [UserController::class, 'ban'])->name('users.ban');
    Route::get('/stories/drafts', [StoryController::class, 'drafts'])->name('stories.drafts');

    Route::get('/stories/create', [StoryController::class, 'create'])->name('stories.create');
    Route::post('/stories/generate', [StoryController::class, 'generate'])->name('stories.generate');
    Route::post('/stories', [StoryController::class, 'store'])->name('stories.store');
        
        Route::get('/stories/{story}/edit', [StoryController::class, 'edit'])->name('stories.edit');
        Route::put('/stories/{story}', [StoryController::class, 'update'])->name('stories.update');
        Route::delete('/stories/{story}', [StoryController::class, 'destroy'])->name('stories.destroy'); 
});

// Admin routes
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::post('/admin/users/{id}/ban', [AdminController::class, 'banUser'])->name('admin.users.ban');
    Route::post('/admin/users/{id}/unban', [AdminController::class, 'unbanUser'])->name('admin.users.unban');
});

// Authentication routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register.form');
Route::post('/register', [RegisterController::class, 'register'])->name('register');
Route::get('/verify-code', function () {
    return view('auth.verify_code');
})->name('verify.code.form');
Route::post('/verify-email', [RegisterController::class, 'verifyEmail'])->name('verify.email');