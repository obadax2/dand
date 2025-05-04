{{-- resources/views/stories/edit.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Edit Story: {{ $story->title ?: 'Untitled Draft' }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        form div { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"], textarea, select {
            width: 100%;
            padding: 8px;
            box-sizing: border-box; /* Include padding in width */
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        textarea {
            min-height: 200px; /* Give ample space for content */
        }
        button {
            padding: 10px 15px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #218838;
        }
        .back-link {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #007bff;
        }
    </style>
</head>
<body>
    <h1>Edit Story: "{{ $story->title ?: 'Untitled Draft' }}"</h1>

    <form action="{{ route('stories.update', $story->id) }}" method="POST">
        @csrf
        @method('PUT') {{-- Use the PUT method for updates --}}

        <div>
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" value="{{ old('title', $story->title) }}" required>
            @error('title')
                <span style="color: red;">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label for="genre">Genre:</label>
            <input type="text" id="genre" name="genre" value="{{ old('genre', $story->genre) }}" required>
            @error('genre')
                <span style="color: red;">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label for="content">Content:</label>
            <textarea id="content" name="content" required>{{ old('content', $story->content) }}</textarea>
            @error('content')
                <span style="color: red;">{{ $message }}</span>
            @enderror
        </div>

        {{-- Optional: Allow changing status (e.g., from draft to published) --}}
        <div>
            <label for="status">Status:</label>
            <select id="status" name="status" required>
                <option value="draft" {{ old('status', $story->status) == 'draft' ? 'selected' : '' }}>Draft</option>
              
            </select>
            @error('status')
                <span style="color: red;">{{ $message }}</span>
            @enderror
        </div>


        <button type="submit">Update Story</button>
    </form>

    <a href="{{ route('stories.drafts') }}" class="back-link">Back to Drafts</a>

</body>
</html>