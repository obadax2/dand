<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Ticket Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #eee;
            padding: 2rem;
            min-height: 100vh;
            background-color: #000; /* pure black */
        }

        h2 {
            color: #fff; /* white heading */
            margin-bottom: 2rem;
            font-weight: 700;
            text-align: center;
            letter-spacing: 1px;
        }

        .ticket {
            display: flex;
            flex-direction: column;
            max-height: 500px;
            max-width: 700px;
            margin: 0 auto 3rem;
            padding: 2rem;
            border-radius: 15px;
            border: 1px solid #555; /* subtle dark gray border */
            background: #111; /* very dark gray background */
        }

        .messages-wrapper {
            flex: 1 1 auto;
            overflow-y: auto;
            margin-bottom: 1rem;
            padding-right: 0.5rem;
            /* space for scrollbar */
        }

        .ticket form {
            flex-shrink: 0;
            background-color: transparent;
        }

        .ticket p strong {
            color: #ddd; /* light gray */
            font-weight: 600;
        }

        .message {
            padding: 1rem 1.25rem;
            border-radius: 12px;
            margin-bottom: 1.25rem;
            font-size: 1rem;
            line-height: 1.4;
            box-shadow: 0 2px 5px rgba(255, 255, 255, 0.05);
            word-wrap: break-word;
            transition: background-color 0.3s ease;
            color: #eee;
        }

        .message.admin {
            background-color: #222; /* very dark gray */
            border-left: 6px solid #888; /* medium gray accent */
            color: #fff;
        }

        .message.user {
            background-color: #1a1a1a; /* slightly lighter dark */
            border-left: 6px solid #aaa; /* lighter gray accent */
            color: #f5f5f5;
        }

        .message strong {
            display: block;
            margin-bottom: 0.5rem;
            color: #fff;
            font-weight: 700;
        }

        .message p {
            margin: 0;
        }

        small {
            display: block;
            margin-top: 0.5rem;
            font-size: 0.85rem;
            color: #999;
            font-style: italic;
        }

        form textarea {
            width: 100%;
            padding: 0.85rem 1rem;
            font-size: 1.1rem;
            font-family: inherit;
            background-color: #222;
            color: #eee;
            border: 2px solid #555;
            border-radius: 12px;
            resize: none;
            transition: border-color 0.3s ease;
            min-height: 50px;
            line-height: 1.4;
            max-height: 150px;
        }

        form textarea::placeholder {
            color: #777;
            font-style: italic;
        }

        form textarea:focus {
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

        a {
            display: block;
            max-width: 700px;
            margin: 0 auto;
            color: #eee;
            text-decoration: none;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-align: center;
            transition: color 0.3s ease;
        }

        a:hover {
            color: #bbb;
            text-decoration: underline;
        }

        /* Responsive tweaks */
        @media (max-width: 600px) {
            body {
                padding: 1rem;
            }

            .ticket {
                padding: 1.5rem;
                margin-bottom: 2rem;
            }

            form textarea {
                font-size: 1rem;
                min-height: 80px;
            }

            form button {
                font-size: 1rem;
                padding: 0.6rem 1.5rem;
            }
        }
    </style>
</head>

<body>
    <h2>Ticket Details</h2>

    <div class="ticket">
        <p><strong>User:</strong> {{ $ticket->username }}</p>

        <div class="messages-wrapper">
            @foreach ($ticket->messages as $message)
                <div class="message {{ $message->sender }}">
                    <strong>{{ ucfirst($message->sender) }}:</strong>
                    <p>{{ $message->message }}</p>
                    <small>{{ $message->created_at->format('d M Y, H:i') }}</small>
                </div>
            @endforeach
        </div>

        <form action="{{ route('tickets.userReply', $ticket->id) }}" method="POST">
            @csrf
            <textarea name="message" rows="1" required placeholder="Write your reply here..."></textarea>
            <button type="submit">Send Reply</button>
        </form>
    </div>

    <a href="{{ route('home') }}">← Back to Home Page</a>
</body>
<script>
    window.addEventListener('load', () => {
        const form = document.querySelector('form[action^="{{ route('tickets.userReply', $ticket->id) }}"]');
        if (form) {
            form.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
</script>

</html>
