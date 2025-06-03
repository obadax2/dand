<style>
    button {
        background-color: #05EEFF;
        border: none;
        padding: 8px 14px;
        color: #000;
        border-radius: 5px;
        cursor: pointer;
        margin-top: 6px;
    }
</style>
<nav>
    <ul>
        <li><a href="{{ route('home') }}">Home</a></li>
        <li><a href="{{ route('user.profile') }}">Profile</a></li>
        <li><a href="#">Services</a></li>
        <li><a href="#">Contact</a></li>
        <li>
            <button>Try Now<i class="fas fa-rocket"></i></button>
        </li>


        <li>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
            {{-- <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                Logout
            </a> --}}
        </li>
    </ul>
</nav>
