<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Select Story to Generate Map</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.lineicons.com/3.0/lineicons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('style.css') }}">
</head>
<style>
select{
    max-width: 200px;
}
</style>
<body>
    @include('layout.nav')

    <div class="d-flex" style="padding: 20px;">
    <div class="container5 flex-grow-1">
        <div class="page-header mb-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap w-100">
                <h1 class="m-0">Select a Story to Generate a Map</h1>
            </div>
        </div>

        <form action="{{ route('maps.generate.post') }}" method="POST">
            @csrf
            <div class="mb-3 w-100" style="max-width: 500px;">
                <p for="story_id" class="form-label">Your Stories</p>
                <select class="form-select" name="story_id" id="story_id" required>
                    @foreach ($stories as $story)
                        <option value="{{ $story->id }}">{{ $story->title }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="ch">Generate Map</button>
        </form>
    </div>
</div>

</body>

</html>
