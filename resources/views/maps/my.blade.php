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
        h2 {
            color: #000000;
            text-align: center;
            margin-bottom: 2rem;
        }

        .card {rgb(72, 5, 255)
            background-color: #122620;
            border: 1px solid #333;
            height: 100%;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-6px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
        }

        .card-title {
            color: #05EEFF;
        }

        .card-text, small {
            color: #000000;
        }

        .map-image img {
            width: 100%;
            border-radius: 10px;
            border: 1px solid #444;
        }

        .card-body {
            display: flex;
            flex-direction: row;
            gap: 1.5rem;
        }

        .map-text {
            flex: 1;
        }

        .map-image {
            flex-shrink: 0;
            width: 200px;
        }

        .map-container {
            max-width: 1100px;
            margin: 0 auto;
        }
    </style>
</head>

<body>
    @include('layout.nav')

    <div class="container mt-4 map-container">
        <h2>Your Maps and Stories</h2>
        <div class="row">
            @forelse ($map as $maps)
                <div class="col-md-6 mb-4">
                    <div class="card p-3 text-light h-100">
                        <div class="card-body">
                            <div class="map-text">
                                <h4 class="card-title">{{ $maps->title }}</h4>
                                <p style="color: #000000"><strong>Story:</strong> {{ $maps->story->title ?? 'No story linked' }}</p>
                                <p class="card-text">{{ $maps->description }}</p>
                                <small>Created at: {{ $maps->created_at }}</small>
                            </div>
                            @if ($maps->image)
                                <div class="map-image">
                                    <img src="{{ asset('storage/maps/' . basename($maps->image)) }}" alt="Map image">
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-muted">No maps found.</p>
            @endforelse
        </div>
    </div>
</body>

</html>
