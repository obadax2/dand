<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Select a Story</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #eee;
            padding: 2rem;
            min-height: 100vh;
            background-color: #000;
        }

        h2 {
            color: #fff;
            margin-bottom: 2rem;
            font-weight: 700;
            text-align: center;
            letter-spacing: 1px;
        }

        .ticket {
            display: flex;
            flex-direction: column;
            max-width: 700px;
            margin: 0 auto 3rem;
            padding: 2rem;
            border-radius: 15px;
            border: 1px solid #555;
            background: #111;
        }

        form select {
            width: 100%;
            padding: 0.75rem 1rem;
            background-color: #222;
            color: #eee;
            border: 2px solid #555;
            border-radius: 12px;
            font-size: 1.1rem;
            margin-bottom: 1rem;
        }

        form select:focus {
            border-color: #ccc;
            outline: none;
            box-shadow: 0 0 8px #ccc88;
        }

        form button {
            margin-top: 1rem;
            padding: 0.7rem 2rem;
            background-color: #eee;
            color: #000;
            font-weight: 700;
            font-size: 1.1rem;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            display: block;
            width: fit-content;
        }

        form button:hover {
            background-color: #ddd;
        }
    </style>
</head>

<body>
    <h2>Select a Story</h2>

    <div class="ticket">
        <form action="{{ route('chat.start') }}" method="POST">
            @csrf
            <select name="story_id" required>
                <option value="" disabled selected>Select a story</option>
                @foreach ($stories as $story)
                    <option value="{{ $story->id }}">{{ $story->title }}</option>
                @endforeach
            </select>
            <button type="submit">Start Chat</button>
        </form>
    </div>
</body>

</html>
