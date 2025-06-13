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
        .modal-content {

            border: 1px solid rgba(129, 125, 125, 0.7) !important;
            /* your red border */
        }

        h1 {
            color: #000 !important;
            text-align: center;
                font-style: italic;

            margin-bottom: 30px;
        }

        .drafts {
            list-style: none;
            padding: 0;
            max-width: 1000px;
            margin: 0 auto;
        }

        .drafts li {
            background-color: #16383b;
            backdrop-filter: blur(1px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 20px;
            margin-bottom: 20px;
            position: relative;
            border-radius: 8px;
        }

        .drafts li a {
            text-decoration: none;
            font-size: 1.1rem;
            color: #ffffff;
        }

        .story-content {
            margin-top: 15px;
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
            color: #000 !important;
        }

        .edit-button:hover {
            background-color: #00bfff;
        }

        .create {
            background-color: #B68D40;
            color: #ffffff !important;
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
            color: #c3b2b2;
        }
    </style>
</head>

<body>
            @include('layout.nav')
            <br>
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

                                    <form id="delete-form-{{ $story->id }}"
                                        action="{{ route('stories.destroy', $story->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger fw-semibold" data-bs-toggle="modal"
                                            data-bs-target="#confirmDeleteModal" data-story-id="{{ $story->id }}">
                                            Delete
                                        </button>
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
    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-dark text-light border border-2 border-danger">
                <div class="modal-header">
                    <h5 class="modal-title text-danger" id="confirmDeleteModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this story? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Yes, Delete</button>
                </div>
            </div>
        </div>
    </div>
    <br>

    <script>
        let currentStoryId = null;

        const confirmModal = document.getElementById('confirmDeleteModal');
        const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');

        confirmModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            currentStoryId = button.getAttribute('data-story-id');
        });

        confirmDeleteBtn.addEventListener('click', function() {
            if (currentStoryId) {
                const form = document.getElementById(`delete-form-${currentStoryId}`);
                if (form) form.submit();
            }
        });
    </script>

</body>

</html>
