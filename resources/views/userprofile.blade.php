<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>User Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <link href="https://cdn.lineicons.com/3.0/lineicons.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('style.css') }}" />

    <style>

        a {
            color: #05EEFF;
            text-decoration: none;
            display: inline-block;
        }

        h1,
        h3 {
            width: 100%;
            margin-bottom: 20px;
        }

        h3 {
            margin-top: 20px;
        }
        h3{
            color: #000
        }

        form {
            margin-bottom: 20px;
        }

        img {
            border-radius: 50%;
            width: 100px;
            height: 100px;
            object-fit: cover;
        }

        /* Profile picture wrapper */
        .profile-wrapper-with-name {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .profile-wrapper {
            position: relative;
            cursor: pointer;
        }

        .edit-icon {
            position: absolute;
            bottom: 0;
            right: 0;
            background: #05eeffcc;
            padding: 5px;
            border-radius: 50%;
            font-size: 18px;
            color: #06043e;
            transition: background 0.3s;
        }

        .edit-icon:hover {
            background: #b6a7c0cc;
        }

        .hidden-file-input {
            position: absolute;
            width: 1px;
            height: 1px;
            opacity: 0;
            overflow: hidden;
            pointer-events: none;
        }

        input[type="password"],
        input[type="file"] {
            width: 250px;
            padding: 6px 8px;
            margin: 6px 0 12px;
            border: 1px solid black;
            border-radius: 4px;
            background-color: #ffffff;
            color: #fff;
            outline: 1px solid transparent;
            transition: all 0.3s ease;
        }

        input[type="password"]:focus,
        input[type="file"]:focus {
            transform: scale(1.05);
            outline: 1px solid #000000;
        }

        /* Buttons */
        .userButton {
            background-color: #05eeff;
            border: none;
            color: #06043e;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }

        .userButton:hover,
        .userButton:focus {
            background-color: #b6a7c0;
            color: #06043e;
        }

        /* Friend request container */
        .friend-request {
            background: rgba(25, 23, 75, 0.7);
            border-radius: 8px;
            padding: 10px 15px;
            margin-bottom: 10px;
        }

        /* Statistics box */
        .status-box {
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            color: #eee;
        }

        /* Layout columns */
        .usercontainer {
            display: flex;
            gap: 40px;
            flex-wrap: wrap;
        }

        .column {
            flex: 1;
            min-width: 280px;
            max-width: 450px;
        }

        /* Alert fadeout */
        .custom-alert {
            position: fixed;
            top: 15px;
            right: 15px;
            z-index: 2000;
            opacity: 1;
            transition: opacity 0.5s ease;
        }

        @media (max-width: 768px) {
            .usercontainer {
                flex-direction: column;
            }

            input[type="password"],
            input[type="file"] {
                width: 100%;
            }
        }
    </style>
</head>

<body>
    @if (session('success'))
        <div class="alert alert-success custom-alert" id="successAlert">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger custom-alert" id="errorAlert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

        <div class="container">
            <br>
            @include('layout.nav')
            <main class="container my-4">
                <div class="usercontainer">
                    <!-- Column 1: Profile Picture + Stats + Friend Requests -->
                    <div class="column">
                        <form action="{{ route('user.updateProfile') }}" method="POST" enctype="multipart/form-data"
                            id="profileForm">
                            @csrf
                            <div class="profile-wrapper-with-name" role="group"
                                aria-label="Profile Picture and User Info">
                                <div class="profile-wrapper">
                                    <img id="profileImage"
                                        src="{{ $user->profile_picture ? asset('storage/' . str_replace('public/', '', $user->profile_picture)) : 'https://via.placeholder.com/150' }}"
                                        alt="Profile picture of {{ Auth::user()->name }}" />
                                    <label for="profilePictureInput" class="edit-icon" title="Change Profile Picture"
                                        tabindex="0">
                                        <i class="lni lni-pencil"></i>
                                    </label>
                                    <input type="file" name="profile_picture" accept="image/*"
                                        id="profilePictureInput" class="hidden-file-input"
                                        onchange="document.getElementById('profileForm').submit();" />
                                </div>
                                <div class="profile-info-text">
                                    <h3 class="profile-username">Name: {{ Auth::user()->name }}</h3>
                                    <p style="color: #000" class="profile-role">Role: {{ Auth::user()->role }}</p>
                                </div>
                            </div>
                        </form>

                        <h3>Statistics</h3>
                        <div class="status-box">
                            <p>Followers: {{ $followersCount }}</p>
                            <p>Friends: {{ $friendsCount }}</p>
                        </div>

                        <h3>Friend Requests</h3>
                        @if ($friendRequests->isEmpty())
                            <p style="color: #000">No new friend requests.</p>
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

                    <!-- Column 2: Change Password -->
                    <div class="column">
                        <h3>Change Password</h3>
                        <form method="POST" action="{{ route('user.changePassword') }}" novalidate>
                            @csrf
                            <label for="current_password">Current Password</label><br />
                            <input type="password" id="current_password" name="current_password" required
                                autocomplete="current-password" /><br />

                            <label for="new_password">New Password</label><br />
                            <input type="password" id="new_password" name="new_password" required
                                autocomplete="new-password" /><br />

                            <label for="new_password_confirmation">Confirm New Password</label><br />
                            <input type="password" id="new_password_confirmation" name="new_password_confirmation"
                                required autocomplete="new-password" /><br />

                            <button type="submit" class="btn btn-dark">Change Password</button>
                        </form>
                    </div>
                </div>
            </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const successAlert = document.getElementById('successAlert');
            if (successAlert) {
                setTimeout(() => {
                    successAlert.style.opacity = '0';
                    setTimeout(() => successAlert.remove(), 500);
                }, 3000);
            }
            const errorAlert = document.getElementById('errorAlert');
            if (errorAlert) {
                setTimeout(() => {
                    errorAlert.style.opacity = '0';
                    setTimeout(() => errorAlert.remove(), 500);
                }, 5000);
            }
        });
    </script>
</body>

</html>
