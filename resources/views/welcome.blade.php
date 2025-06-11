<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Welcome</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.lineicons.com/3.0/lineicons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('style.css') }}">

    <style>
        #ajaxSearchInput {
            width: 100%;
            max-width: 400px;
            padding: 8px 12px;
            border-radius: 4px;
            border: 1px solid #ccc;
            margin: 20px auto 5px;
            display: block;
            font-size: 16px;
        }

        #searchResults {
            width: 100%;
            max-width: 400px;
            margin: 0 auto;
            border: 1px solid #ccc;
            border-top: none;
            border-radius: 0 0 4px 4px;
            max-height: 200px;
            overflow-y: auto;
            background: white;
            position: relative;
            z-index: 1000;
            display: none;
        }

        #searchResults li {
            list-style: none;
            padding: 8px 12px;
            cursor: pointer;
            border-bottom: 1px solid #eee;
        }

        #searchResults li:hover {
            background-color: #f0f0f0;
        }
    </style>
</head>

<body>
    <div class="container">
        <br>
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

        @if (auth()->check())
            <div style="text-align: center; margin: 2rem auto;">
                <button class="com" data-bs-toggle="modal" data-bs-target="#complaintModal">
                    Submit a Complaint
                </button>
            </div>
        @endif

        <input type="text" id="ajaxSearchInput" placeholder="Search users by name or username..." autocomplete="off" />
        <ul id="searchResults"></ul>

        <!-- Typing Effect Section -->
        <div class="typing-container">
            <div class="line-one">Your website for</div>
            <div class="line-two">
                generating a <span class="typed-text"></span><span class="cursor">|</span>
            </div>
        </div>

        <!-- Polls Section -->
        <div class="polls-container row">
            @forelse ($polls as $poll)
                @php
                    $userVote = auth()->check()
                        ? \App\Models\PollVote::where('poll_id', $poll->id)
                            ->where('user_id', auth()->id())
                            ->first()
                        : null;
                @endphp

                <div class="col-md-4 col-sm-6 mb-4 d-flex justify-content-center">
                    <div class="card1">
                        <div class="card-inner1">
                            <div class="card-front1">
                                <h3 class="poll-title">{{ $poll->title }}</h3>
                            </div>
                            <div class="card-back1">
                                @if (Auth::user() && (Auth::user()->role === 'hr' || Auth::user()->role === 'admin'))
                                    <form action="{{ route('polls.destroy', $poll->id) }}" method="POST"
                                        class="position-absolute top-0 end-0 mt-2 me-2">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Are you sure you want to delete this poll?')">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                @endif

                                @if (!$userVote)
                                    <div class="vote-buttons">
                                        <form action="{{ route('polls.vote', ['poll' => $poll->id, 'vote' => 'yes']) }}" method="POST">
                                            @csrf
                                            <button style="color: white" type="submit" class="btn btn-dark">
                                                Yes ({{ $poll->yes_count }})
                                            </button>
                                        </form>
                                        <form action="{{ route('polls.vote', ['poll' => $poll->id, 'vote' => 'no']) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-light">
                                                No ({{ $poll->no_count }})
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <p class="voted-text">
                                        You voted: <strong>{{ ucfirst($userVote->vote) }}</strong>
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-center text-white">No polls available right now.</p>
            @endforelse
        </div>

        <!-- Bottom Typing Container -->
        <div class="typing-container">
            <div class="line-one">Your website for</div>
            <div class="line-two">
                generating a <span class="typed-text"></span><span class="cursor">|</span>
            </div>
        </div>
    </div>

    <!-- Complaint Modal -->
    <div class="modal fade" id="complaintModal" tabindex="-1" aria-labelledby="complaintModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-light" style="background-color: rgba(25, 23, 75, 0.5); backdrop-filter: blur(12px);">
                <div class="modal-header">
                    <h5 class="modal-title" id="complaintModalLabel">Submit a Complaint</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('tickets.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="content" class="form-label">Your Complaint:</label>
                            <textarea name="content" id="content" rows="4" class="form-control" required></textarea>
                        </div>
                        <button type="submit" class="genButton">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const alert = document.querySelector('.alert');
        if (alert) {
            setTimeout(() => {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            }, 3000);
        }

        const options = ["Story", "Map", "Character"];
        const typedTextElements = document.querySelectorAll(".typed-text");

        let optionIndex = 0;
        let charIndex = 0;
        let deleting = false;

        function typeLoop() {
            const currentOption = options[optionIndex];

            typedTextElements.forEach(el => {
                el.textContent = deleting ?
                    currentOption.substring(0, charIndex - 1) :
                    currentOption.substring(0, charIndex + 1);
            });

            if (!deleting) {
                charIndex++;
                if (charIndex === currentOption.length) {
                    deleting = true;
                    setTimeout(typeLoop, 2000);
                    return;
                }
            } else {
                charIndex--;
                if (charIndex === 0) {
                    deleting = false;
                    optionIndex = (optionIndex + 1) % options.length;
                }
            }

            setTimeout(typeLoop, deleting ? 80 : 150);
        }

        document.addEventListener("DOMContentLoaded", typeLoop);

        // Tooltip Init
        document.addEventListener('DOMContentLoaded', function () {
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.forEach(function (tooltipTriggerEl) {
                new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });

        // AJAX User Search
        const searchInput = document.getElementById('ajaxSearchInput');
        const resultsBox = document.getElementById('searchResults');
        let debounceTimer;

        searchInput.addEventListener('input', () => {
            const query = searchInput.value.trim();
            clearTimeout(debounceTimer);

            if (query.length < 2) {
                resultsBox.style.display = 'none';
                resultsBox.innerHTML = '';
                return;
            }

            debounceTimer = setTimeout(() => {
                fetch(`{{ route('users.ajaxSearch') }}?query=${encodeURIComponent(query)}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    resultsBox.innerHTML = '';
                    if (data.length === 0) {
                        resultsBox.style.display = 'none';
                        return;
                    }
                    data.forEach(user => {
                        const li = document.createElement('li');
                        li.style.display = 'flex';
                        li.style.justifyContent = 'space-between';
                        li.style.alignItems = 'center';

                        const userInfo = document.createElement('div');
                        userInfo.textContent = `${user.name} (${user.username})`;
                        userInfo.style.flexGrow = '1';
                        userInfo.style.cursor = 'pointer';
                        userInfo.addEventListener('click', () => {
                            window.location.href = `/users/${user.id}`;
                        });

                        const friendBtn = document.createElement('button');
                        friendBtn.style.marginRight = '10px';

                        switch (user.friendshipStatus) {
                            case 'friends':
                                friendBtn.textContent = 'Friends';
                                friendBtn.disabled = true;
                                break;
                            case 'pending_sent':
                                friendBtn.textContent = 'Request Sent';
                                friendBtn.disabled = true;
                                break;
                            case 'pending_received':
                                friendBtn.textContent = 'Accept Request';
                                friendBtn.disabled = false;
                                friendBtn.addEventListener('click', () => {
                                    fetch(`/friends/accept/${user.id}`, {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                        }
                                    }).then(() => {
                                        friendBtn.textContent = 'Friends';
                                        friendBtn.disabled = true;
                                    });
                                });
                                break;
                            case 'none':
                            default:
                                friendBtn.textContent = 'Add Friend';
                                friendBtn.disabled = false;
                                friendBtn.addEventListener('click', () => {
                                    fetch(`/friends/request/${user.id}`, {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                        }
                                    })
                                    .then(response => {
                                        if (!response.ok) {
                                            return response.json().then(data => { throw new Error(data.error || 'Error'); });
                                        }
                                        return response.json();
                                    })
                                    .then(data => {
                                        friendBtn.textContent = 'Request Sent';
                                        friendBtn.disabled = true;
                                    })
                                    .catch(err => {
                                        alert(err.message);
                                    });
                                });
                                break;
                        }

                        const followBtn = document.createElement('button');
                        followBtn.textContent = user.isFollowing ? 'Unfollow' : 'Follow';
                        followBtn.addEventListener('click', () => {
                            const url = user.isFollowing ? `/unfollow/${user.id}` : `/follow/${user.id}`;
                            fetch(url, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                }
                            }).then(() => {
                                user.isFollowing = !user.isFollowing;
                                followBtn.textContent = user.isFollowing ? 'Unfollow' : 'Follow';
                            });
                        });

                        li.appendChild(userInfo);
                        li.appendChild(friendBtn);
                        li.appendChild(followBtn);
                        resultsBox.appendChild(li);
                    });
                    resultsBox.style.display = 'block';
                })
                .catch(err => {
                    console.error('Search error:', err);
                    resultsBox.style.display = 'none';
                });
            }, 300);
        });

        document.addEventListener('click', (e) => {
            if (!searchInput.contains(e.target) && !resultsBox.contains(e.target)) {
                resultsBox.style.display = 'none';
            }
        });
    </script>
</body>
</html>
