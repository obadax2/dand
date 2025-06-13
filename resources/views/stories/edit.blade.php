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
        .form-container {
            background-color: #16383B;
            padding: 30px;
            border-radius: 12px;
            max-width: 700px;
            margin: auto;
        }

        label {
            color: #ffffff;
            margin-bottom: 6px;
            font-weight: 500;
        }

        input[type="text"],
        textarea,
        select {
            width: 100%;
            padding: 10px;
            background-color: #ffffff;
            color: #000;
            border-radius: 8px;
            resize: vertical;
        }


        .btn-primary {
            background-color: #122620;
            border: none;
            color: #fff;
            font-weight: bold;
            padding: 10px 20px;
            border-radius: 8px;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #fff;
            color: #000;
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: #ffffff;
            text-decoration: none;
        }

        textarea {
            min-height: 200px;
            resize: none;
            overflow-y: auto;
        }

        .error {
            color: #ff6b6b;
            font-size: 0.9rem;
        }
    </style>
</head>

<body>
        <div>
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

                    <button type="submit" class="btn btn-light">Update Story</button>
                </form>

                <a href="{{ route('stories.drafts') }}" class="back-link">← Back to Drafts</a>
            </div>
        </div>
</body>

</html>
