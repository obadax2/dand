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
        /* Search bar styling */
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
    <div class="hero-section">
        <div>
            <br>
            @include('layout.nav')

            <!-- SEARCH BAR HERE -->
            <input type="text" id="ajaxSearchInput" placeholder="Search users by name or username..." autocomplete="off" />
            <ul id="searchResults"></ul>

            <div class="container">
                <div class="typing-container">
                    <div class="line-one">Your website for</div>
                    <div class="line-two">
                        generating a <span id="typed-text"></span><span class="cursor">|</span>
                    </div>
                </div>
            </div>

            {{-- Submit Complaint Button --}}
            @if(auth()->check())
            <div style="text-align: center; margin: 2rem auto;">
                <a href="{{ route('tickets.form') }}" class="com">
                    Submit a Complaint
                </a>
            </div>
            @endif

            {{-- Poll success message --}}
            @if (session('success'))
            <div style="color: green; text-align: center; margin-top: 1rem;">
                {{ session('success') }}
            </div>
            @endif

            {{-- Polls container --}}
            <div class="polls-container">
                @forelse ($polls as $poll)
                @php
                $userVote = auth()->check()
                ? \App\Models\PollVote::where('poll_id', $poll->id)
                ->where('user_id', auth()->id())
                ->first()
                : null;
                @endphp

                <div class="card1">
                    <div class="card-inner1">
                        {{-- FRONT SIDE: Show the poll question --}}
                        <div class="card-front1">
                            <h3 class="poll-title">{{ $poll->title }}</h3>
                        </div>

                        {{-- BACK SIDE: Show vote buttons or the user's vote --}}
                        <div class="card-back1">
                            @if (!$userVote)
                            <div class="vote-buttons">
                                <form action="{{ route('polls.vote', ['poll' => $poll->id, 'vote' => 'yes']) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn-yes">
                                        Yes ({{ $poll->yes_count }})
                                    </button>
                                </form>

                                <form action="{{ route('polls.vote', ['poll' => $poll->id, 'vote' => 'no']) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn-no">
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

                @empty
                <p style="text-align:center;">No polls available right now.</p>
                @endforelse
            </div>

            {{-- The repeated typing-container blocks below (keep as is if you want) --}}
            <div class="container">
                <div class="typing-container">
                    <div class="line-one">Your website for</div>
                    <div class="line-two">
                        generating a <span id="typed-text"></span><span class="cursor">|</span>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="typing-container">
                    <div class="line-one">Your website for</div>
                    <div class="line-two">
                        generating a <span id="typed-text"></span><span class="cursor">|</span>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="typing-container">
                    <div class="line-one">Your website for</div>
                    <div class="line-two">
                        generating a <span id="typed-text"></span><span class="cursor">|</span>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="typing-container">
                    <div class="line-one">Your website for</div>
                    <div class="line-two">
                        generating a <span id="typed-text"></span><span class="cursor">|</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Typing effect script as you had it
        const options = ["Story", "Map", "Character"];
        const typedText = document.getElementById("typed-text");

        let optionIndex = 0;
        let charIndex = 0;
        let deleting = false;

        function typeLoop() {
            const currentOption = options[optionIndex];
            if (!deleting) {
                typedText.textContent = currentOption.substring(0, charIndex + 1);
                charIndex++;
                if (charIndex === currentOption.length) {
                    deleting = true;
                    setTimeout(typeLoop, 2000); // Pause before deleting
                    return;
                }
            } else {
                typedText.textContent = currentOption.substring(0, charIndex - 1);
                charIndex--;
                if (charIndex === 0) {
                    deleting = false;
                    optionIndex = (optionIndex + 1) % options.length;
                }
            }
            setTimeout(typeLoop, deleting ? 80 : 150);
        }

        document.addEventListener("DOMContentLoaded", typeLoop);
    </script>

    <script>
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

                        // User info div
                        const userInfo = document.createElement('div');
                        userInfo.textContent = `${user.name} (${user.username})`;
                        userInfo.style.flexGrow = '1';
                        userInfo.style.cursor = 'pointer';
                        userInfo.addEventListener('click', () => {
                            window.location.href = `/users/${user.id}`;
                        });

                        // Friend button
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
                                    // Send accept friend request POST
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
    }).then(response => {
        if (!response.ok) {
            return response.json().then(data => { throw new Error(data.error || 'Error'); });
        }
        return response.json();
    }).then(data => {
        friendBtn.textContent = 'Request Sent';
        friendBtn.disabled = true;
    }).catch(err => {
        alert(err.message);
    });
});
                                break;
                        }

                        // Follow button
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
