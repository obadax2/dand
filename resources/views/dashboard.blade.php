<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.lineicons.com/3.0/lineicons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <style>
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

        label {
            color: #fff;
        }

        h4 {
            color: #03bfd4;
        }

        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type=number] {
            -moz-appearance: textfield;
        }

        textarea::placeholder {
            color: #6f7777 !important;
            /* Replace with your desired color */
        }
        strong{
            color: #03bfd4;
        }

        .review {
            width: 300px;
            background-color: rgba(25, 23, 75, 0.5);
            backdrop-filter: blur(1px);
        }
    </style>
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

            <div class="container4">

                <div class="form-section">
                    <h3>Sell your story</h3>
                    <form method="POST" action="{{ route('blogs.create') }}">
                        @csrf
                        <input type="hidden" name="story_id" id="story_id_input" />

                        <label for="story_select">Select Your Story:</label><br>
                        <select id="story_select" name="story_id" class="form-control" required>
                            <option value="">--Select Your Story--</option>
                            @foreach ($myStories as $story)
                                <option value="{{ $story->id }}" data-title="{{ $story->title }}">{{ $story->title }}
                                </option>
                            @endforeach
                        </select>

                        <label for="price">Price ($):</label><br>
                        <input type="number" name="price" step="0.01" min="0" class="form-control"
                            required>

                        <label for="visibility">Visibility:</label><br>
                        <select name="visibility" class="form-control" required>
                            <option value="full">Full Content</option>
                            <option value="partial">First 50 Lines</option>
                        </select>

                        <button type="submit" class="Blog">Save</button>
                    </form>
                </div>

                <h2>Stories</h2>
                <br>
                @forelse($blogs as $blog)
                    @php
                        $story = $blog->story;
                        $storyContent = $story->content;
                        $displayContent =
                            $blog->visibility === 'partial' ? mb_substr($storyContent, 0, 50) . '...' : $storyContent;
                    @endphp

                    <div class="story">
                        <h4>{{ $story->title }} by {{ $story->user->name }}</h4>
                        <div class="story-content">
                            <p>{{ $displayContent }}</p>
                            <p><strong>Price:</strong> ${{ number_format($blog->price, 2) }}</p>
                            <p><strong>Visibility:</strong> {{ ucfirst($blog->visibility) }}</p>

                            <form method="POST" action="{{ route('paypal.create') }}" class="d-inline-block me-2">
                                @csrf
                                <input type="hidden" name="blog_id" value="{{ $blog->id }}">
                                <button type="submit" class="Btn4">
                                    PayPal
                                    <svg class="svgIcon" viewBox="0 0 576 512">
                                        <path
                                            d="M512 80c8.8 0 16 7.2 16 16v32H48V96c0-8.8 7.2-16 16-16H512zm16 144V416c0 8.8-7.2 16-16 16H64c-8.8 0-16-7.2-16-16V224H528zM64 32C28.7 32 0 60.7 0 96V416c0 35.3 28.7 64 64 64H512c35.3 0 64-28.7 64-64V96c0-35.3-28.7-64-64-64H64zm56 304c-13.3 0-24 10.7-24 24s10.7 24 24 24h48c13.3 0 24-10.7 24-24s-10.7-24-24-24H120zm128 0c-13.3 0-24 10.7-24 24s10.7 24 24 24H360c13.3 0 24-10.7 24-24s-10.7-24-24-24H248z">
                                        </path>
                                    </svg>
                                </button>
                            </form>

                            <form method="POST" action="{{ route('cart.add') }}" class="d-inline-block">
                                @csrf
                                <input type="hidden" name="blog_id" value="{{ $blog->id }}">
                                <button type="submit" class="Btn4">
                                    Add to Cart
                                    <svg class="svgIcon" viewBox="0 0 576 512">
                                        <path
                                            d="M528.12 301.319l47.273-208A16 16 0 0 0 560 80H128l-12.75-56.87A16 16 0 0 0 99.57 8H16A16 16 0 0 0 0 24v16a16 16 0 0 0 16 16h66.3l70.2 312.2a48 48 0 1 0 58.3 19.8h214.2a48 48 0 1 0 57.8-19.6l5.4-23.9a16 16 0 0 0-15.2-19.4H183.3l-6.5-28.9h319.3a16 16 0 0 0 15.8-12.1zM192 416a32 32 0 1 1-32-32 32.036 32.036 0 0 1 32 32zm288 0a32 32 0 1 1-32-32 32.036 32.036 0 0 1 32 32z" />
                                    </svg>
                                </button>
                            </form>


                            {{-- Review Form --}}
                            @if (auth()->check())
                                <form action="{{ route('reviews.store', $blog->id) }}" method="POST"
                                    style="margin-top: 20px;">
                                    @csrf
                                    <label for="rating">Rate this blog:</label>
                                    <select name="rating" required class="form-control w-25">
                                        <option value="">--Select--</option>
                                        @for ($i = 1; $i <= 5; $i++)
                                            <option value="{{ $i }}">{{ $i }}
                                                Star{{ $i > 1 ? 's' : '' }}</option>
                                        @endfor
                                    </select>

                                    <label for="comment">Review:</label>
                                    <textarea name="comment" rows="3" class="form-control" placeholder="Write your thoughts..."></textarea>

                                    <button type="submit" class="Blog">Submit Review</button>
                                </form>
                            @endif

                            {{-- Display Reviews --}}
                            @if ($blog->reviews->count())
                                <div style="margin-top: 20px;">
                                    <h5 style="color: #fff;">Reviews:</h5>
                                    @foreach ($blog->reviews as $review)
                                        <div style="padding: 10px; margin-bottom: 10px; border-radius: 6px;" class="review">
                                            <strong>{{ $review->user->name }}</strong> —
                                            <span
                                                style="color: gold;">{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</span>
                                            <p>{{ $review->comment }}</p>
                                            <small
                                                style="color: #888;">{{ $review->created_at->diffForHumans() }}</small>
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
            <br>
        </div>
    </div>

    <script>
        document.querySelector('#story_select').addEventListener('change', function() {
            document.getElementById('story_id_input').value = this.value;
        });

        const select = document.getElementById('story_select');
        if (select.value) {
            document.getElementById('story_id_input').value = select.value;
        }

        document.addEventListener('DOMContentLoaded', function() {
            const alert = document.getElementById('successAlert');
            if (alert) {
                setTimeout(() => {
                    alert.style.transition = 'opacity 0.5s ease';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                }, 3000);
            }
        });
    </script>
</body>

</html>
