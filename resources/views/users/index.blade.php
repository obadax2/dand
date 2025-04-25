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

<body>
    <div class="container">
        <h1>User Management</h1>
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('users.index') }}" method="GET" class="mb-4">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Search Users..."
                    value="{{ request()->query('search') }}">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="submit">Search</button>
                </div>
            </div>
        </form>

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
                                    <input value="user" name="role" type="radio" id="role-user" {{ $user->role === 'user' ? 'checked' : '' }}>
                                    <label for="role-user">User</label>

                                    <input value="hr" name="role" type="radio" id="role-hr" {{ $user->role === 'hr' ? 'checked' : '' }}>
                                    <label for="role-hr">HR</label>

                                    <input value="admin" name="role" type="radio" id="role-admin" {{ $user->role === 'admin' ? 'checked' : '' }}>
                                    <label for="role-admin">Admin</label>

                                    <button type="submit" class="btn btn-primary btn-sm">Update Role</button>

                                </div>

                            </form>

                            @if ($user->role === 'admin')
                                <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger"
                                        onclick="return confirm('Are you sure you want to delete this admin? This action cannot be undone.');">Delete
                                        Admin</button>
                                </form>
                            @endif
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
</body>

</html>
