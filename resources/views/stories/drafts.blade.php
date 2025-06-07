{{-- resources/views/stories/drafts.blade.php --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Your Draft Stories</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.lineicons.com/3.0/lineicons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <style>
        .drafts {
            list-style: none;
            padding: 0;
        }

        .drafts li {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 10px;
            position: relative;
        }

        .drafts li a {
            text-decoration: none;
            font-weight: bold;
            color: #333;
        }

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
            display: flex;
            /* Arrange buttons side by side */
            gap: 5px;
            /* Space between buttons */
        }

        .action-buttons a,
        .action-buttons button {
            padding: 5px 10px;
            color: white;
            border: none;
            cursor: pointer;
            text-decoration: none;
            border-radius: 3px;
            font-size: 0.9em;
            /* Slightly smaller font */
        }

        .edit-button {
            background-color: #007bff;
        }

        .edit-button:hover {
            background-color: #0056b3;
        }

        .delete-button {
            background-color: #dc3545;
            /* Red color for delete */
        }

        .delete-button:hover {
            background-color: #c82333;
        }

        .create {
            background-color: #4070f4;
            font-weight: 600;
            border: none;
            padding: 10px 20px;
        }
    </style>
</head>

<body>
    <div class="hero-section">
        <div>
            <br>
            @include('layout.nav')
            <h1>Your Draft Stories</h1>

            @if ($draftStories->isEmpty())
                <div class="d-flex flex-column justify-content-center align-items-center text-center min-vh-100"
                    style="margin-top: -30vh;">
                    <p class="text-uppercase fs-3 fw-bold  mb-4">
                        You have no draft stories yet
                    </p>
                    <a href="{{ route('stories.create') }}" class="btn btn-info create text-white">
                        Create a new story <i class="fas fa-reply ms-2"></i>
                    </a>
                </div>
            @else
                <ul class="drafts">
                    @foreach ($draftStories as $story)
                        <li>
                            {{-- Link to view/edit the draft story --}}
                            <a
                                href="{{ route('stories.edit', $story->id) }}">{{ $story->title ?: 'Untitled Draft' }}</a>

                            {{-- Action buttons (Edit and Delete) --}}
                            <div class="action-buttons">
                                <a href="{{ route('stories.edit', $story->id) }}" class="edit-button">Edit</a>

                                <form action="{{ route('stories.destroy', $story->id) }}" method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this story?');">
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
        </div>
</body>

</html>
