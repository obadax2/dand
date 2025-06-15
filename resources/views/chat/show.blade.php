<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Chat about: {{ $story->title }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        form[style] button.btn-link:hover {
            transform: scale(1.5);
            transition: transform 0.5s ease-in-out;

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
            transform: scale(1.05);

        }

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

        .container-chat {
            max-width: 700px;
            margin: 0 auto;
            background: #111;
            padding: 2rem;
            border-radius: 15px;
            border: 1px solid #555;
            display: flex;
            flex-direction: column;
            height: 80vh;
        }

        .story-info {
            max-height: 200px;
            overflow-y: auto;
            padding-right: 10px;
            color: #ddd;
            margin-bottom: 1.5rem;
            font-size: 0.95rem;
        }

        .story-info::-webkit-scrollbar {
            width: 8px;
        }

        .story-info::-webkit-scrollbar-track {
            background: #222;
        }

        .story-info::-webkit-scrollbar-thumb {
            background: #555;
            border-radius: 4px;
        }


        .story-info ul {
            padding-left: 1.25rem;
        }

        #messages-container {
            flex: 1 1 auto;
            overflow-y: auto;
            padding-right: 0.5rem;
            margin-bottom: 1rem;
        }

        #messages-container::-webkit-scrollbar {
            width: 8px;
        }

        #messages-container::-webkit-scrollbar-track {
            background: #222;
        }

        #messages-container::-webkit-scrollbar-thumb {
            background: #555;
            border-radius: 4px;
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

        .message.bot {
            background-color: #222;
            border-left: 6px solid #888;
            color: #fff;
        }

        .message.user {
            background-color: #1a1a1a;
            border-left: 6px solid #aaa;
            color: #f5f5f5;
        }

        .empty-message {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
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

        /* Form styles */
        #chat-form {
            flex-shrink: 0;
            display: flex;
            gap: 0.5rem;
        }

        #chat-form input[type="text"] {
            flex-grow: 1;
            padding: 0.85rem 1rem;
            font-size: 1.1rem;
            font-family: inherit;
            background-color: #222;
            color: #eee;
            border: 2px solid #555;
            border-radius: 12px;
            outline: none;
            transition: border-color 0.3s ease;
        }

        #chat-form input[type="text"]::placeholder {
            color: #777;
            font-style: italic;
        }

        #chat-form input[type="text"]:focus {
            border-color: #ccc;
            box-shadow: 0 0 8px #ccc88;
        }

        #chat-form button {
            padding: 0.7rem 2rem;
            background-color: #eee;
            color: #000;
            font-weight: 700;
            font-size: 1.1rem;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            white-space: nowrap;
        }

        .container-chat {
            position: relative;
            max-width: 700px;
            margin: 0 auto;
            background: #111;
            padding: 2rem;
            border-radius: 15px;
            border: 1px solid #555;
            display: flex;
            flex-direction: column;
            height: 80vh;
        }

        #chat-form button:hover {
            background-color: #ddd;
        }
    </style>
</head>

<body>
    <h2>Chat about: {{ $story->title }}</h2>

    <div class="container-chat">
        <form action="{{ route('chat.clear', $conversation) }}" method="POST"
            onsubmit="return confirm('Are you sure you want to clear the chat?');"
            style="position: absolute; top: 0rem; right: 1rem;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-link text-danger p-0 m-0" title="Clean Chat">
                <i class="fas fa-broom fa-lg"></i>
            </button>
        </form>

        <div class="story-info">
            <h4>Characters (count: {{ $story->characters->count() }})</h4>
            @if ($story->characters->isEmpty())
                <p>No characters found.</p>
            @endif
            <ul>
                @foreach ($story->characters as $char)
                    <li>{{ $char->name }}: {{ $char->description }}</li>
                @endforeach
            </ul>
            @if ($story->map)
                <p><strong>Map Title:</strong> {{ $story->map->title }}</p>
                <p><strong>Map Description:</strong> {{ $story->map->description }}</p>
            @endif
        </div>

        <div id="messages-container"
            style="flex: 1 1 auto; overflow-y: auto; padding-right: 0.5rem; margin-bottom: 1rem;">
            @if ($messages->isEmpty())
                <div class="empty-message">
                    <a href="#" id="start-chat-link"
                        style="color: #888; font-style: italic; font-size: 2rem; cursor: pointer;">
                        Start chat now
                    </a>
                </div>
            @else
                @include('chat.partials.messages', ['messages' => $messages])
            @endif
        </div>

        <div id="loading-spinner" style="display: none; text-align: center; margin-bottom: 1rem;">
            <div class="spinner-border text-light" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>


        <form id="chat-form" action="{{ route('chat.send', $conversation) }}" method="POST">
            @csrf
            <input type="text" name="message" required autocomplete="off" placeholder="Type your message...">
            <button type="submit">Send</button>
        </form>
    </div>
    <a href="{{ route('home') }}" style="margin-top: 2rem; display: block;">← Back to Home Page</a>

 <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(function() {
        // Scroll to bottom on initial page load
        $('#messages-container').scrollTop($('#messages-container')[0].scrollHeight);

        // Focus message input when clicking "Start chat now"
        $('#start-chat-link').on('click', function(e) {
            e.preventDefault();
            $('input[name="message"]').focus();
        });

        // AJAX send message handler with loading spinner
        $('#chat-form').submit(function (e) {
            e.preventDefault();
            let form = $(this);

            $('#loading-spinner').show();

            $.post(form.attr('action'), form.serialize())
                .done(function (response) {
                    if (response.messages_html) {
                        $('#messages-container').html(response.messages_html);
                        $('#messages-container').scrollTop($('#messages-container')[0].scrollHeight);
                    }
                    form.find('input[name=message]').val('').focus();
                })
                .fail(function () {
                    alert('Failed to send message.');
                })
                .always(function () {
                    $('#loading-spinner').hide();
                });
        });
    });
</script>


</body>

</html>
