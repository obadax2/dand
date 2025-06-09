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
</head>

<body>


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

    <div class="hero-section">
        <div>
            <br>
            @include('layout.nav')
            <div class="container">
                <div class="typing-container">
                    <div class="line-one">Your website for</div>
                    <div class="line-two">
                        generating a <span id="typed-text"></span><span class="cursor">|</span>
                    </div>
                </div>
            </div>


            {{-- Submit Complaint Button --}}
            @if (auth()->check())
                <div style="text-align: center; margin: 2rem auto;">
                    <button class="com" data-bs-toggle="modal" data-bs-target="#complaintModal">
                        Submit a Complaint
                    </button>

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
                                        <form action="{{ route('polls.vote', ['poll' => $poll->id, 'vote' => 'yes']) }}"
                                            method="POST">
                                            @csrf
                                            <button type="submit" class="btn-yes">
                                                Yes ({{ $poll->yes_count }})
                                            </button>
                                        </form>

                                        <form action="{{ route('polls.vote', ['poll' => $poll->id, 'vote' => 'no']) }}"
                                            method="POST">
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

        </div>
    </div>
    <div class="modal fade" id="complaintModal" tabindex="-1" aria-labelledby="complaintModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content  text-light "
                style="    background-color: rgba(25, 23, 75, 0.5);backdrop-filter: blur(12px);">
                <div class="modal-header">
                    <h5 class="modal-title" id="complaintModalLabel">Submit a Complaint</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
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

</body>
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



</html>
