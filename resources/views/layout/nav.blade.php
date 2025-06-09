<style>
    .story-dropdown {
        position: relative;
        list-style: none;
    }

    .story-dropdown .nav-link {
        color: #ffffff;
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
        background-color: rgba(25, 23, 75, 0.5);
        backdrop-filter: blur(1px);
        border-radius: 8px;
        list-style: none;
        top: 40px;
        right: -40px;
        margin: 0;
        padding: 5px 0;
        z-index: 1000;
        min-width: 150px;
    }

    .story-submenu li {
        width: 100%;
    }

    .story-submenu a {
        display: block;
        padding: 10px 15px;
        color: #05EEFF;
        text-decoration: none;
        transition: background 0.3s;
    }

    .story-submenu a:hover {
        color: #B6A7C0;
    }

    /* Notification section */
    .notification-wrapper {
        position: relative;
        display: inline-block;
    }

    .notification-icon {
        position: relative;
        cursor: pointer;
        font-size: 24px;
        color: #ffffff;
        margin-right: 20px;
    }

    .notification-badge {
        position: absolute;
        top: -6px;
        right: -6px;
        background: red;
        color: white;
        font-size: 11px;
        padding: 2px 6px;
        border-radius: 50%;
        font-weight: bold;
    }

    .notification-dropdown {
        display: none;
        position: absolute;
        top: 40px;
        right: 0;
        width: 320px;
        max-height: 300px;
        overflow-y: auto;
        background: rgba(25, 23, 75, 0.5);
        backdrop-filter: blur(5px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow: 0 3px 6px rgba(0, 183, 255, 0.2);
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
        color: #05EEFF;
        font-weight: 500;
    }

    .notification-item a:hover {
        color: #B6A7C0;
        text-decoration: underline;
    }
</style>

<nav>
    <div class="logo">
        <img src="your-logo.png" alt="Logo Here">
    </div>
    <ul>



        @if (Auth::user()->role === 'admin')
            <li><a href="{{ route('admin.dashboard') }}">Admin</a></li>
        @endif
        @if (Auth::user()->role === 'hr')
            <li><a href="{{ route('users.index') }}">Management</a></li>
        @endif

        <li><a href="{{ route('home') }}">Home</a></li>


        <li class="nav-item story-dropdown">
            <a href="#" class="nav-link">Story ▾</a>
            <ul class="story-submenu">
                <li><a href="{{ route('stories.create') }}">Create</a></li>
                <li><a href="{{ route('dashboard') }}">Store</a></li>
                <li><a href="{{ route('stories.drafts') }}">Drafts</a></li>
            </ul>
        </li>


        <li class="nav-item story-dropdown">
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

        {{-- Notification Icon --}}
        @php
            // Get logged-in user's tickets with replies
$repliedTickets = auth()->check()
    ? \App\Models\Ticket::where('user_id', auth()->id())
        ->whereNotNull('reply')
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
                            <span class="notification-badge">{{ $repliedTickets->count() }}</span>
                        @endif
                    </div>

                    <div class="notification-dropdown" id="notificationDropdown">
                        @if ($repliedTickets->isEmpty())
                            <p style="padding: 10px;">No new replies.</p>
                        @else
                            @foreach ($repliedTickets as $ticket)
                                <div class="notification-item">
                                    <p><strong>Reply to:</strong> {{ Str::limit($ticket->content, 40) }}</p>
                                    <a href="{{ route('tickets.show', $ticket->id) }}">View Full Reply</a>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </li>

        @endif

        <li>
            <button>Try Now<i class="fas fa-rocket"></i></button>
        </li>

    </ul>
</nav>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const bell = document.getElementById('notificationIcon');
        const dropdown = document.getElementById('notificationDropdown');

        bell.addEventListener('click', () => {
            dropdown.classList.toggle('active');
        });

        document.addEventListener('click', (e) => {
            if (!bell.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.classList.remove('active');
            }
        });
    });
</script>
