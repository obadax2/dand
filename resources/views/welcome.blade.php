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
    <div class="container">
        <br>
        @include('layout.nav')

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
                            <!-- Front -->
                            <div class="card-front1 ">
                                <h3 class="poll-title">{{ $poll->title }}</h3>
                            </div>
                            <!-- Back -->
                            <div class="card-back1">
                                @if (Auth::user() && (Auth::user()->role === 'hr' || Auth::user()->role === 'admin'))
                                    <!-- Delete Button -->
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
                                        <form action="{{ route('polls.vote', ['poll' => $poll->id, 'vote' => 'yes']) }}"
                                            method="POST">
                                            @csrf
                                            <button style="color: white" type="submit" class="btn btn-dark">
                                                Yes ({{ $poll->yes_count }})
                                            </button>
                                        </form>

                                        <form action="{{ route('polls.vote', ['poll' => $poll->id, 'vote' => 'no']) }}"
                                            method="POST">
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

        <!-- Reuse Typing Container at the Bottom -->
        <div class="typing-container">
            <div class="line-one">Your website for</div>
            <div class="line-two">
                generating a <span class="typed-text"></span><span class="cursor">|</span>
            </div>
        </div>
    </div>

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

        document.addEventListener('DOMContentLoaded', function() {
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.forEach(function(tooltipTriggerEl) {
                new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
</body>

</html>
