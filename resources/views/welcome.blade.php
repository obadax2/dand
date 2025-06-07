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
</body>
<script>
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
