<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.lineicons.com/3.0/lineicons.css" rel="stylesheet">

</head>
<style>
    table{
        color: #BDAEC6;
            background-color: rgba(25, 23, 75, 0.5);
    /* semi-transparent */
    backdrop-filter: blur(1px);
    }
    /* From Uiverse.io by adamgiebl */
    .ban ,.unban {
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

    .ban:hover ,.unban:hover {
        color: white;
        box-shadow: inset 0 -100px 0 0 #725AC1;
    }

    .ban:active ,.unban:active {
        transform: scale(0.9);
    }
</style>

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
                                        <form action="{{ route('admin.users.ban', $user->id) }}" method="POST"
                                            style="display:inline;">
                                            @csrf
                                            <button type="submit" class="ban">Ban</button>
                                        </form>
                                        <form action="{{ route('admin.users.unban', $user->id) }}" method="POST"
                                            style="display:inline;">
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
                                <th>Content</th>
                                <th>Date Submitted</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tickets as $ticket)
                                <tr>
                                    <td>{{ $ticket->id }}</td>
                                    <td>{{ $ticket->user->username ?? 'N/A' }}</td>
                                    <td>{{ $ticket->content }}</td>
                                    <td>{{ $ticket->created_at->format('Y-m-d H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.4.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
