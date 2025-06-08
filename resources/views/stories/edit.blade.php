<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Edit Story: {{ $story->title ?: 'Untitled Draft' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.lineicons.com/3.0/lineicons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <style>
        h1 {
            color: #05EEFF;
            margin-bottom: 30px;
        }

        .form-container {
            background-color: rgba(25, 23, 75, 0.5);
            backdrop-filter: blur(2px);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0, 183, 255, 0.3);
            max-width: 700px;
            margin: auto;
        }

        label {
            color: #05EEFF;
            margin-bottom: 6px;
            font-weight: 500;
        }

        input[type="text"],
        textarea,
        select {
            width: 100%;
            padding: 10px;
            background-color: #2a2860;
            color: #fff;
            border-radius: 8px;
            resize: vertical;
        }

        textarea {
            min-height: 200px;
        }

        .btn-primary {
            background-color: #05EEFF;
            border: none;
            color: #06043E;
            font-weight: bold;
            padding: 10px 20px;
            border-radius: 8px;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #03bfd4;
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: #05EEFF;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .error {
            color: #ff6b6b;
            font-size: 0.9rem;
        }
    </style>
</head>

<body>
    <div class="hero-section">
        <div>
            <br>
            @include('layout.nav')
            <div class="form-container">
                <h1>Edit Story: "{{ $story->title ?: 'Untitled Draft' }}"</h1>

                <form action="{{ route('stories.update', $story->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="title">Title:</label>
                        <input type="text" id="title" name="title" value="{{ old('title', $story->title) }}"
                            required>
                        @error('title')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="genre">Genre:</label>
                        <input type="text" id="genre" name="genre" value="{{ old('genre', $story->genre) }}"
                            required>
                        @error('genre')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="content">Content:</label>
                        <textarea id="content" name="content" required>{{ old('content', $story->content) }}</textarea>
                        @error('content')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="status">Status:</label>
                        <select id="status" name="status" required>
                            <option value="draft" {{ old('status', $story->status) == 'draft' ? 'selected' : '' }}>
                                Draft</option>
                        </select>
                        @error('status')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Update Story</button>
                </form>

                <a href="{{ route('stories.drafts') }}" class="back-link">← Back to Drafts</a>
            </div>
        </div>
    </div>
</body>

</html>
