<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>User Profile</title>
</head>
<body>

<!-- Logout Button -->
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>
<!-- Logout link -->
<a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
    Logout
</a>

<h1>User Profile</h1>

@if(session('success'))
    <div style="color: green;">{{ session('success') }}</div>
@endif
@if($errors->any())
    <div style="color: red;">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- Profile Picture -->
<div>
    <h3>Profile Picture</h3>
    <img src="{{ $user->profile_picture ? asset('storage/' . str_replace('public/', '', $user->profile_picture)) : 'https://via.placeholder.com/150' }}" alt="Profile Picture" width="150" height="150" />
    <form action="{{ route('user.updateProfile') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="profile_picture" accept="image/*" />
        <button type="submit">Update Profile Picture</button>
    </form>
</div>

<!-- Change Password -->
<h3>Change Password</h3>
<form method="POST" action="{{ route('user.changePassword') }}">
    @csrf
    <label>Current Password</label>
    <input type="password" name="current_password" required /><br>
    <label>New Password</label>
    <input type="password" name="new_password" required /><br>
    <label>Confirm New Password</label>
    <input type="password" name="new_password_confirmation" required /><br>
    <button type="submit">Change Password</button>
</form>

<!-- Friend Requests -->
<h3>Friend Requests</h3>
@if($friendRequests->isEmpty())
    <p>No new friend requests.</p>
@else
   @foreach($friendRequests as $req)
    <div style="border: 1px solid #ccc; padding: 10px; margin-bottom: 10px;">
        <p>{{ $req->name }} wants to be your friend.</p>
        @if($req->pivot->status == 'pending')
            <!-- Show accept button only if request is pending -->
            <form action="{{ route('friend.accept', $req->id) }}" method="POST">
                @csrf
                <button type="submit">Accept Friend Request</button>
            </form>
        @elseif($req->pivot->status == 'accepted')
            <p>You are already friends.</p>
        @endif
    </div>
@endforeach
@endif
<!-- Stats: Followers and Friends -->
<h3>Statistics</h3>
<p>Followers: {{ $followersCount }}</p>
<p>Friends: {{ $friendsCount }}</p>

</body>
</html>