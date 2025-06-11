<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Select Story to Generate Map</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-dark text-light">

<div class="container py-5">
    <h2 class="mb-4 text-info">Select a Story to Generate a Map</h2>

    <form action="{{ route('maps.generate.post') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="story_id" class="form-label">Your Stories</label>
            <select class="form-select" name="story_id" id="story_id" required>
                @foreach($stories as $story)
                    <option value="{{ $story->id }}">{{ $story->title }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-info">Generate Map</button>
    </form>
</div>

</body>
</html>
