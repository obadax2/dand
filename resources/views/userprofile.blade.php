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

        h3 {
            color: #000
        }

        form {
            margin-bottom: 20px;
        }

        .profile-wrapper img {
            border-radius: 50%;
            width: 120px;
            height: 120px;
            object-fit: cover;
        }

        .profile-wrapper {
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

        .delete-icon {
            position: absolute;
            top: 0;
            left: 0;
            background: rgba(255, 255, 255, 0.8);
            padding: 4px 6px;
            border-radius: 50%;
            font-size: 16px;
            color: #06043e;
            cursor: pointer;
            z-index: 10;
            transition: background 0.3s ease;
        }

        .delete-icon:hover {
            background: #ff6b6b;
            color: #fff;
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

        .friend-request {
            border-radius: 8px;
            padding: 10px 15px;
            margin-bottom: 10px;
        }

        .status-box {
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            color: #eee;
        }

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

        input[type="password"] {
            color: #000;
        }

        .profile-info-text {
            white-space: nowrap;
        }
    </style>
</head>

<body>
    @if (session('success'))
        <div class="alert alert-success custom-alert bg-custom-success" id="successAlert">
            {{ session('success') }}
        </div>
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

    @include('layout.nav')
    <div class="container">
        <main class="container my-4">
            <div class="usercontainer">
                <div class="column">
                    <form action="{{ route('user.updateProfile') }}" method="POST" enctype="multipart/form-data"
                        id="profileForm">
                        @csrf
                        <div class="profile-wrapper">
                            <img id="profileImage"
                                src="{{ $user->profile_picture
                                    ? asset('storage/' . str_replace('public/', '', $user->profile_picture))
                                    : asset('p.jpg') }}" />

                            @if ($user->profile_picture)
                                <button type="button" id="deleteProfilePicture" class="delete-icon"
                                    title="Remove Profile Picture">
                                    <i class="lni lni-trash-can"></i>
                                </button>
                            @endif

                            <label for="profilePictureInput" class="edit-icon" title="Change Profile Picture"
                                tabindex="0">
                                <i class="lni lni-pencil"></i>
                            </label>

                            <input type="file" name="profile_picture" accept="image/*" id="profilePictureInput"
                                class="hidden-file-input" onchange="document.getElementById('profileForm').submit();" />
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
                                        <button type="submit" class="btn btn-light">Accept</button>
                                    </form>
                                @elseif($req->pivot->status == 'accepted')
                                    <p>You are already friends.</p>
                                @endif
                            </div>
                        @endforeach
                    @endif
                </div>

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
                        <input type="password" id="new_password_confirmation" name="new_password_confirmation" required
                            autocomplete="new-password" /><br />

                        <button type="submit" class="btn btn-dark">Change Password</button>
                    </form>
                </div>
            </div>
        </main>
        <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content bg-dark text-light border border-2 border-danger">
                    <div class="modal-header">
                        <h5 class="modal-title text-danger" id="confirmDeleteModalLabel">Confirm Deletion</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete your profile picture? This action cannot be undone.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Yes, Delete</button>
                    </div>
                </div>
            </div>
        </div>


</body>
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

    document.addEventListener('DOMContentLoaded', function() {
        const deleteBtn = document.getElementById('deleteProfilePicture');
        const confirmDeleteModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
        const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');

        deleteBtn.addEventListener('click', () => {
            confirmDeleteModal.show();
        });

        confirmDeleteBtn.addEventListener('click', () => {
            fetch("{{ route('user.removeProfilePicture') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({})
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        deleteBtn.style.display = 'none';
                        confirmDeleteModal.hide();
                        location.reload();

                    } else {
                        alert('Failed to remove profile picture.');
                    }
                })
                .catch(() => alert('An error occurred.'));
        });
    });
</script>

</html>
