<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard</title>
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #06043E;
        }

        .container {
            background-color: #19174B;
            padding: 30px;
            border-radius: 10px;
            max-width: 1000px;
            margin: 40px auto;
        }

        h2, h3 {
            color: #05EEFF;
        }

        a, label {
            color: #05EEFF;
        }

        .form-section, .story {
            background-color: #2a2860;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }

        select, input[type="number"], button, textarea {
            margin-top: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
            padding: 8px;
            border: none;
        }

        button {
            background-color: #05EEFF;
            color: #000;
            cursor: pointer;
        }

        .alert {
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .story-content p {
            margin: 10px 0;
        }

        p {
            color: #ccc;
        }
    </style>
</head>

<body>
    @include('layout.nav')

    <div class="container">
        {{-- Notifications --}}
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-warning">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Create Blog Form --}}
        <div class="form-section">
            <h3>Create a New Blog</h3>
            <form method="POST" action="{{ route('blogs.create') }}">
                @csrf
                <input type="hidden" name="story_id" id="story_id_input" />

                <label for="story_select">Select Your Story:</label><br>
                <select id="story_select" name="story_id" class="form-control" required>
                    <option value="">--Select Your Story--</option>
                    @foreach ($myStories as $story)
                        <option value="{{ $story->id }}" data-title="{{ $story->title }}">{{ $story->title }}</option>
                    @endforeach
                </select>

                <label for="price">Price:</label><br>
                <input type="number" name="price" step="0.01" min="0" class="form-control" required>

                <label for="visibility">Visibility:</label><br>
                <select name="visibility" class="form-control" required>
                    <option value="full">Full Content</option>
                    <option value="partial">First 50 Lines</option>
                </select>

                <button type="submit">Create Blog</button>
            </form>
        </div>

        {{-- Blog List --}}
        <h2>Blogs</h2>
        @forelse($blogs as $blog)
            @php
                $story = $blog->story;
                $storyContent = $story->content;
                $displayContent = $blog->visibility === 'partial' ? mb_substr($storyContent, 0, 50) . '...' : $storyContent;
            @endphp

            <div class="story">
                <h4>{{ $story->title }} by {{ $story->user->name }}</h4>
                <div class="story-content">
                    <p>{{ $displayContent }}</p>
                    <p><strong>Price:</strong> ${{ number_format($blog->price, 2) }}</p>
                    <p><strong>Visibility:</strong> {{ ucfirst($blog->visibility) }}</p>

                    {{-- Purchase Options --}}
                    <form method="POST" action="{{ route('paypal.create') }}" class="d-inline-block me-2">
                        @csrf
                        <input type="hidden" name="blog_id" value="{{ $blog->id }}">
                        <button type="submit">Buy via PayPal</button>
                    </form>
                    <form method="POST" action="{{ route('cart.add') }}" class="d-inline-block">
                        @csrf
                        <input type="hidden" name="blog_id" value="{{ $blog->id }}">
                        <button type="submit">Add to Cart</button>
                    </form>

                    {{-- Review Form --}}
                    @if (auth()->check())
                        <form action="{{ route('reviews.store', $blog->id) }}" method="POST" style="margin-top: 20px;">
                            @csrf
                            <label for="rating">Rate this blog:</label>
                            <select name="rating" required class="form-control w-25">
                                <option value="">--Select--</option>
                                @for ($i = 1; $i <= 5; $i++)
                                    <option value="{{ $i }}">{{ $i }} Star{{ $i > 1 ? 's' : '' }}</option>
                                @endfor
                            </select>

                            <label for="comment">Review:</label>
                            <textarea name="comment" rows="3" class="form-control" placeholder="Write your thoughts..."></textarea>

                            <button type="submit" class="btn mt-2">Submit Review</button>
                        </form>
                    @endif

                    {{-- Display Reviews --}}
                    @if ($blog->reviews->count())
                        <div style="margin-top: 20px;">
                            <h5 style="color: #05EEFF;">Reviews:</h5>
                            @foreach ($blog->reviews as $review)
                                <div style="background: #1b1b3a; padding: 10px; margin-bottom: 10px; border-radius: 6px;">
                                    <strong>{{ $review->user->name }}</strong> —
                                    <span style="color: gold;">{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</span>
                                    <p>{{ $review->comment }}</p>
                                    <small style="color: #888;">{{ $review->created_at->diffForHumans() }}</small>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <p>No blogs available.</p>
        @endforelse
    </div>

    <script>
        // Auto-set hidden story ID input
        document.querySelector('#story_select').addEventListener('change', function () {
            document.getElementById('story_id_input').value = this.value;
        });

        const select = document.getElementById('story_select');
        if (select.value) {
            document.getElementById('story_id_input').value = select.value;
        }
    </script>
</body>

</html>
