<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>All Tickets</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 700px;
            margin: 2rem auto;
            padding: 0 1rem;
            background: #f9f9f9;
        }
        .ticket {
            border: 1px solid #ccc;
            padding: 1rem;
            margin-bottom: 1rem;
            background: white;
            border-radius: 4px;
        }
        textarea {
            width: 100%;
            padding: 0.5rem;
            font-size: 1rem;
            box-sizing: border-box;
        }
        button {
            margin-top: 0.5rem;
            padding: 0.5rem 1rem;
            background: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 3px;
        }
        button:hover {
            background: #0056b3;
        }
        .reply {
            color: green;
            font-weight: bold;
            margin-top: 0.5rem;
        }
    </style>
</head>
<body>
    <h2>All Tickets</h2>

    @foreach($tickets as $ticket)
        <div class="ticket">
            <p><strong>{{ $ticket->username }}</strong> said:</p>
            <p>{{ $ticket->content }}</p>

            @if($ticket->reply)
                <p class="reply">Admin replied: {{ $ticket->reply }}</p>
            @else
                <form action="{{ route('tickets.reply', $ticket->id) }}" method="POST">
                    @csrf
                    <textarea name="reply" rows="2" required></textarea>
                    <button type="submit">Send Reply</button>
                </form>
            @endif
        </div>
    @endforeach
</body>
</html>
