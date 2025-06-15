<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>My Characters</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.lineicons.com/3.0/lineicons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <style>
        .card {
            background-color: #122620;
            border: none;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        .card-title {
            color: #05EEFF;
        }

        .card-text {
            color: #ddd;
        }

        h1 {
            color: #205344;
            font-family: 'Raleway', sans-serif;

        }

        h2 {
            color: #000000;
        }
    </style>
</head>

<body>
    @include('layout.nav')
    <div class="container mt-2">
        <h2>Your Maps and Stories</h2>

        @forelse ($map as $maps)
            <div class="card mb-3 p-3 text-light bg-dark border border-secondary">
                <h4>{{ $maps->title }}</h4>
                <p><strong>Story:</strong> {{ $maps->story->title }}</p>
                <p>{{ $maps->description }}</p>
                @if ($maps->image)
                    <img src="{{ asset($maps->image) }}" alt="Map image" style="max-width: 300px;">
                @endif
                <small>Created at: {{ $maps->created_at }}</small>
            </div>
        @empty
            <p>No maps found.</p>
        @endforelse


    </div>
</body>

</html>
