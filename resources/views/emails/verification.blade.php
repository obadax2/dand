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

</body>
</html>
