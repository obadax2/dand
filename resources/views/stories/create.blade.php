<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Create a New Story</title>
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #06043E;
            color: #ffffff;
        }

        textarea {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            margin-bottom: 15px;
            background-color: #2a2860;
            color: #ffffff;
            overflow: hidden;
            resize: none;
            /* Prevent manual resizing since it's auto-resized */
            min-height: 100px;
        }


        .container {
            background-color: #19174B;
            padding: 30px;
            border-radius: 10px;
            max-width: 900px;
            margin: 40px auto;
        }

        .nav-links {
            display: flex;
            justify-content: flex-end;
            gap: 20px;
            margin-bottom: 20px;
        }


        .nav-links a {
            color: #05EEFF;
            text-decoration: none;
            padding: 6px 12px;
            background-color: #2a2860;
            border-radius: 5px;
        }

        .nav-links a:hover {
            background-color: #3b386c;
        }

        textarea,
        input[type="text"] {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            margin-bottom: 15px;
            background-color: #2a2860;
            color: #ffffff;
        }

        button {
            background-color: #05EEFF;
            color: #000;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: bold;
        }

        button:hover {
            background-color: #03bfd4;
        }

        .chat-box {
            background-color: #2a2860;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            white-space: pre-wrap;
            word-wrap: break-word;
            max-height: 400px;
            overflow-y: auto;
        }

        .alert {
            margin-top: 15px;
        }

        h1,
        h2 {
            color: #05EEFF;
        }

        #charCount {
            display: block;
            margin-top: -10px;
            margin-bottom: 15px;
            font-size: 0.9rem;
            color: #ccc !important;
            /* or #05EEFF for highlight */
        }

        .sidebar-column {
            flex-shrink: 0;
            background-color: #1e1b59;
            padding: 15px;
            border-radius: 10px;
            width: 250px;
            height: fit-content;
        }

        .sidebar-column .card {
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
    @include('layout.nav')
    <div class="d-flex" style=" padding: 20px;">
        <div class="sidebar-column">
            <div class="card">STORY</div>
            <div class="card">HOVER</div>
            <div class="card">HOVER</div>
            <div class="card">HOVER</div>
            <div class="card">HOVER</div>
            <div class="card">HOVER</div>
        </div>
        <div class="container flex-grow-1">
            <!-- Navigation Links -->
            <div class="page-header d-flex justify-content-between align-items-center mb-4">
                <h1 class="m-0">Create a New Story</h1>
                <div class="nav-links d-flex gap-2">
                    <a href="{{ route('stories.drafts') }}">Drafts</a>
                    <a href="{{ route('dashboard') }}">Blog Index</a>
                </div>
            </div>


            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (isset($generatedContent))
                <h2>Generated Story Content</h2>
                <div class="chat-box">{{ $generatedContent }}</div>

                <h2>Complete Your Story Details</h2>
                <form method="POST" action="{{ route('stories.store') }}">
                    @csrf
                    <label for="title">Story Title:</label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" required>

                    <label for="genre">Genre:</label>
                    <input type="text" name="genre" id="genre" value="{{ old('genre') }}" required>

                    <button type="submit">Save Story</button>
                </form>
            @else
                <form method="POST" action="{{ route('stories.generate') }}">
                    @csrf
                    <label for="prompt">Enter your prompt:</label>
                    <textarea name="prompt" id="prompt" maxlength="2000" required>{{ old('prompt') }}</textarea>
                    <small id="charCount" class="text-muted">0 / 2000 characters</small>

                    <button type="submit">Generate Story</button>
                </form>
            @endif
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const textarea = document.getElementById('prompt');
                const charCount = document.getElementById('charCount');

                const autoResize = (el) => {
                    el.style.height = 'auto';
                    el.style.height = el.scrollHeight + 'px';
                };

                if (textarea) {
                    autoResize(textarea);
                    textarea.addEventListener('input', () => {
                        autoResize(textarea);
                        if (charCount) {
                            charCount.textContent = `${textarea.value.length} / 2000 characters`;
                        }
                    });

                    // Initial count
                    if (charCount) {
                        charCount.textContent = `${textarea.value.length} / 2000 characters`;
                    }
                }

                const alert = document.querySelector('.alert');
                if (alert) {
                    setTimeout(() => {
                        alert.style.transition = 'opacity 0.5s ease';
                        alert.style.opacity = '0';
                        setTimeout(() => alert.remove(), 500);
                    }, 3000);
                }
            });
        </script>


</body>

</html>
