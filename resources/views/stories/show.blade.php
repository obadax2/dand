<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $story->title }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #1e1e2f;
            color: #fff;
        }
        .card {
            background-color: #2e2e3e;
            border: none;
            padding: 20px;
            margin-top: 40px;
        }
        h1 {
            color: #05EEFF;
        }
        p {
            color: #ccc;
            font-size: 18px;
        }
        .btn-back {
            background-color: #05EEFF;
            border: none;
            color: #000;
            margin-top: 20px;
        }
        .btn-back:hover {
            background-color: #03c9d9;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="{{ route('stories.my') }}" class="btn btn-back mt-4">← Back to My Stories</a>
        <div class="card">
            <h1>{{ $story->title }}</h1>
            <p>{{ $story->content }}</p>
        </div>
    </div>
</body>
</html>
