<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Ticket Details</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 700px; margin: 2rem auto; padding: 1rem; background: #f9f9f9; }
        .ticket { background: white; padding: 1rem; border-radius: 5px; box-shadow: 0 0 5px #ccc; }
        .message {
            margin-bottom: 1rem; 
            padding: 0.5rem; 
            border-radius: 5px; 
        }
        .message.admin {
            background-color: #e0f7fa;
        }
        .message.user {
            background-color: #f1f8e9;
        }
        textarea {
            width: 100%;
            padding: 0.5rem;
            font-size: 1rem;
            box-sizing: border-box;
            border-radius: 4px;
            border: 1px solid #ccc;
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
    </style>
</head>
<body>
    <h2>Ticket Details</h2>
    <div class="ticket">
        <p><strong>User:</strong> {{ $ticket->username }}</p>

        @foreach($ticket->messages as $message)
            <div class="message {{ $message->sender }}">
                <strong>{{ ucfirst($message->sender) }}:</strong>
                <p>{{ $message->message }}</p>
                <small>{{ $message->created_at->format('d M Y, H:i') }}</small>
            </div>
        @endforeach

        <form action="{{ route('tickets.userReply', $ticket->id) }}" method="POST">
            @csrf
            <textarea name="message" rows="3" required placeholder="Write your reply here..."></textarea>
            <button type="submit">Send Reply</button>
        </form>
    </div>

    <a href="{{ route('tickets.index') }}">Back to all tickets</a>
</body>
</html>
