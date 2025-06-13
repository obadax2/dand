<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>My Stories</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.lineicons.com/3.0/lineicons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <style>
        h1 {
            color: #205344;
            font-family: 'Raleway', sans-serif;
        }

        .card {
            position: relative;
            width: 220px;
            height: 320px;
            background: #16383B;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            border-radius: 15px;
            cursor: pointer;
            overflow: hidden;
            padding: 20px;
            text-align: center;
            z-index: 1;
        }

        .card h3,
        .card p,
        .card a {
            z-index: 2;
            position: relative;
        }

        .card::before,
        .card::after {
            content: "";
            position: absolute;
            width: 20%;
            height: 20%;
            background-color: lightblue;
            transition: all 0.5s;
            z-index: 0;
        }

        .card::before {
            top: 0;
            right: 0;
            border-radius: 0 15px 0 100%;
        }

        .card::after {
            bottom: 0;
            left: 0;
            border-radius: 0 100% 0 15px;
        }

        .card:hover::before,
        .card:hover::after {
            width: 100%;
            height: 100%;
            border-radius: 15px;
        }

        .card:hover::after {
            content: "";
        }
    </style>

</head>

<body>
    @include('layout.nav')
    <div class="container mt-2">
        <h1 class="mb-4">My Storeis</h1>

        @forelse($stories as $story)
            @if ($loop->first)
                <div class="row g-4">
            @endif

            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                <div class="card shadow p-3 mb-4">
                    <h3>{{ $story->title }}</h3>
                    <p>{{ $story->description }}</p>
                    <a href="{{ route('stories.show', $story->id) }}" class="btn btn-dark">View Story</a>
                </div>
            </div>

            @if ($loop->last)
    </div>
    @endif
@empty
    <div class="d-flex flex-column justify-content-center align-items-center text-center min-vh-100"
        style="margin-top: -30vh;">
        <p class=" fs-3 fw-bold mb-4 text-black">
           You haven't created any stories yet.</p>
        <a href="{{ route('stories.create') }}" class="btn create text-white" style="background: #1f2d31">
            Create a new story <i class="fas fa-reply ms-2"></i>
        </a>
    </div>
    @endforelse
    </div>
</body>


</html>
