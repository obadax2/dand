>
<!DOCTYPE html>
<html>
<head>
    <title>Verify Your Email</title>
</head>
<body>
    <h1>Verify Your Email</h1>

    @if(session('status'))
        <div>{{ session('status') }}</div>
    @endif

    <p>Your verification code: <strong>{{ $verificationCode }}</strong></p>

    <form method="POST" action="{{ route('verify.email') }}">
        @csrf
        <div>
            <label for="verification_code">Enter Verification Code</label>
            <input type="text" name="verification_code" id="verification_code" required>
        </div>
        <button type="submit">Verify</button>
    </form>
</body>
</html>