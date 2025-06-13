<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.lineicons.com/3.0/lineicons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('style.css') }}">
</head>

<style>
    label {
        color: #000000;
    }

    .table-wrapper {
        display: flex;
        margin-bottom: 40px;
        /* optional spacing between sections */
    }

    h1 {
        color: #000000;
    }

    .bb {
        margin-top: 10px !important;
        /* or adjust the value as needed */
    }

    .container input[type="text"]{
        width: 250px;
        padding: 6px 8px;
        margin: 6px 0 12px;
        border: 1px solid black;
        border-radius: 4px;
        background-color: #ffffff;
        color: #000000;
        outline: 1px solid transparent;
        transition: all 0.3s ease;
    }
</style>

<body>
    @include('layout.nav')
    <div class="container">
        <br>
        <h1>User Management</h1>
        <div class="d-flex justify-content-start align-items-end mb-4 flex-wrap">
            <form action="{{ route('polls.store') }}" method="POST" class="me-3">
                @csrf
                <label>Create a poll</label>
                <div class="input-group">
                    <input type="text" class="bb" name="title" id="pollTitle" required placeholder="Enter poll content">
                    <button class="learn-more ms-2">
                        <span class="circle" aria-hidden="true">
                            <span class="icon arrow"></span>
                        </span>
                        <span class="button-text">Create</span>
                    </button>
                </div>
            </form>
                        <!-- Search Form -->

            <form action="{{ route('users.index') }}" method="GET">
                <label>Search Users</label>
                <div class="input-group">
                    <input type="text" class="bb" name="search" value="{{ request()->query('search') }}" placeholder="Enter user name">
                    <button class="learn-more ms-2">
                        <span class="circle" aria-hidden="true">
                            <span class="icon arrow"></span>
                        </span>
                        <span class="button-text">Search</span>
                    </button>
                </div>
            </form>

        </div>
        <div class="table-wrapper">
            <table class="custom-table">
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
                                            id="role-hr-{{ $user->id }}"
                                            {{ $user->role === 'hr' ? 'checked' : '' }}>
                                        <label for="role-hr-{{ $user->id }}">HR</label>

                                        <input value="admin" name="role" type="radio"
                                            id="role-admin-{{ $user->id }}"
                                            {{ $user->role === 'admin' ? 'checked' : '' }}>
                                        <label for="role-admin-{{ $user->id }}">Admin</label>
                                        <button type="submit" class="btn btn-dark btn-sm">Update
                                            Role</button>
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
    </div>
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
