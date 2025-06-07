<h2>Your Cart</h2>

@foreach ($cartItems as $cart)
    <div class="story">
        <h3>{{ $cart->item->story->title }}</h3>
        <p>Price: ${{ number_format($cart->price, 2) }}</p>
        <form action="{{ route('cart.remove', $cart->id) }}" method="POST">
            @csrf @method('DELETE')
            <button type="submit">Remove</button>
        </form>
    </div>
@endforeach

<form method="GET" action="{{ route('paypal.cart.checkout') }}">
    <button type="submit">Checkout with PayPal</button>
</form>
