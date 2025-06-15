<style>
    body {
        margin: 0;
        padding: 0;
        background-image: url('{{ asset('tt.png') }}');
        background-size: cover;
        background-repeat: no-repeat;
        background-attachment: fixed;
        background-position: center top 80px;
    }

    nav {
        padding: 10px 10px;
        position: relative;
        display: flex;
        align-items: center;
        z-index: 1050;
    }

    nav .logo img {
        height: 20px;
    }

    nav ul {
        display: flex;
        align-items: center;
        gap: 15px;
        list-style: none;
        margin: 0;
        padding: 0;
        justify-content: space-around;
        flex-grow: 1;
    }

    nav ul li {
        position: relative;
    }

    nav ul li a,
    nav ul li button {
        font-family: "Open Sans";
        color: #000000;
        text-decoration: none;
        padding: 8px 12px;
        border-radius: 4px;
        transition: background-color 0.3s;
        background: none;
        font-style: italic;
        border: none;
        cursor: pointer;
        font-size: 17px;
        white-space: nowrap;
    }

    nav ul li a:hover {
        color: #ADD8E6;
    }

    nav ul li button:hover {
        background-color: #122620;
        color: #fff;
    }

    .story-dropdown {
        position: relative;
        list-style: none;
    }

    .story-dropdown .nav-link {
        color: #000000;
        text-decoration: none;
        padding: 10px 15px;
        display: block;
        white-space: nowrap;
    }

    .story-dropdown:hover .story-submenu {
        display: block;
    }

    .story-submenu {
        display: none;
        position: absolute;
        background-color: #fff;
        border-radius: 8px;
        list-style: none;
        z-index: 1000;
        min-width: 150px;
        border: 1px solid #000;
    }


    .story-submenu li {
        width: 100%;
    }

    .story-submenu a {
        display: block;
        padding: 10px 15px;
        color: #000;
        font-style: italic;
        text-decoration: none;
    }

    .story-submenu a:hover {
        color: #ADD8E6;
    }

    /*Notification*/
    .notification-wrapper {
        position: relative;
        display: inline-block;
    }

    .notification-icon {
        position: relative;
        cursor: pointer;
        font-size: 24px;
        color: #000000;
        margin-right: 20px;
    }

    .notification-dropdown {
        display: none;
        position: absolute;
        top: 40px;
        right: 0;
        width: 320px;
        max-height: 300px;
        overflow-y: auto;
        border: 1px solid #000;
        background-color: #fff;
        border-radius: 8px;
        z-index: 1100;
    }

    .notification-dropdown.active {
        display: block;
    }

    .notification-dropdown {
        text-align: center;
    }

    .notification-item {
        padding: 12px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        color: #eee;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
    }

    .notification-item:last-child {
        border-bottom: none;
    }

    .notification-item p {
        margin: 0 0 6px 0;
        font-size: 14px;
        color: #fff;
    }

    .notification-item a {
        text-decoration: none;
        color: #ADD8E6;
        font-weight: 500;
        display: inline-block;
        margin-top: 4px;
        transition: transform 0.2s ease;
    }

    .notification-item a:hover {
        transform: scale(1.05);
    }

    .store-now-link {
        display: inline-block;
        color: #ADD8E6 !important;
        transition: transform 0.3s ease;
    }

    .store-now-link:hover {
        transform: scale(1.05);
    }

    #navToggle {
        background: none;
        border: none;
        color: #05EEFF;
        font-size: 28px;
        cursor: pointer;
        display: none;
    }

    .search-container {
        position: relative;
        left: 0;
        width: 25%;
        margin-left: 10px;
    }

    .search-container input {
        height: 30px;
        border: 1px solid #ccc;
        transform: translateY(30%);
    }

    #searchResults {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background-color: #ffffff;
        border: 1px solid #F4EBD0;
        border-top: none;
        max-height: 250px;
        overflow-y: auto;
        z-index: 1000;
        display: none;
        list-style: none;
        margin: 0;
        padding: 0;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
    }

    #searchResults li {
        padding: 8px 12px;
        cursor: pointer;
    }

    #searchResults li:hover {
        background-color: #767879;
    }

    .search-result-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 12px;
        border-bottom: 1px solid #444;
        cursor: default;
    }

    .search-result-userinfo {
        flex-grow: 1;
        cursor: pointer;
        color: #000000;
        transition: color 0.2s ease;
    }

    .search-result-friend-btn {
        font-size: 12px;
        margin-right: 10px;
        padding: 5px 10px;
        color: #000000;
        cursor: pointer;
        border-radius: 4px;
        transition: background-color 0.2s ease;
    }

    .search-result-friend-btn:disabled {
        background-color: #333;
        border-color: #555;
        cursor: default;
        color: #777;
    }

    .search-result-friend-btn:hover:not(:disabled) {
        background-color: #16383B;
        color: white;
    }

    .search-result-follow-btn {
        font-size: 12px;
        padding: 5px 10px;
        color: #000000;
        cursor: pointer;
        border-radius: 4px;
        transition: background-color 0.2s ease;
    }

    .search-result-follow-btn:hover {
        background-color: #16383B;
        color: white;
    }

    @media (max-width: 600px) {
        .search-result-item {
            flex-direction: column;
            align-items: flex-start;
        }

        .search-result-friend-btn,
        .search-result-follow-btn {
            margin: 5px 0 0 0;
            width: 100%;
        }
    }

    @media (max-width: 768px) {
        .search-container {
            width: 90%;
            margin: 10px auto;
            position: relative;
        }

        .search-container input {
            width: 100%;
            padding: 8px 30px 8px 12px;
            font-size: 14px;
        }

        .search-icon {
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
        }

        #searchResults {
            width: 100%;
            left: 0;
            right: 0;
            max-height: 200px;
        }
    }

    @media (max-width: 768px) {

        .cart-dropdown,
        .notification-dropdown {
            width: 100%;
            left: 0;
            right: 0;
            box-shadow: none;
            border-radius: 0;
        }

        .dropdown-menu-end {
            right: 0 !important;
            left: auto !important;
        }
    }

    @media (max-width: 768px) {
        nav ul li button {
            font-size: 14px;
            padding: 6px 10px;
        }

        .chatbot-btn i {
            font-size: 18px;
        }

        .floating-buttons {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1050;
        }

        .submit-complaint-btn strong {
            font-size: 18px;
        }
    }

    @media (max-width: 768px) {

        .story-dropdown.active>.story-submenu,
        .story-dropdown:hover>.story-submenu {
            display: block;
            padding-left: 20px;
            background-color: #f9f9f9;
            border-left: 2px solid #ADD8E6;
        }

        .story-dropdown .nav-link {
            padding-right: 20px;
        }

        nav ul li a:hover,
        .story-submenu a:hover {
            background-color: transparent;
            color: #ADD8E6;
        }
    }

    @media (max-width: 768px) {
        nav ul {
            flex-direction: column;
            position: fixed;
            top: 60px;
            left: 0;
            right: 0;
            display: none;
            padding: 10px 0;
            z-index: 9999;
            max-height: calc(100vh - 60px);
            overflow-y: auto;
        }

        nav ul.active {
            display: flex;
        }

        nav ul li {
            width: 100%;
            text-align: center;
        }



        .story-dropdown .story-submenu {
            position: static;
            background: transparent;
            backdrop-filter: none;
            padding-left: 20px;
            border-radius: 0;
            display: none;
        }

        .story-dropdown.active>.story-submenu {
            display: block;
        }

        .notification-dropdown {
            position: static;
            max-height: none;
            width: 100%;
            box-shadow: none;
            border: none;
            backdrop-filter: none;
        }

        #navToggle {
            display: block;
        }

        .cart-wrapper {
            position: relative;
            display: inline-block;
        }

        .cart-icon {
            position: relative;
            cursor: pointer;
            font-size: 24px;
            color: #000000;
            margin-right: 20px;
        }


        .cart-badge {
            position: absolute;
            top: -6px;
            right: -6px;
            background: #05EEFF;
            color: #000;
            font-size: 11px;
            padding: 2px 6px;
            border-radius: 50%;
            font-weight: bold;
        }

        .cart-dropdown {
            display: none;
            position: absolute;
            top: 40px;
            right: 0;
            width: 320px;
            max-height: 400px;
            overflow-y: auto;
            border: 1px solid #000;
            background-color: #fff;
            backdrop-filter: blur(5px);
            border-radius: 8px;
            z-index: 1100;
        }

        .cart-dropdown.active {
            display: block;
        }

        .dropdown-menu .btn-outline-danger {
            border: none;
            color: #FF5C5C;
        }
    }

    .search-input {
        width: 100%;
        padding-right: 30px;
    }

    .search-icon {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        color: #aaa;
        pointer-events: none;
    }

    .EmptyCart {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        padding: 20px;
    }

    .EmptyCart .store-now-link {
        font-size: 16px;
        font-weight: 500;
        color: #ADD8E6 !important;
        transition: transform 0.3s ease;
    }

    .EmptyCart .store-now-link:hover {
        transform: scale(1.05);
    }
</style>

<nav>
    <a href="{{ route('home') }}">
        <div class="logo">
            <img src="{{ asset('logo.jpg') }}" alt="Logo">
        </div>
    </a>


    <div class="search-container">
        <input style="color: #000" type="text" id="ajaxSearchInput" placeholder="Search users" autocomplete="off" />
        <i class="fa fa-search search-icon"></i>

        <ul id="searchResults"></ul>
    </div>
    <button id="navToggle" aria-label="Toggle navigation">☰</button>

    <ul>


        <li><a href="{{ route('home') }}">Home</a></li>

        <li class="nav-item story-dropdown" id="storyDropdown1">
            <a href="#" class="nav-link">Story ▾</a>
            <ul class="story-submenu">
                <li><a href="{{ route('stories.create') }}">Create</a></li>
                <li><a href="{{ route('dashboard') }}">Store</a></li>
                <li><a href="{{ route('stories.drafts') }}">Drafts</a></li>
            </ul>
        </li>

        <li class="nav-item story-dropdown">
            <a href="#" class="nav-link">Generate Map ▾</a>
            <ul class="story-submenu">
                <li><a href="{{ route('maps.generate') }}">Select Story</a></li>
            </ul>
        </li>

        <li class="nav-item story-dropdown" id="storyDropdown1">
            <a href="#" class="nav-link">View ▾</a>
            <ul class="story-submenu">
                <li><a href="{{ route('stories.my') }}">My Stories</a></li>
                <li><a href="{{ route('characters.my') }}">My Characters</a></li>
                <li><a href="{{ route('maps.my') }}">My maps</a></li>

            </ul>
        </li>

        <li class="nav-item story-dropdown" id="accountDropdown">
            <a href="#" class="nav-link">Account ▾</a>
            <ul class="story-submenu">
                <li><a href="{{ route('user.profile') }}">Profile</a></li>
                @if (Auth::user()->role === 'admin')
                    <li><a href="{{ route('admin.dashboard') }}">Admin panel</a></li>
                @endif
                @if (Auth::user()->role === 'hr')
                    <li><a href="{{ route('users.index') }}">Management</a></li>
                @endif
                <li>
                    <a href="#"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>

            </ul>
        </li>

        <li class="nav-item dropdown">
            <a class="nav-link position-relative" href="#" id="cartDropdown" data-bs-toggle="dropdown"
                aria-expanded="false">
                <i class="fa fa-shopping-cart"></i>
                @if ($cartItems->count() > 0)
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        {{ $cartItems->count() }}
                    </span>
                @endif
            </a>

            <ul class="dropdown-menu dropdown-menu-end p-3 shadow " style="width: auto;" aria-labelledby="cartDropdown">
                @if ($cartItems->count() > 0)
                    <div class="mb-2">
                        <p style="color: #ADD8E6;">Your Cart <i class="fa fa-shopping-cart"></i></p>
                    </div>
                @endif
                @forelse ($cartItems as $cart)
                    <li class="mb-2 border-top border-bottom py-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="me-2" style="width: auto">
                                <strong
                                    class="text-nowrap text-truncate d-block">{{ $cart->item->story->title ?? 'Untitled' }}</strong>
                                <div class="small text-muted">${{ number_format($cart->price, 2) }}</div>
                            </div>
                            <form action="{{ route('cart.remove', $cart->id) }}" method="POST" class="ms-2">
                                @csrf @method('DELETE')
                                <button
                                    class="btn btn-sm btn-danger rounded-circle p-0 d-flex justify-content-center align-items-center"
                                    style="width: 28px; height: 28px; font-size: 18px; line-height: 1;"
                                    type="submit">&times;</button>
                            </form>
                        </div>
                    </li>
                @empty
                    <div class="EmptyCart">
                        <a href="{{ route('dashboard') }}" class="store-now-link">Store Now!</a>
                    </div>
                @endforelse
                @if ($cartItems->count() > 0)
                    <form method="GET" action="{{ route('paypal.cart.checkout') }}">
                        <button type="submit">Checkout with PayPal</button>
                    </form>
                @endif
            </ul>
        </li>

        @php
            use App\Models\Ticket;

            $repliedTickets = auth()->check()
                ? Ticket::where('user_id', auth()->id())
                    ->whereHas('messages', function ($query) {
                        $query->where('sender', 'admin')->where('is_read', false);
                    })
                    ->with([
                        'messages' => function ($query) {
                            $query->where('sender', 'admin')->where('is_read', false)->latest();
                        },
                    ])
                    ->orderBy('updated_at', 'desc')
                    ->get()
                : collect();
        @endphp
        @if (auth()->check())
            <li>
                <div class="notification-wrapper">
                    <div class="notification-icon" id="notificationIcon" title="View Admin Replies">
                        <i class="fa fa-bell"></i>
                        @if ($repliedTickets->count() > 0)
                            <span
                                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                style="font-size: 0.9rem; padding: 0.25em 0.4em;">
                                {{ $repliedTickets->count() }}
                            </span>
                        @endif
                    </div>

                    <div class="notification-dropdown" id="notificationDropdown">
                        @if ($repliedTickets->isEmpty())
                            <p style="padding: 10px; color:#000">No new replies</p>
                        @else
                            @foreach ($repliedTickets as $ticket)
                                @php
                                    $latestAdminMessage = $ticket->messages->first();
                                @endphp
                                @if ($latestAdminMessage)
                                    <div class="notification-item">
                                        <p style="color: #000"><strong>Admin:</strong>
                                            {{ Str::limit($latestAdminMessage->message, 60) }}
                                        </p>
                                        <a href="{{ route('tickets.show', $ticket->id) }}">View Conversation</a>
                                    </div>
                                @endif
                            @endforeach
                        @endif
                    </div>
                </div>
            </li>
        @endif

        <li>
            <a href="{{ route('stories.create') }}">
                <button>
                    Try Now <i class="fas fa-rocket"></i>
                </button>
            </a>
        </li>




        @if (Auth::user()->role === 'user')
            <li>
                <a href="{{ route('chat.index') }}" class="btn rounded-circle shadow-lg chatbot-btn"
                    data-bs-placement="left" title="Chatbot">
                    <i class="fas fa-robot"></i>
                </a>
            </li>
        @endif
    </ul>

</nav>
@if (Auth::user()->role === 'user')
    <div class="floating-buttons">
        <a href="#" id="openComplaintModal" class="btn rounded-circle shadow-lg submit-complaint-btn"
            data-bs-placement="left" title="Contact Us" onclick="return false;">
            <strong style="font-size: 24px; margin-top: -2px;">!</strong>
        </a>

    </div>
@endif
<div class="modal fade" id="complaintModal" tabindex="-1" aria-labelledby="complaintModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background-color: #000; color: #ffffff;"; border-radius: 10px;">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="complaintModalLabel">Submit a Complaint</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('tickets.store') }}" method="POST" id="complaintForm">
                    @csrf
                    <div class="mb-3">
                        <label for="content" class="form-label">Your Complaint:</label>
                        <textarea name="content" id="content" rows="5" required class="form-control"></textarea>
                    </div>
                    <button type="submit" class="btn btn-light">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const openModalBtn = document.getElementById("openComplaintModal");
        const complaintModalEl = document.getElementById("complaintModal");

        const complaintModal = new bootstrap.Modal(complaintModalEl);

        openModalBtn.addEventListener("click", (e) => {
            e.preventDefault();
            complaintModal.show();
        });
    });


    document.addEventListener("DOMContentLoaded", () => {
        const navToggle = document.getElementById("navToggle");
        const navUl = document.querySelector("nav ul");

        navToggle.addEventListener("click", () => {
            navUl.classList.toggle("active");
        });

        const storyDropdowns = document.querySelectorAll(".story-dropdown > .nav-link");
        storyDropdowns.forEach((link) => {
            link.addEventListener("click", (e) => {
                if (window.innerWidth <= 768) {
                    e.preventDefault();
                    const parentLi = link.parentElement;
                    parentLi.classList.toggle("active");
                }
            });
        });

        const bell = document.getElementById("notificationIcon");
        const dropdown = document.getElementById("notificationDropdown");
        const badge = document.querySelector(".notification-badge");

        bell.addEventListener("click", async () => {
            dropdown.classList.toggle("active");

            if (badge) {
                try {
                    await fetch("{{ route('notifications.markRead') }}", {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "Content-Type": "application/json",
                        },
                        body: JSON.stringify({}),
                    });

                    badge.remove();
                } catch (err) {
                    console.error("Failed to mark notifications as read.", err);
                }
            }
        });

        document.addEventListener("click", (e) => {
            if (!bell.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.classList.remove("active");
            }
        });
    });
    document.addEventListener('DOMContentLoaded', function() {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.forEach(function(tooltipTriggerEl) {
            new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });

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
                        li.classList.add('search-result-item');

                        const userInfo = document.createElement('div');
                        userInfo.textContent = `${user.name} (${user.username})`;
                        userInfo.classList.add('search-result-userinfo');
                        userInfo.addEventListener('click', () => {
                            window.location.href = `/users/${user.id}`;
                        });

                        const friendBtn = document.createElement('button');
                        friendBtn.classList.add('search-result-friend-btn');

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
                                                return response.json().then(
                                                    data => {
                                                        throw new Error(data
                                                            .error ||
                                                            'Error');
                                                    });
                                            }
                                            return response.json();
                                        })
                                        .then(data => {
                                            friendBtn.textContent =
                                                'Request Sent';
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
                        followBtn.classList.add('search-result-follow-btn');
                        followBtn.addEventListener('click', () => {
                            const url = user.isFollowing ? `/unfollow/${user.id}` :
                                `/follow/${user.id}`;
                            fetch(url, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                }
                            }).then(() => {
                                user.isFollowing = !user.isFollowing;
                                followBtn.textContent = user.isFollowing ?
                                    'Unfollow' : 'Follow';
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
    setTimeout(() => {
        const alert = document.getElementById('successAlert');
        if (alert) {
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }
    }, 3000);

    document.addEventListener("click", function(e) {
        const navUl = document.querySelector("nav ul");
        const navToggle = document.getElementById("navToggle");

        if (window.innerWidth <= 768) {
            if (!navUl.contains(e.target) && !navToggle.contains(e.target)) {
                navUl.classList.remove("active");
            }

            if (e.target.closest("nav ul li a")) {
                navUl.classList.remove("active");
            }
        }
    });

    document.querySelectorAll('.story-dropdown > .nav-link').forEach(link => {
        link.addEventListener('click', function(e) {
            if (window.innerWidth <= 768) {
                e.preventDefault();
                const parentLi = this.parentElement;
                parentLi.classList.toggle('active');
            }
        });
    });
</script>
