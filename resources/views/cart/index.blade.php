<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.lineicons.com/3.0/lineicons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('style.css') }}">
</head>
<style>
    .create {
        background-color: #05EEFF;
        color: #06043E !important;
        font-weight: bold;
        border: none;
        padding: 10px 20px;
        border-radius: 6px;
        transition: background-color 0.3s ease;
    }

    .create:hover {
        background-color: #00d0ff;
    }
</style>

<body>
    @if (session('success'))
            <div class="alert alert-success custom-alert bg-custom-success" id="successAlert">
                {{ session('success') }}
            </div>
        @endif


        @if ($errors->any())
            <div class="alert alert-danger custom-alert" id="successAlert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

            @include('layout.nav')
            <div class="d-flex" style="padding: 20px;">
                <div class="container3 flex-grow-1">
                    @if ($cartItems->isEmpty())
                        <div class="d-flex flex-column justify-content-center align-items-center text-center min-vh-100"
                            style="margin-top: -30vh;">
                            <p class="text-uppercase fs-3 fw-bold mb-4">
                                Your cart is empty </p>
                            <a href="{{ route('dashboard') }}" class="btn create">
                                Shop now <i class="fas fa-reply ms-2"></i>
                            </a>
                        </div>
                    @else
                        @foreach ($cartItems as $cart)
                            <div class="story p-3 mb-3"
                                style="
                                background-color: rgba(25, 23, 75, 0.5);
                                backdrop-filter: blur(5px);
                                border-radius: 12px;
                                color: #BDAEC6;
                                box-shadow: 0 0 10px #725AC1;">
                                <h3 style="color: #05EEFF;">{{ $cart->item->story->title }}</h3>
                                <p>Price: <strong>${{ number_format($cart->price, 2) }}</strong></p>
                                <form action="{{ route('cart.remove', $cart->id) }}" method="POST"
                                    style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger"
                                        style="font-size: 14px; padding: 8px 15px;">Remove</button>
                                </form>
                            </div>
                        @endforeach

                        <form method="GET" action="{{ route('paypal.cart.checkout') }}">
                            <button type="submit" class="genButton"
                                style="font-size: 16px; padding: 12px 25px; margin-top: 1rem;">
                                Checkout with PayPal
                            </button>
                        </form>
                    @endif

                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
<script>
    const alert = document.querySelector('.alert');
    if (alert) {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 3000);
    }
</script>

</html>
