<!DOCTYPE html>
<html>

<head>
    <title>Verify Your Email</title>
    <link rel="stylesheet" href="style.css">
</head>

<body class="register_body">
    <div class="hero-section">
        <div>
            @if (session('status'))
                <div>{{ session('status') }}</div>
            @endif
            <section class="wrapper" style="padding: 20px 30px 50px;">
                <div class="form signup">
                    <header>Verify Email</header>
                    <form method="POST" action="{{ route('verify.email') }}" class="verify">
                        @csrf
                        <p class="centered-text">
                            To create your account, you must ensure that this email belongs to you. Please enter the
                            code that
                            was sent to your email.
                        </p>
                        <input type="text" class="code" name="verification_code" id="verification_code" style="font-size: 20px"
                            required>
                        <input type="submit" style="background-color: #725AC1" value="Confirm" />
                    </form>
                </div>
            </section>
        </div>
    </div>
    {{-- <form method="POST" action="{{ route('verify.email') }}">
        @csrf
        <div>
            <label for="verification_code">Enter Verification Code</label>
            <input type="text" name="verification_code" id="verification_code" required>
        </div>
        <button type="submit">Verify</button>
    </form> --}}
</body>

</html>
