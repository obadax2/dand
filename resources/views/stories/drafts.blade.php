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
        h1 {
            color: #05EEFF;
            text-align: center;
            margin-bottom: 30px;
        }

        .drafts {
            list-style: none;
            padding: 0;
            max-width: 1000px;
            margin: 0 auto;
        }

        .drafts li {
            background-color: rgba(25, 23, 75, 0.5);
            backdrop-filter: blur(1px);
            box-shadow: 0 0 15px rgba(0, 183, 255, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 20px;
            margin-bottom: 20px;
            position: relative;
            border-radius: 8px;
        }

        .drafts li a {
            text-decoration: none;
            font-size: 1.1rem;
            color: #05EEFF;
        }

        .story-content {
            margin-top: 15px;
            border-top: 1px dashed #05EEFF;
            padding-top: 10px;
            white-space: pre-wrap;
            color: #ccc;
        }

        .action-buttons {
            position: absolute;
            top: 15px;
            right: 15px;
            display: flex;
            gap: 8px;
        }



        .edit-button {
            background-color: #05EEFF;
            color: #06043E;
        }

        .edit-button:hover {
            background-color: #00bfff;
        }

        .create {
            background-color: #05EEFF;
            color: #06043E !important;
            font-weight: bold;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            transition: background-color 0.3s ease;
        }

        .create:hover {
            background-color: #00d0ff;
        }

        small {
            color: #aaa;
        }
    </style>
</head>

<body>
    <div class="hero-section">
        <div>
            <br>
            @include('layout.nav')
            <div>
                <div class="d-flex align-items-center justify-content-center gap-3 mb-4">
                    <h1 class="m-0">Your Draft Stories</h1>
                    <img src="{{ asset('tool.png') }}" alt="Draft Icon" style="width: 40px; height: 40px;">
                </div>

                @if ($draftStories->isEmpty())
                    <div class="d-flex flex-column justify-content-center align-items-center text-center min-vh-100"
                        style="margin-top: -30vh;">
                        <p class="text-uppercase fs-3 fw-bold mb-4">
                            You have no draft stories yet
                        </p>
                        <a href="{{ route('stories.create') }}" class="btn create">
                            Create a new story <i class="fas fa-reply ms-2"></i>
                        </a>
                    </div>
                @else
                    <ul class="drafts">
                        @foreach ($draftStories as $story)
                            <li>
                                <a href="{{ route('stories.edit', $story->id) }}">
                                    {{ $story->title ?: 'Untitled Draft' }}
                                </a>

                                <div class="nav-links d-flex gap-2 position-absolute top-0 end-0 m-3">
                                    <a href="{{ route('stories.edit', $story->id) }}" class="edit-button">
                                        Edit
                                    </a>

                                    <form action="{{ route('stories.destroy', $story->id) }}" method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this story?');"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger fw-semibold">Delete</button>
                                    </form>
                                </div>
                                <br>
                                <small>Genre: {{ $story->genre ?: 'N/A' }} | Status: {{ $story->status }}</small>

                                <div class="story-content">
                                    <p>{{ $story->content ?: 'No content yet.' }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>

</body>

</html>
