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
use App\Http\Controllers\TicketController;
use App\Http\Controllers\CharacterImageController;
use App\Http\Controllers\CharacterController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\MapGenerationController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\UpvoteController;
use App\Http\Controllers\DownvoteController;
use App\Models\Blog;
use App\Models\Story;
use App\Models\Poll;

// Welcome page with polls, requires auth and not banned
Route::get('/', function () {
    $polls = Poll::orderBy('created_at', 'desc')->get();
    return view('welcome', compact('polls'));
})->middleware(['auth', CheckUserBanStatus::class])->name('home');

// Routes for authenticated users with CheckUserBanStatus middleware
Route::middleware(['auth', CheckUserBanStatus::class])->group(function () {

    // HR role group
    Route::middleware(['role:hr'])->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/users/{user}/role', [UserController::class, 'updateRole'])->name('users.role.update');
        Route::post('/polls', [PollController::class, 'store'])->name('polls.store');
        Route::delete('/polls/{poll}', [PollController::class, 'destroy'])->name('polls.destroy');
    });

    // Common user management
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::post('/users/{user}/ban', [UserController::class, 'ban'])->name('users.ban');

    // Stories
    Route::get('/stories/drafts', [StoryController::class, 'drafts'])->name('stories.drafts');
    Route::get('/stories/create', [StoryController::class, 'create'])->name('stories.create');
    Route::post('/stories/generate', [StoryController::class, 'generate'])->name('stories.generate');
    Route::post('/stories', [StoryController::class, 'store'])->name('stories.store');
    Route::get('/stories/{story}/edit', [StoryController::class, 'edit'])->name('stories.edit');
    Route::put('/stories/{story}', [StoryController::class, 'update'])->name('stories.update');
    Route::delete('/stories/{story}', [StoryController::class, 'destroy'])->name('stories.destroy');
    Route::get('/stories/{id}', [StoryController::class, 'show'])->name('stories.show');
    Route::get('my-stories', [StoryController::class, 'showMyStory'])->name('stories.my');

    // Characters
    Route::get('/my-characters', [CharacterController::class, 'myCharacters'])->name('characters.my');
    Route::post('/characters/generate-images', [CharacterImageController::class, 'generateImages'])->name('characters.generate.images');

    // AJAX User search
    Route::get('/users/ajaxSearch', [UserController::class, 'ajaxSearch'])->name('users.ajaxSearch');

    // Follow / Unfollow
    Route::post('/follow/{user}', [FollowController::class, 'follow'])->name('followers.follow');
    Route::post('/unfollow/{user}', [FollowController::class, 'unfollow'])->name('followers.unfollow');

    // Friend Requests and management
    Route::post('/friends/request/{user}', [FriendController::class, 'sendRequest'])->name('friends.sendRequest');
    Route::post('/friends/accept/{user}', [FriendController::class, 'acceptRequest'])->name('friends.acceptRequest');
    Route::post('/friends/reject/{user}', [FriendController::class, 'rejectRequest'])->name('friends.rejectRequest');
    Route::delete('/friends/{user}', [FriendController::class, 'unfriend'])->name('friends.unfriend');
    Route::post('/friends/accept/{user_id}', [FriendController::class, 'acceptFriendRequest'])->name('friend.accept');

    // User profile and password
    Route::get('/user/profile', [UserProfileController::class, 'show'])->name('user.profile');
    Route::post('/user/profile/update', [UserProfileController::class, 'updateProfile'])->name('user.updateProfile');
    Route::post('/user/password/change', [UserProfileController::class, 'changePassword'])->name('user.changePassword');

    // Dashboard and blogs
    Route::get('/dashboard', [BlogController::class, 'dashboard'])->name('dashboard');
    Route::post('/blogs/create', [BlogController::class, 'create'])->name('blogs.create');
    Route::post('/blogs/{blog}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::post('/blogs/{blog}/upvote', [UpvoteController::class, 'store'])->name('upvotes.store');
    Route::post('/blogs/{blog}/downvote', [DownvoteController::class, 'store'])->name('downvotes.store');

    // Purchases and payments
    Route::post('/purchase', [PurchaseController::class, 'purchase'])->name('purchase');
    Route::post('/pay', [PaymentController::class, 'createPayment'])->name('paypal.create');
    Route::get('/payment/cancel', [PaymentController::class, 'cancel'])->name('paypal.cancel');
    Route::get('/payment/callback', [PaymentController::class, 'execute'])->name('paypal.execute');

    // Cart routes
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::get('/cart/checkout', [PaymentController::class, 'checkoutAndExecuteCart'])->name('paypal.cart.checkout');
    Route::get('/cart/execute', [PaymentController::class, 'checkoutAndExecuteCart'])->name('paypal.cart.execute');

    // Chat system
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::post('/chat/start', [ChatController::class, 'startConversation'])->name('chat.start');
    Route::get('/chat/{conversation}', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/{conversation}/send', [ChatController::class, 'sendMessage'])->name('chat.send');
    Route::delete('/chat/{conversation}/clear', [ChatController::class, 'clear'])->name('chat.clear');

    // Polls
    Route::post('/polls/{poll}/vote/{vote}', [PollController::class, 'vote'])->name('polls.vote');
    Route::delete('/polls/{poll}', [PollController::class, 'destroy'])->name('polls.destroy');

    // Tickets (user role only)
    Route::middleware(['role:user'])->group(function () {
        Route::get('/tickets/form', fn() => view('tickets.form'))->name('tickets.form');
        Route::post('/tickets/store', [TicketController::class, 'store'])->name('tickets.store');
        Route::get('/tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show');
        Route::post('/tickets/{ticket}/reply', [TicketController::class, 'userReply'])->name('tickets.userReply');
    });

    // Notifications
    Route::get('/notifications', [TicketController::class, 'notifications'])->name('notifications');
    Route::post('/notifications/mark-read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.markRead');

    // Map generation
    Route::get('/maps/generate', [MapGenerationController::class, 'selectStory'])->name('maps.generate');
    Route::post('/maps/generate', [MapGenerationController::class, 'generateMap'])->name('maps.generate.post');
    Route::get('/maps/upload', [MapGenerationController::class, 'uploadForm'])->name('maps.upload.form');
    Route::post('/maps/upload', [MapGenerationController::class, 'uploadImage'])->name('maps.upload.image');
    Route::post('/upload-map-image', [MapGenerationController::class, 'apiUploadImage']);
    Route::delete('/tickets/{ticket}', [TicketController::class, 'destroy'])->name('ticket.destroy');
    Route::post('/user/remove-profile-picture', [UserController::class, 'removeProfilePicture'])->name('user.removeProfilePicture');
});

// Admin routes with auth and admin role
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::post('/admin/users/{id}/ban', [AdminController::class, 'banUser'])->name('admin.users.ban');
    Route::post('/admin/users/{id}/unban', [AdminController::class, 'unbanUser'])->name('admin.users.unban');
    Route::get('/admin/tickets', [TicketController::class, 'index'])->name('tickets.index');
    Route::post('/admin/tickets/{ticket}/reply', [TicketController::class, 'reply'])->name('tickets.reply');
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

Route::fallback(function () {
    return view('errors.404');
});
