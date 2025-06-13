<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $story->title }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"  rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.lineicons.com/3.0/lineicons.css"  rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <style>
        .card {
            background-color: #16383b;
            border: none;
            padding: 20px;
        }

        h1 {
            color: #205344;
            font-family: 'Raleway', sans-serif;
        }

        p {
            color: #ccc;
            font-size: 18px;
        }

        .back-link {
            display: inline-block;
            margin-top: 10px;
            color: #000000;
            text-decoration: none;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: color 0.3s ease;
            padding: 5px 10px;
        }

        .back-link:hover {
            color: #bbb;
            text-decoration: underline;
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

<body class="pb-5">
    @include('layout.nav')
    <div class="container mb-5">
        <h1>{{ $story->title }}</h1>
                <a href="{{ route('stories.my') }}" class="back-link">← Back to My Stories</a>

        <div class="card">
            <p>{{ $story->content }}</p>
        </div>
                <a href="{{ route('stories.my') }}" class="back-link">← Back to My Stories</a>

    </div>

</body>
</html>
