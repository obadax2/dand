{{-- resources/views/stories/drafts.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Your Draft Stories</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        ul { list-style: none; padding: 0; }
        li { border: 1px solid #ccc; padding: 10px; margin-bottom: 10px; position: relative; }
        li a { text-decoration: none; font-weight: bold; color: #333; }
        .story-content {
            margin-top: 10px;
            border-top: 1px dashed #eee;
            padding-top: 10px;
            white-space: pre-wrap;
        }
        .action-buttons {
            position: absolute;
            top: 10px;
            right: 10px;
            display: flex; /* Arrange buttons side by side */
            gap: 5px; /* Space between buttons */
        }
        .action-buttons a,
        .action-buttons button {
            padding: 5px 10px;
            color: white;
            border: none;
            cursor: pointer;
            text-decoration: none;
            border-radius: 3px;
            font-size: 0.9em; /* Slightly smaller font */
        }
        .edit-button {
            background-color: #007bff;
        }
        .edit-button:hover {
            background-color: #0056b3;
        }
        .delete-button {
            background-color: #dc3545; /* Red color for delete */
        }
        .delete-button:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <h1>Your Draft Stories</h1>

    @if($draftStories->isEmpty())
        <p>You have no draft stories yet.</p>
        <p><a href="{{ route('stories.create') }}">Start creating a new story!</a></p>
    @else
        <ul>
            @foreach($draftStories as $story)
                <li>
                    {{-- Link to view/edit the draft story --}}
                    <a href="{{ route('stories.edit', $story->id) }}">{{ $story->title ?: 'Untitled Draft' }}</a>

                    {{-- Action buttons (Edit and Delete) --}}
                    <div class="action-buttons">
                        <a href="{{ route('stories.edit', $story->id) }}" class="edit-button">Edit</a>

                        <form action="{{ route('stories.destroy', $story->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this story?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="delete-button">Delete</button>
                        </form>
                    </div>

                    <br>
                    <small>Genre: {{ $story->genre ?: 'N/A' }} | Status: {{ $story->status }}</small>

                    {{-- Display the content here --}}
                    @if ($story->content)
                        <div class="story-content">
                            <p>{{ $story->content }}</p>
                        </div>
                    @else
                        <div class="story-content">
                            <p>No content yet.</p>
                        </div>
                    @endif
                </li>
            @endforeach
        </ul>
    @endif

    <p><a href="{{ route('stories.create') }}">Back to Create Story</a></p>

</body>
</html>