<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="{{ asset('style.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <link href="https://cdn.lineicons.com/3.0/lineicons.css" rel="stylesheet" />
    <style>
        table {
            color: #bdaec6;
            background-color: rgba(25, 23, 75, 0.5);
            backdrop-filter: blur(1px);
        }

        .ban, .unban {
            position: relative;
            display: inline-block;
            margin: 15px;
            padding: 15px 15px;
            text-align: center;
            font-size: 12px;
            letter-spacing: 1px;
            text-decoration: none;
            color: #bdaec6;
            background: transparent;
            cursor: pointer;
            transition: ease-out 0.5s;
            border: 2px solid #725ac1;
            border-radius: 10px;
            box-shadow: inset 0 0 0 0 #725ac1;
        }

        .ban:hover, .unban:hover {
            color: white;
            box-shadow: inset 0 -100px 0 0 #725ac1;
        }

        .ban:active, .unban:active {
            transform: scale(0.9);
        }

        textarea {
            width: 100%;
            margin-top: 0.5rem;
            resize: vertical;
        }
    </style>
</head>
<body>
    <div class="hero-section">
        <div>
            @include('layout.nav')
            <div class="container">
                <h1>Admin Dashboard</h1>

                <h2>Users</h2>
                @if ($users->isEmpty())
                    <p>No users found.</p>
                @else
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->username }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <form action="{{ route('admin.users.ban', $user->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="ban">Ban</button>
                                        </form>
                                        <form action="{{ route('admin.users.unban', $user->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="unban">Unban</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif

                <h2>Tickets</h2>
                @if ($tickets->isEmpty())
                    <p>No tickets found.</p>
                @else
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Ticket ID</th>
                                <th>Username</th>
                                <th>Conversation</th>
                                <th>Last Updated</th>
                                <th>Reply</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tickets as $ticket)
                                <tr>
                                    <td>{{ $ticket->id }}</td>
                                    <td>{{ $ticket->user->username ?? 'N/A' }}</td>
                                    <td>
                                        @foreach ($ticket->messages as $message)
                                            <div style="margin-bottom: 1rem; padding: 0.5rem; border-radius: 5px; background-color: {{ $message->sender === 'admin' ? '#e0f7fa' : '#f1f8e9' }}">
                                                <strong>{{ ucfirst($message->sender) }}:</strong>
                                                <p>{{ $message->message }}</p>
                                                <small>{{ $message->created_at->format('d M Y, H:i') }}</small>
                                            </div>
                                        @endforeach
                                    </td>
                                    <td>{{ $ticket->updated_at->format('Y-m-d H:i') }}</td>
                                    <td>
                                        @php
                                            $lastMessage = $ticket->messages->first();
                                        @endphp

                                        @if (!$lastMessage || $lastMessage->sender === 'user')
                                            <div id="reply-container-{{ $ticket->id }}">
                                                <form action="{{ route('tickets.reply', $ticket->id) }}" method="POST" class="reply-form" data-ticket-id="{{ $ticket->id }}">
                                                    @csrf
                                                    <textarea name="reply" rows="2" required></textarea>
                                                    <button type="submit" class="ban">Send Reply</button>
                                                </form>
                                            </div>
                                        @else
                                            <p style="color: lightgreen;"><strong>Last reply sent.</strong></p>
                                            <p>Waiting for user response.</p>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.reply-form').forEach(form => {
            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                const ticketId = form.dataset.ticketId;
                const formData = new FormData(form);
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value
                    },
                    body: formData
                });

                if (response.ok) {
                    document.getElementById(`reply-container-${ticketId}`).innerHTML = `
                        <p style="color: lightgreen;"><strong>Reply sent.</strong></p>
                        <p>Waiting for user response.</p>
                    `;
                } else {
                    alert('Failed to send reply.');
                }
            });
        });
    </script>
</body>
</html>
