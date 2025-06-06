<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PollController;
use App\Http\Controllers\AdminController;
use App\Http\Middleware\CheckUserBanStatus;
use App\Http\Controllers\StoryController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\BlogController;
use App\Models\Blog;
use App\Models\Story;

// Route for the welcome page
Route::get('/', function () {
    // Your logic here, e.g. return a view
    return view('welcome');
})->middleware(['auth', CheckUserBanStatus::class])
    ->name('home');

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
    // Route for AJAX search
    Route::get('/users/ajaxSearch', [UserController::class, 'ajaxSearch'])->name('users.ajaxSearch');

    // Follow / Unfollow
    Route::post('/follow/{user}', [FollowController::class, 'follow'])->name('followers.follow');
    Route::post('/unfollow/{user}', [FollowController::class, 'unfollow'])->name('followers.unfollow');

    // Friend Requests
    Route::post('/friends/request/{user}', [FriendController::class, 'sendRequest'])->name('friends.sendRequest');
    Route::post('/friends/accept/{user}', [FriendController::class, 'acceptRequest'])->name('friends.acceptRequest');
    Route::post('/friends/reject/{user}', [FriendController::class, 'rejectRequest'])->name('friends.rejectRequest');
    Route::delete('/friends/{user}', [FriendController::class, 'unfriend'])->name('friends.unfriend');
    Route::get('/user/profile', [UserProfileController::class, 'show'])->name('user.profile');

    Route::post('/user/profile/update', [UserProfileController::class, 'updateProfile'])->name('user.updateProfile');
    Route::post('/user/password/change', [UserProfileController::class, 'changePassword'])->name('user.changePassword');
    Route::post('/friends/accept/{user_id}', [FriendController::class, 'acceptFriendRequest'])->name('friend.accept');
    Route::get('/dashboard', [BlogController::class, 'dashboard'])->name('dashboard');

    Route::post('/friends/accept/{user_id}', [FriendController::class, 'acceptFriendRequest'])->name('friend.accept');

    Route::post('/purchase', [PurchaseController::class, 'purchase'])->name('purchase');
    Route::get('/payment/cancel', [PaymentController::class, 'cancel'])->name('paypal.cancel');
    // In web.php
    Route::post('/pay', [PaymentController::class, 'createPayment'])->name('paypal.create');
    Route::get('/payment/callback', [PaymentController::class, 'execute'])->name('paypal.execute');
    // Handle blog creation
    Route::post('/blogs/create', [BlogController::class, 'create'])->name('blogs.create')->middleware('auth');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

    Route::get('/cart/checkout', [PaymentController::class, 'checkoutAndExecuteCart'])->name('paypal.cart.checkout');
    Route::get('/cart/execute', [PaymentController::class, 'checkoutAndExecuteCart'])->name('paypal.cart.execute');
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
Route::get('/login', [RegisterController::class, 'showRegistrationForm'])->name('register.form');
Route::post('/login', [RegisterController::class, 'register'])->name('register');
Route::get('/verify-code', function () {
    return view('auth.verify_code');
})->name('verify.code.form');
Route::post('/verify-email', [RegisterController::class, 'verifyEmail'])->name('verify.email');
