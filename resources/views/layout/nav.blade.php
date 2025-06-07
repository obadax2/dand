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
        /* semi-transparent */
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
                <li><a href="{{ route('dashboard') }}">Edit</a></li>
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


        <li>
            <button>Try Now<i class="fas fa-rocket"></i></button>
        </li>

    </ul>
</nav>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
