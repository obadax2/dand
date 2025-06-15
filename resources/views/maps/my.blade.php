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
        <h1 class="mb-4">My Characters</h1>

        @forelse($stories as $story)
            @if ($story->map->isNotEmpty())
                <h2 class="mt-4 mb-4">{{ $story->title }}</h2>

                <div class="row g-3 mb-4">
                    @foreach ($story->map as $maps)
                        <div class="col-12 col-sm-6 col-md-4">
                            <div class="card mb-4 shadow-sm">
                                @php
                                    $imageId = $maps->id;
                                @endphp

                                @if (Storage::disk('public')->exists("maps_images/{$imageId}.png"))
                                    <img src="{{ asset("storage/maps_images/{$imageId}.png") }}"
                                        class="card-img-top" alt="{{ $maps->title }}">
                                @else
                                    <img src="{{ asset('images/default-character.png') }}" class="card-img-top"
                                        alt="No Image">
                                @endif

                                <div class="card-body">
                                    <h5 class="card-title">{{ $maps->title }}</h5>
                                    <p class="card-text">{{ $maps->description }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        @empty
            <div class="d-flex flex-column justify-content-center align-items-center text-center min-vh-100"
                style="margin-top: -30vh;">
                <p class=" fs-3 fw-bold mb-4 text-black">
                    You haven't created any characters yet.</p>
            </div>
        @endforelse

    </div>
</body>

</html>
