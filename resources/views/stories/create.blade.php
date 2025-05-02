<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Create a New Story</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        form { max-width: 600px; margin: auto; }
        textarea { width: 100%; height: 100px; }
        input[type="text"] { width: 100%; }
        button { padding: 10px 20px; margin-top: 10px; }
        .success { color: green; }
        .error { color: red; }
    </style>
</head>
<body>
    <h1>Create a New Story</h1>

    @if(session('success'))
        <p class="success">{{ session('success') }}</p>
    @endif

    @if($errors->any())
        <div class="error">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('stories.store') }}">
        @csrf
        <label for="prompt">Enter your prompt:</label><br>
        <textarea name="prompt" id="prompt" required>{{ old('prompt') }}</textarea><br><br>
        <button type="submit">Generate Story</button>
    </form>
</body>
</html>