<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Upload Generated Map</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-light">
<div class="container py-5">
    <h2 class="mb-4 text-info">Upload Generated Map Image</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('maps.upload.image') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="story_id" class="form-label">Select Story</label>
            <select class="form-select" name="story_id" required>
                @foreach($stories as $story)
                    <option value="{{ $story->id }}">{{ $story->title }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="map_image" class="form-label">Map Image (PNG, JPG)</label>
            <input type="file" class="form-control" name="map_image" required />
        </div>

        <button type="submit" class="btn btn-info">Upload Map</button>
    </form>
</div>
</body>
</html>
