<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
</head>

<style>
    html {
        background-color: #fff;
    }
</style>

<body>
    <nav>
        <ul>
            {{-- <li class="logo">
                <img src="site-logo.png" alt="Logo"> <!-- Placeholder logo -->
            </li> --}}
            <li>
                <a href="#" style="color: black;" onmouseover="this.style.color='#05EEFF'"
                    onmouseout="this.style.color='black'">Home</a>
            </li>
            <li>
                <a href="#" style="color: black;" onmouseover="this.style.color='#05EEFF'"
                    onmouseout="this.style.color='black'">About</a>
            </li>
            <li>
                <a href="#" style="color: black;" onmouseover="this.style.color='#05EEFF'"
                    onmouseout="this.style.color='black'">Services</a>
            </li>
            <li>
                <a href="#" style="color: black;" onmouseover="this.style.color='#05EEFF'"
                    onmouseout="this.style.color='black'">Contact</a>
            </li>
        </ul>
    </nav>
    <br>
    <div class="container">
        <h1>User Management</h1>

            <h2>Create a Poll</h2>
        <div class="d-flex justify-content-between align-items-start gap-4 mb-4 flex-wrap">
            <form action="{{ route('polls.store') }}" method="POST" class="flex-grow-1">
                @csrf
                <div class="mb-3">
                    <label for="pollTitle" class="form-label">Poll Title</label>
                    <input type="text" name="title" id="pollTitle" class="form-control" required>
                </div>
                <button class="btn" id="btnn">
                    <span class="btn-text-one">Create poll</span>
                    <span class="btn-text-two">Click</span>
                </button>
            </form>

            <!-- Search Form -->
            <form action="{{ route('users.index') }}" method="GET" class="flex-grow-1">
                <label for="search" class="form-label">Search Users</label>
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search Users..."
                        value="{{ request()->query('search') }}">
                    <button class="learn-more ms-2">
                        <span class="circle" aria-hidden="true">
                            <span class="icon arrow"></span>
                        </span>
                        <span class="button-text">Search here</span>
                    </button>
                </div>
            </form>

        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->username }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->role }}</td>
                        <td>
                            <form action="{{ route('users.role.update', $user) }}" method="POST" class="d-inline">
                                @csrf
                                <div id="checklist">
                                    <input value="user" name="role" type="radio"
                                        id="role-user-{{ $user->id }}"
                                        {{ $user->role === 'user' ? 'checked' : '' }}>
                                    <label for="role-user-{{ $user->id }}">User</label>

                                    <input value="hr" name="role" type="radio"
                                        id="role-hr-{{ $user->id }}" {{ $user->role === 'hr' ? 'checked' : '' }}>
                                    <label for="role-hr-{{ $user->id }}">HR</label>

                                    <input value="admin" name="role" type="radio"
                                        id="role-admin-{{ $user->id }}"
                                        {{ $user->role === 'admin' ? 'checked' : '' }}>
                                    <label for="role-admin-{{ $user->id }}">Admin</label>
                                    <button type="submit" class="btn btn-primary btn-sm">Update Role</button>
                                </div>
                            </form>

                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">No users found.</td>
                    </tr>
                @endforelse

            </tbody>
        </table>

    </div>

    @if (session('success'))
        <div class="alert alert-success custom-alert" id="successAlert">
            {{ session('success') }}
        </div>
    @endif
</body>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const alert = document.getElementById('successAlert');
        if (alert) {
            setTimeout(() => {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            }, 3000);
        }
    });
</script>

</html>
