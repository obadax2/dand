<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Create a New Story</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.lineicons.com/3.0/lineicons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('style.css') }}">
</head>

<body>
    @if (session('success'))
        <div class="alert alert-success custom-alert" id="successAlert">{{ session('success') }}</div>
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

    <div class="hero-section">
        <div>
            <br>
            @include('layout.nav')
            <div class="d-flex" style="padding: 20px;">
                <div class="container3 flex-grow-1">
                    <!-- Navigation Links -->
                    <div class="page-header d-flex justify-content-between align-items-center mb-4">
                        <h1 class="m-0">Create a New Story</h1>
                        <div class="nav-links d-flex gap-2">
                            <a href="{{ route('stories.drafts') }}">Drafts</a>
                            <a href="{{ route('dashboard') }}">Blog Index</a>
                        </div>
                    </div>

                    @if (isset($generatedContent))
                        <h2>Generated Story Content</h2>
                        <div class="chat-box">{{ $generatedContent }}</div>

                        {{-- 🎭 Show Generated Characters --}}
                        @if (isset($characters) && count($characters))
                            <h3 class="mt-4">Generated Characters</h3>
                            <ul class="list-group mb-4">
                                @foreach ($characters as $char)
                                    <li class="list-group-item">
                                        <strong>{{ $char['name'] }}</strong>: {{ $char['description'] }}
                                    </li>
                                @endforeach
                            </ul>
                        @endif

                        <h2>Complete Your Story Details</h2>
                        <form method="POST" action="{{ route('stories.store') }}">
                            @csrf
                            <p for="title">Story Title:</p>
                            <input type="text" name="title" id="title" value="{{ old('title') }}" required autocomplete="off">

                            <p for="genre">Genre:</p>
                            <input type="text" name="genre" id="genre" value="{{ old('genre') }}" required autocomplete="off">

                            <button class="userButton" type="submit">Save Story</button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('stories.generate') }}">
                            @csrf
                            <p for="prompt">Enter your prompt:</p>
                            <textarea name="prompt" id="prompt" maxlength="2000" required>{{ old('prompt') }}</textarea>
                            <small id="charCount" class="text-muted">0 / 2000 characters</small>

                            <button class="genButton" type="submit">Generate Story</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
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
