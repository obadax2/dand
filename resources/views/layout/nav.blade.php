<style>
    nav {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 20px;
        position: relative;
        z-index: 1200;
    }

    nav .logo img {
        max-height: 80px;
    }

    nav ul {
        display: flex;
        align-items: center;
        gap: 35px;
        list-style: none;
        margin: 0;
        padding: 0;
    }

    nav ul li {
        position: relative;
    }

    nav ul li a,
    nav ul li button {
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
    }

    /* nav ul li a.btn.btn-primary:hover {
        background-color: #03c6db;
        color: white;
    } */
    nav ul li button:hover {
        background-color: #122620;
        color: #fff;
    }

    /* Dropdown menu styles */
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
        backdrop-filter: blur(12px);
        border-radius: 8px;
        list-style: none;
        top: 40px;
        right: -40px;
        margin: 0;
        padding: 5px 0;
        z-index: 1000;
        min-width: 150px;
        border: 1px solid #000;
        /* Added black border */
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
        transition: background 0.3s;
    }

    .story-submenu a:hover {
        color: #D6AD60;
    }

    /* Notification styles */
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
        /* Added black border */
        background-color: #fff;
        backdrop-filter: blur(5px);
        border-radius: 8px;
        z-index: 1100;
    }

    .notification-dropdown.active {
        display: block;
    }

    .notification-item {
        padding: 12px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        color: #eee;
    }

    .notification-item:last-child {
        border-bottom: none;
    }

    .notification-item p {
        margin: 0 0 6px 0;
        font-size: 14px;
    }

    .notification-item a {
        text-decoration: none;
        color: #D6AD60;
        font-weight: 500;
    }

    .notification-item a:hover {
        transform: scale(1.05);
    }

    /* Hamburger button */
    #navToggle {
        background: none;
        border: none;
        color: #05EEFF;
        font-size: 28px;
        cursor: pointer;
        display: none;
    }

    /* Responsive styles */
    @media (max-width: 768px) {
        nav ul {
            flex-direction: column;
            position: fixed;
            top: 60px;
            left: 0;
            right: 0;
            display: none;
            /* hidden by default */
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

        /* Show submenu on tap (click) on mobile */
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

        /* Show hamburger */
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

        .dropdown-menu {
            background-color: #06043E;
            /* your dark theme */
            color: #fff;
        }

        .dropdown-menu .btn-outline-danger {
            border: none;
            color: #FF5C5C;
        }

    }
</style>

<nav>
    <div class="logo">
      <img src="{{ asset('storage/logo.jpeg') }}" alt="Logo">
    </div>

    <button id="navToggle" aria-label="Toggle navigation">☰</button>

    <ul>
        @if (Auth::user()->role === 'admin')
            <li><a href="{{ route('admin.dashboard') }}">Admin</a></li>
        @endif
        @if (Auth::user()->role === 'hr')
            <li><a href="{{ route('users.index') }}">Management</a></li>
        @endif

        <li><a href="{{ route('home') }}">Home</a></li>

        <li class="nav-item story-dropdown" id="storyDropdown1">
            <a href="#" class="nav-link">Story ▾</a>
            <ul class="story-submenu">
                <li><a href="{{ route('stories.create') }}">Create</a></li>
                <li><a href="{{ route('dashboard') }}">Store</a></li>
                <li><a href="{{ route('stories.drafts') }}">Drafts</a></li>
            </ul>
        </li>

        <li class="nav-item story-dropdown" id="storyDropdown1">
            <a href="#" class="nav-link">View ▾</a>
            <ul class="story-submenu">
                <li><a href="{{ route('stories.my') }}">My Stories</a></li>
                <li><a href="{{ route('characters.my') }}">My Characters</a></li>
            </ul>
        </li>

        <li class="nav-item story-dropdown" id="accountDropdown">
            <a href="#" class="nav-link">Account ▾</a>
            <ul class="story-submenu">
                <li><a href="{{ route('user.profile') }}">Profile</a></li>
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

            <ul class="dropdown-menu dropdown-menu-end p-3 shadow" style="width: auto;" aria-labelledby="cartDropdown">
                @if ($cartItems->count() > 0)
                    <div class="mb-2">
                        <p style="color: #D6AD60;">Your Cart <i class="fa fa-shopping-cart"></i></p>
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
                    <li class="text-black text-center w-100">Cart is empty</li>
                @endforelse
                @if ($cartItems->count() > 0)
                    <form method="GET" action="{{ route('paypal.cart.checkout') }}">
                        <button type="submit">Checkout with PayPal</button>
                    </form>
                @endif
            </ul>
        </li>

        {{-- Notification Icon --}}
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
            <button>Try Now <i class="fas fa-rocket"></i></button>
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
    <li class="nav-item story-dropdown">
    <a href="#" class="nav-link">Generate Map ▾</a>
    <ul class="story-submenu">
        <li><a href="{{ route('maps.generate') }}">Select Story</a></li>
    </ul>
</li>
</nav>
@if (Auth::user()->role === 'user')
    <div class="floating-buttons">
        <a href="#" id="openComplaintModal" class="btn rounded-circle shadow-lg submit-complaint-btn"
            data-bs-placement="left" title="Contact Us" onclick="return false;">
            <strong style="font-size: 24px; margin-top: -2px;">!</strong>
        </a>

    </div>
@endif
<!-- Submit Complaint Modal -->
<div class="modal fade" id="complaintModal" tabindex="-1" aria-labelledby="complaintModalLabel" aria-hidden="true">
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
                        <textarea name="content" id="content" rows="5" required class="form-control"
                            style="background: #0a0a3c; color: white; "></textarea>
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

        // Initialize Bootstrap modal object
        const complaintModal = new bootstrap.Modal(complaintModalEl);

        openModalBtn.addEventListener("click", (e) => {
            e.preventDefault();
            complaintModal.show(); // Open the modal via JS
        });
    });


    document.addEventListener("DOMContentLoaded", () => {
        const navToggle = document.getElementById("navToggle");
        const navUl = document.querySelector("nav ul");

        // Toggle nav visibility on small screens
        navToggle.addEventListener("click", () => {
            navUl.classList.toggle("active");
        });

        // Dropdown toggling for mobile - tap to open submenus
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

        // Notification bell dropdown toggle
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

                    badge.remove(); // remove the red badge visually
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
</script>
