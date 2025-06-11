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
    <style>


        h1 {
            color: #000000;
        }

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
            color: #000000;
            background: transparent;
            cursor: pointer;
            transition: ease-out 0.5s;
            border: 2px solid #000000;
            border-radius: 10px;
            box-shadow: inset 0 0 0 0 #000000;
        }

        h2 {
            color: #000000;
            margin-bottom: 20px;
            /* space between heading and table */
        }

        .table-wrapper {

            margin-bottom: 40px;
        }

        table {
            text-align: center;
        }

        .ban:hover,
        .unban:hover {
            color: white;
            box-shadow: inset 0 -100px 0 0 #000000;
        }

        .ban:active,
        .unban:active {
            transform: scale(0.9);
        }
    </style>
</head>

<body>
    <div>
        <br>
        @include('layout.nav')
        <br>
        <div class="container">
            <h1>Admin Dashboard</h1>

            <h2>Users</h2>

            @if ($users->isEmpty())
                <div class="d-flex justify-content-center align-items-center">
                    <p style="color: #000;font-style: italic;">No users found ❌</p>
                </div>
            @else
                <div class="table-wrapper">

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
                </div>

            @endif

            <br>
            <<h2>Tickets</h2>
<div class="table-wrapper">
    @if ($tickets->isEmpty())
        <p style="color: #000000">No tickets found.</p>
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
                            <button type="button" class="btn btn-dark" data-bs-toggle="modal"
                                data-bs-target="#conversationModal-{{ $ticket->id }}">
                                View Conversation
                            </button>
                        </td>
                        <td>{{ $ticket->updated_at->format('Y-m-d H:i') }}</td>
                        <td>
                            @php $lastMessage = $ticket->messages->first(); @endphp
                            @if (!$lastMessage || $lastMessage->sender === 'user')
                                <button type="button" class="ban" data-bs-toggle="modal"
                                    data-bs-target="#replyModal-{{ $ticket->id }}">
                                    Reply
                                </button>
                                <button class="ban" onclick="openReplyModal({{ $ticket->id }})">Send
                                    Reply</button>
                            @else
                                Last reply sent.
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
    </div> <br>

    {{-- Reply Modals --}}
    @foreach ($tickets as $ticket)
        <div class="modal fade" id="replyModal-{{ $ticket->id }}" tabindex="-1"
            aria-labelledby="replyModalLabel-{{ $ticket->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="background-color: #000; color: #ffffff;">
                    <div class="modal-header border-0">
                        <h5 class="modal-title" id="replyModalLabel-{{ $ticket->id }}">
                            Reply to {{ $ticket->user->username ?? 'User' }}</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <form action="{{ route('tickets.reply', $ticket->id) }}" method="POST" class="reply-form"
                        data-ticket-id="{{ $ticket->id }}">
                        @csrf
                        <div class="modal-body">
                            <textarea name="reply" class="form-control" rows="4" placeholder="Type your reply here..." required></textarea>
                        </div>
                        <div class="modal-footer border-0">
                            <button type="submit" class="btn btn-light">Send Reply</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    {{-- Conversation Modals --}}
    @foreach ($tickets as $ticket)
        <div class="modal fade" id="conversationModal-{{ $ticket->id }}" tabindex="-1"
            aria-labelledby="conversationModalLabel-{{ $ticket->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content" style="background-color: #1f1f2e; color: #ffffff; border-radius: 12px;">
                    <div class="modal-header border-0">
                        <h5 class="modal-title" id="conversationModalLabel-{{ $ticket->id }}">
                            Conversation with {{ $ticket->user->username ?? 'User' }}</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body" style="max-height: 400px; overflow-y: auto; padding: 1rem;">
                        @forelse ($ticket->messages as $message)
                            <div class="p-3 mb-3 rounded"
                                style="background-color: {{ $message->sender === 'admin' ? '#343454' : '#2a2a3c' }};">
                                <div class="d-flex justify-content-between">
                                    <strong>{{ ucfirst($message->sender) }}</strong>
                                    <small class="text-light">{{ $message->created_at->format('d M Y, H:i') }}</small>
                                </div>
                                <p class="mt-2 mb-0">{{ $message->message }}</p>
                            </div>
                        @empty
                            <p>No conversation found.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    <script>
        document.querySelectorAll('.reply-form').forEach(form => {
            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                const ticketId = form.dataset.ticketId;
                const modalId = `#replyModal-${ticketId}`;
                const modalElement = document.querySelector(modalId);
                const modalInstance = bootstrap.Modal.getInstance(modalElement);

                const formData = new FormData(form);
                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value,
                            'Accept': 'application/json'
                        },
                        body: formData
                    });

                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }

                    const data = await response.json();

                    if (data.success) {
                        modalInstance.hide();
                        document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
                        document.body.classList.remove('modal-open');

                        setTimeout(() => {
                            location.reload();
                        }, 300);
                    } else {
                        alert(data.message || 'Failed to send reply.');
                    }
                } catch (error) {
                    console.error(error);
                }
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
