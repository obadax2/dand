<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Welcome</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <link rel="stylesheet" href="style.css" />
</head>

<body>

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

    {{-- Optional commented out card section
    <div class="card1">
        <div class="card-inner1">
            <div class="card-front1">
                <p>Front Side</p>
            </div>
            <div class="card-back1">
                <p>Back Side</p>
            </div>
        </div>
    </div>
    --}}

</body>

<script>
    const options = ["Story", "Map", "Character"];
    const typedText = document.getElementById("typed-text");
    let optionIndex = 0;
    let charIndex = 0;
    let deleting = false;

    function typeLoop() {
        const currentOption = options[optionIndex]
