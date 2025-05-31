<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>User Profile</title>
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #06043E;

        }

        .container {
            background-color: #19174B;
            padding: 30px;
            color: #ffffff;
            border-radius: 10px;
            max-width: 900px;
            margin: 40px auto;
            display: flex;
            flex-wrap: wrap;
            gap: 40px;
        }

        .column {
            flex: 1;
            min-width: 300px;
        }

        a {
            color: #05EEFF;
            text-decoration: none;
            display: inline-block;
        }

        h1 {
            width: 100%;
            margin-bottom: 20px;
        }

        h3 {
            margin-top: 20px;
            margin-bottom: 10px;
        }

        form {
            margin-bottom: 20px;
        }

        img {
            border-radius: 50%;
            width: 100px;
            height: 100px;
        }

        input[type="password"],
        input[type="file"] {
            width: 250px;
            padding: 6px;
            margin: 6px 0 12px;
            border: none;
            border-radius: 4px;
        }

        button {
            background-color: #05EEFF;
            border: none;
            padding: 8px 14px;
            color: #000;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 6px;
        }

        .friend-request,
        .status-box {
            background-color: #2a2860;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 12px;
            max-width: 300px;
        }

        .profile-wrapper-with-name {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .profile-wrapper {
            position: relative;
            width: 120px;
            height: 120px;
        }

        .profile-wrapper img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
        }

        .edit-icon {
            position: absolute;
            bottom: 6px;
            right: 6px;
            background-color: #05EEFF;
            border-radius: 50%;
            height: 26px;
            width: 26px;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            color: #000;
            font-size: 14px;
        }

        .edit-icon:hover {
            background-color: #03bfd4;
        }

        .profile-info-text {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .profile-username {
            margin: 0;
            font-size: 22px;
            user-select: none;
        }

        .profile-role {
            margin: 4px 0 0 0;
            font-size: 14px;
            user-select: none;
        }

        .hidden-file-input {
            display: none;
        }
    </style>
</head>

<body>
    @include('layout.nav')
    @if (session('success'))
        <div class="alert alert-success custom-alert" id="successAlert">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger custom-alert" id="successAlert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    <div class="container">
        <!-- Column 1 -->
        <div class="column">
            <div style="display: flex; align-items: center; gap: 20px; margin-bottom: 20px;">
                <form action="{{ route('user.updateProfile') }}" method="POST" enctype="multipart/form-data"
                    id="profileForm">
                    @csrf
                    <div class="profile-wrapper-with-name">
                        <div class="profile-wrapper">
                            <img id="profileImage"
                                src="{{ $user->profile_picture ? asset('storage/' . str_replace('public/', '', $user->profile_picture)) : 'https://via.placeholder.com/150' }}"
                                alt="Profile Picture" />
                            <label for="profilePictureInput" class="edit-icon">
                                <i class="lni lni-pencil"></i>
                            </label>
                            <input type="file" name="profile_picture" accept="image/*" id="profilePictureInput"
                                class="hidden-file-input" onchange="document.getElementById('profileForm').submit();" />
                        </div>
                        <div class="profile-info-text">
                            <h3 class="profile-username">Name: {{ Auth::user()->name }}</h3>
                            <p class="profile-role">Role: {{ Auth::user()->role }}</p>
                        </div>
                    </div>

                </form>

            </div>


            <h3>Statistics</h3>
            <div class="status-box">
                <p>Followers: {{ $followersCount }}</p>
                <p>Friends: {{ $friendsCount }}</p>
            </div>

            <h3>Friend Requests</h3>
            @if ($friendRequests->isEmpty())
                <p>No new friend requests.</p>
            @else
                @foreach ($friendRequests as $req)
                    <div class="friend-request">
                        <p>{{ $req->name }} wants to be your friend.</p>
                        @if ($req->pivot->status == 'pending')
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
        </div>

        <!-- Column 2 -->
        <div class="column">
            <h3>Change Password</h3>
            <form method="POST" action="{{ route('user.changePassword') }}">
                @csrf
                <label>Current Password</label><br>
                <input type="password" name="current_password" required /><br>
                <label>New Password</label><br>
                <input type="password" name="new_password" required /><br>
                <label>Confirm New Password</label><br>
                <input type="password" name="new_password_confirmation" required /><br>
                <button type="submit">Change Password</button>
            </form>


        </div>
    </div>
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
</body>

</html>
