<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Create a New Story</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        form { max-width: 600px; margin: auto; }
        textarea { width: 100%; height: 200px; /* Increased height for content */ }
        input[type="text"] { width: 100%; padding: 8px; margin-bottom: 10px; }
        button { padding: 10px 20px; margin-top: 10px; cursor: pointer; }
        .success { color: green; }
        .error { color: red; }
        .chat-box {
            border: 1px solid #ccc;
            padding: 15px;
            margin-bottom: 20px;
            background-color: #f9f9f9;
            white-space: pre-wrap; /* Preserve line breaks */
            word-wrap: break-word; /* Break long words */
            max-height: 400px; /* Limit height and add scroll */
            overflow-y: auto;
        }
        .header-links {
            position: absolute; /* Position it absolutely relative to the body or a container */
            top: 20px; /* Adjust as needed */
            right: 20px; /* Adjust as needed */
        }
        .header-links a {
            text-decoration: none; /* Remove underline */
            color: #333; /* Default link color */
            margin-left: 15px; /* Space between links if you add more later */
            font-weight: bold;
        }
        .header-links a:hover {
            color: #000; /* Darker color on hover */
        }
        /* Optional: Add an icon (using a simple character or an icon font/SVG) */
        .header-links a::before {
            content: '📄'; /* Unicode character for a document/page */
            margin-right: 5px;
        }
    </style>
</head>
<body>

    <div class="header-links">
        {{-- Link to the drafts page --}}
        <a href="{{ route('stories.drafts') }}">Drafts</a>
    </div>

    <h1>Create a New Story</h1>

    @if(session('success'))
        <p class="success">{{ session('success') }}</p>
    @endif

    @if(session('error'))
        <p class="error">{{ session('error') }}</p>
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

    {{-- Check if generated content exists --}}
    @if(isset($generatedContent))
        <h2>Generated Story Content:</h2>
        <div class="chat-box">
            {{ $generatedContent }}
        </div>

        <h2>Complete Your Story Details</h2>

        <form method="POST" action="{{ route('stories.store') }}">
            @csrf

            {{-- The generated content is not directly in this form,
                 it's passed via the session and retrieved in the controller. --}}

            <label for="title">Story Title:</label><br>
            <input type="text" name="title" id="title" value="{{ old('title') }}" required><br><br>

            <label for="genre">Genre:</label><br>
            <input type="text" name="genre" id="genre" value="{{ old('genre') }}" required><br><br>

            <button type="submit">Save Story</button>
        </form>

    @else
        {{-- Show the initial prompt form --}}
        <form method="POST" action="{{ route('stories.generate') }}">
            @csrf
            <label for="prompt">Enter your prompt:</label><br>
            <textarea name="prompt" id="prompt" required>{{ old('prompt') }}</textarea><br><br> {{-- Corrected closing textarea tag --}}
            <button type="submit">Generate Story</button> {{-- Added a button for the prompt form --}}
        </form>
    @endif

</body>
</html>