<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Stories</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #1e1e2f;
            color: #fff;
        }
        .card {
            background-color: #2e2e3e;
            border: none;
        }
        .card h3 {
            color: #05EEFF;
        }
        .card p {
            color: #ccc;
        }
        .btn-primary {
            background-color: #05EEFF;
            border: none;
        }
        .btn-primary:hover {
            background-color: #03c9d9;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">My Stories</h1>

        @forelse($stories as $story)
            <div class="card mb-3 shadow p-3">
                <h3>{{ $story->title }}</h3>
                <p>{{ $story->description }}</p>
               <a href="{{ route('stories.show', $story->id) }}" class="btn btn-primary">View Story</a>
            </div>
        @empty
            <p>You haven't created any stories yet.</p>
        @endforelse
    </div>
</body>
</html>
