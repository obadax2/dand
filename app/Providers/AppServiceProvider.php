<?php

namespace App\Providers;
use Illuminate\Support\Facades\View;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use App\Models\Blog;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
        if (Auth::check()) {
            $cartItems = Cart::with('item')
                ->where('user_id', Auth::id())
                ->where('item_type', Blog::class)
                ->get();
        } else {
            $cartItems = collect(); // empty collection for guests
        }

        $view->with('cartItems', $cartItems);
    });
    }
}
