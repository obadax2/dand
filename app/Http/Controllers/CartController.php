<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Blog;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function add(Request $request)
    {
        $request->validate(['blog_id' => 'required|exists:blogs,id']);
        $user = Auth::user();
        $blog = Blog::findOrFail($request->blog_id);

        Cart::firstOrCreate(
            [
                'user_id' => $user->id,
                'item_id' => $blog->id,
                'item_type' => Blog::class, // ✅ Use fully qualified class name
            ],
            [
                'price' => $blog->price,
                'quantity' => 1,
            ]
        );

        return redirect()->route('dashboard')->with('success', 'Blog added to cart!');
    }

    public function index()
    {
        $cartItems = Cart::with('item')
            ->where('user_id', Auth::id())
            ->where('item_type', Blog::class) // ✅ Match exactly how you save it
            ->get();

        return view('cart.index', compact('cartItems'));
    }

    public function remove($id)
    {
        Cart::where('user_id', Auth::id())->where('id', $id)->delete();
        return back()->with('success', 'Item removed from cart.');
    }
}
