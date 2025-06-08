<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Welcome</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <link rel="stylesheet" href="style.css" />

    <style>
        /* Notification styles */
        .notification-icon {
            position: fixed;
            top: 20px;
            right: 20px;
            cursor: pointer;
            font-size: 24px;
            color: #333;
            z-index: 1100;
        }

        .notification-badge {
            position: absolute;
            top: -6px;
            right: -6px;
            background: red;
            color: white;
            font-size: 12px;
            padding: 2px 6px;
            border-radius: 50%;
            font-weight: bold;
        }

        .notification-dropdown {
            display: none;
            position: fixed;
            top: 50px;
            right: 20px;
            width: 320px;
            max-height: 300px;
            overflow-y: auto;
            background: white;
            border: 1px solid #ccc;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.2);
            border-radius: 6px;
            z-index: 1100;
        }

        .notification-dropdown.active {
            display: block;
        }

        .notification-item {
            padding: 10px;
            border-bottom: 1px solid #eee;
            font-size: 14px;
        }

        .notification-item:last-child {
            border-bottom: none;
        }

        .notification-item p {
            margin: 0 0 5px 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .notification-item a {
            text-decoration: none;
            color: #007bff;
            font-weight: 600;
        }

        .notification-item a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

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

    @if(auth()->check())
        <div class="notification-icon" id="notificationIcon" title="View Admin Replies">
            <i class="fa fa-bell"></i>
            @if($repliedTickets->count() > 0)
                <span class="notification-badge">{{ $repliedTickets->count() }}</span>
            @endif
        </div>

        <div class="notification-dropdown" id="notificationDropdown">
            @if($repliedTickets->isEmpty())
                <p style="padding: 10px;">No new replies.</p>
            @else
                @foreach($repliedTickets as $ticket)
                    <div class="notification-item">
                        <p><strong>Reply to:</strong> {{ Str::limit($ticket->content, 40) }}</p>
                        <a href="{{ route('tickets.show', $ticket->id) }}">View Full Reply</a>
                    </div>
                @endforeach
            @endif
        </div>
    @endif


    <div class="hero-section">
        <div class="container">
            @include('layout.nav')
            <div class="typing-container">
                <div class="line-one">Your website for</div>
                <div class="line-two">
                    generating a <span id="typed-text"></span><span class="cursor">|</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Submit Complaint Button --}}
    @if(auth()->check())
        <div style="text-align: center; margin: 2rem auto;">
            <a href="{{ route('tickets.form') }}" style="background: #007BFF; color: white; padding: 0.6rem 1.2rem; border-radius: 5px; text-decoration: none; font-weight: bold;">
                Submit a Complaint
            </a>
        </div>
    @endif

    {{-- Poll success message --}}
    @if(session('success'))
        <div style="color: green; text-align: center; margin-top: 1rem;">
            {{ session('success') }}
        </div>
    @endif

    {{-- Polls container --}}
    <div class="container">
        <h2 style="text-align:center; margin: 2rem 0;">Active Polls</h2>

        @forelse ($polls as $poll)
            @php
                $userVote = auth()->check()
                    ? \App\Models\PollVote::where('poll_id', $poll->id)
                        ->where('user_id', auth()->id())->first()
                    : null;
            @endphp

            <div style="border: 1px solid #ccc; border-radius: 10px; padding: 1.5rem; margin-bottom: 1.5rem; background: #f9f9f9;">
                <h3 style="margin-bottom: 1rem;">{{ $poll->title }}</h3>

                @if(!$userVote)
                    <div style="display: flex; justify-content: space-between; max-width: 400px; margin-bottom: 1rem;">
                        <form action="{{ route('polls.vote', ['poll' => $poll->id, 'vote' => 'yes']) }}" method="POST">
                            @csrf
                            <button type="submit" style="background-color: #4CAF50; color: white; padding: 0.5rem 1rem; border: none; border-radius: 5px;">
                                Yes ({{ $poll->yes_count }})
                            </button>
                        </form>

                        <form action="{{ route('polls.vote', ['poll' => $poll->id, 'vote' => 'no']) }}" method="POST">
                            @csrf
                            <button type="submit" style="background-color: #f44336; color: white; padding: 0.5rem 1rem; border: none; border-radius: 5px;">
                                No ({{ $poll->no_count }})
                            </button>
                        </form>
                    </div>
                @else
                    <p style="font-style: italic; color: #555;">You voted: <strong>{{ ucfirst($userVote->vote) }}</strong></p>
                @endif
            </div>
        @empty
            <p style="text-align:center;">No polls available right now.</p>
        @endforelse
    </div>

    {{-- Repeated typing containers (you can consider removing duplicates if needed) --}}
    <div class="container">
        <div class="typing-container">
            <div class="line-one">Your website for</div>
            <div class="line-two">
                generating a <span id="typed-text"></span><span class="cursor">|</span>
            </div>
        </div>
    </div>
    <!-- (Repeated multiple times as in your original) -->

</body>

<script>
    // Notification toggle
    document.getElementById('notificationIcon')?.addEventListener('click', function () {
        document.getElementById('notificationDropdown').classList.toggle('active');
    });

    // Close notification dropdown when clicking outside
    window.addEventListener('click', function (e) {
        const dropdown = document.getElementById('notificationDropdown');
        const icon = document.getElementById('notificationIcon');
        if (!dropdown?.contains(e.target) && !icon?.contains(e.target)) {
            dropdown?.classList.remove('active');
        }
    });

    // Typing effect script as you had it
    const options = ["Story", "Map", "Character"];
    const typedText = document.getElementById("typed-text");
    let optionIndex = 0;
    let charIndex = 0;
    let deleting = false;

    function typeLoop() {
        const currentOption = options[optionIndex];
        if (!deleting && charIndex <= currentOption.length) {
            typedText.textContent = currentOption.substring(0, charIndex++);
            setTimeout(typeLoop, 100);
        } else if (deleting && charIndex >= 0) {
            typedText.textContent = currentOption.substring(0, charIndex--);
            setTimeout(typeLoop, 50);
        } else {
            deleting = !deleting;
            if (!deleting) optionIndex = (optionIndex + 1) % options.length;
            setTimeout(typeLoop, 500);
        }
    }

    typeLoop();
</script>

</html>
