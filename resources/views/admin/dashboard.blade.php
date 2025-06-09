<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.lineicons.com/3.0/lineicons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('style.css') }}">

</head>
<style>
    .custom-table {
        color: #BDAEC6;
        background-color: rgba(25, 23, 75, 0.5);
        /* semi-transparent */
        backdrop-filter: blur(1px);
    }

    /* From Uiverse.io by adamgiebl */
    .ban,
    .unban {
        position: relative;
        display: inline-block;
        margin: 15px;
        padding: 15px 15px;
        text-align: center;
        font-size: 12px;
        letter-spacing: 1px;
        text-decoration: none;
        color: #BDAEC6;
        background: transparent;
        cursor: pointer;
        transition: ease-out 0.5s;
        border: 2px solid #725AC1;
        border-radius: 10px;
        box-shadow: inset 0 0 0 0 #725AC1;
    }

    .ban:hover,
    .unban:hover {
        color: white;
        box-shadow: inset 0 -100px 0 0 #725AC1;
    }

    .ban:active,
    .unban:active {
        transform: scale(0.9);
    }
</style>

<body>
    <div class="hero-section">
        <div>
            <br>
            @include('layout.nav')
            <div class="container">
                <h1>Admin Dashboard</h1>

                <h2>Users</h2>
                @if ($users->isEmpty())
                    <p>No users found.</p>
                @else
                    <table class="custom-table">
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
                                        <form
                                            action="{{ $user->banned ? route('admin.users.unban', $user->id) : route('admin.users.ban', $user->id) }}"
                                            method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit"
                                                class="btn {{ $user->banned ? 'btn-success' : 'btn-danger' }}">
                                                {{ $user->banned ? 'Unban' : 'Ban' }}
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
                <br>
                <h2>Tickets</h2>
                @if ($tickets->isEmpty())
                    <p>No tickets found.</p>
                @else
                    <table class="custom-table">
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
                                            <div style="margin-bottom: 1rem; padding: 0.5rem; border-radius: 5px; ">
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
                                            <button class="ban" onclick="openReplyModal({{ $ticket->id }})">Send
                                                Reply</button>
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
    <div class="modal fade" id="replyModal" tabindex="-1" aria-labelledby="replyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-light"
                style="background-color: rgba(25, 23, 75, 0.5); backdrop-filter: blur(12px);">
                <div class="modal-header">
                    <h5 class="modal-title" id="replyModalLabel">Send Reply</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="replyForm" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="replyText" class="form-label">Reply Message:</label>
                            <textarea name="reply" id="replyText" rows="4" class="form-control" required></textarea>
                        </div>
                        <button type="submit" class="genButton">Send</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let currentTicketId = null;
        const replyModal = new bootstrap.Modal(document.getElementById('replyModal'));

        function openReplyModal(ticketId) {
            currentTicketId = ticketId;
            const form = document.getElementById('replyForm');
            form.action = `/admin/tickets/${ticketId}/reply`; // Adjust if using named routes
            document.getElementById('replyText').value = '';
            replyModal.show();
        }

        document.getElementById('replyForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const form = e.target;
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
                replyModal.hide();
                // Optional: update UI or refresh page
                alert('Reply sent successfully!');
            } else {
                alert('Failed to send reply.');
            }
        });
    </script>

</body>

</html>
