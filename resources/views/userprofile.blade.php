<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>User Profile</title>
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.lineicons.com/3.0/lineicons.css" rel="stylesheet">

    <link rel="stylesheet" href="style.css">
    <style>
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
    </style>
</head>

<body>
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


    <div class="hero-section">
        <div class="usercontainer1">
            @include('layout.nav')
            <div class="usercontainer">
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
                                    <input type="file" name="profile_picture" accept="image/*"
                                        id="profilePictureInput" class="hidden-file-input"
                                        onchange="document.getElementById('profileForm').submit();" />
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
                                        <button type="submit" class="userButton">Accept Friend Request</button>
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
                        <button type="submit" class="userButton">Change Password</button>
                    </form>


                </div>
            </div>

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
