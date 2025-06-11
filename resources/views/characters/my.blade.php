
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Characters</title>
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
        .card-title {
            color: #05EEFF;
        }
        .card-text {
            color: #ddd;
        }
        h1, h2 {
            color: #ffffff;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">My Characters</h1>

        @forelse($stories as $story)
            @if($story->characters->isNotEmpty())
                <h2 class="mt-4">{{ $story->title }}</h2>
                <div class="row">
                    @foreach($story->characters as $character)
                        <div class="col-md-4">
                            <div class="card mb-4 shadow-sm">
                               @php
    $imageId = $character->original_character_id ?? $character->id;
@endphp

@if(Storage::disk('public')->exists("character_images/{$imageId}.png"))
    <img src="{{ asset("storage/character_images/{$imageId}.png") }}" class="card-img-top" alt="{{ $character->name }}">
@else
    <img src="{{ asset('images/default-character.png') }}" class="card-img-top" alt="No Image">
@endif
                                <div class="card-body">
                                    <h5 class="card-title">{{ $character->name }}</h5>
                                    <p class="card-text">{{ $character->description }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        @empty
            <p>You haven't created any characters yet.</p>
        @endforelse
    </div>
</body>
</html>
