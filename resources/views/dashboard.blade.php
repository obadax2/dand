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
            color: #000000;
        }

        h4 {
            color: #ffffff !important;
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
        }

        .s {
            color: #ffffff;
        }

        .review {
            width: 300px;
            backdrop-filter: blur(1px);
        }

        input[name="price"] {
            all: unset;
            display: block;
            width: 20%;
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
            line-height: 1.5;
            color: #212529;
            background-color: #fff;
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        input[name="price"]:focus {
            color: #212529;
            background-color: #fff;
            outline: 0;
        }

        .form-control,
        .form-select {
            height: calc(2.25rem + 2px);
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

    @include('layout.nav')

    <div class="container my-4">
        <div class="row">
            <!-- Form Container -->
            <div class="col-md-5 mb-4">
                <div class="form-section p-3 rounded">
                    <h3 class="text-dark mb-4">Sell your story</h3>
                    <form method="POST" action="{{ route('blogs.create') }}">
                        @csrf
                        <input type="hidden" name="story_id" id="story_id_input" />
                        <div class="mb-3">
                            <label for="story_select" class="form-label">Select Your Story:</label>
                            <select id="story_select" name="story_id" class="form-select" required>
                                <option value="">Select Your Story</option>
                                @foreach ($myStories as $story)
                                    <option value="{{ $story->id }}">{{ $story->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3 row">
                            <div class="col-md-6 d-flex align-items-center">
                                <label for="price" class="form-label me-2">Price ($):</label>
                                <input type="number" name="price" step="0.01" min="0"
                                    class="form-control form-control-sm" required>
                            </div>

                            <div class="col-md-6 d-flex justify-content-center align-items-center">
                                <label for="visibility" class="form-label me-2">Visibility:</label>
                                <select name="visibility" class="form-select form-select-sm" required>
                                    <option value="full">Full</option>
                                    <option value="partial">50 char</option>
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-dark">Save</button>
                    </form>
                </div>
            </div>

            <!-- Stories Container -->
            <div class="col-md-7">
                <h2 class="text-dark mb-3">Stories</h2>
                <div style="max-height: 500px; overflow-y: auto; padding-right: 10px;">
                    @forelse($blogs as $blog)
                        @php
                            $story = $blog->story;
                            $storyContent = $story->content;
                            $displayContent =
                                $blog->visibility === 'partial'
                                    ? mb_substr($storyContent, 0, 50) . '...'
                                    : $storyContent;
                            $isFriend = in_array($blog->user_id, $friends ?? []);
                            $isFollower = in_array($blog->user_id, $following ?? []);
                        @endphp

                        @if ($isFriend || $isFollower)
                            <div class="story mb-4 p-3 border rounded">
                                <h4 style="color: white; font-weight: bold; font-style: italic;">
                                    {{ $story->title }} by {{ $story->user->name }}
                                </h4>
                                <p>{{ $displayContent }}</p>
                                <div class="story-content mb-3">
                                    <p>{{ $displayContent }}</p>
                                    <p><strong class="s">Price:</strong> ${{ number_format($blog->price, 2) }}</p>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-2 flex-wrap">

                                    <div>
                                        @if ($isFriend)
                                            <form method="POST" action="{{ route('paypal.create') }}"
                                                class="d-inline-block me-2 mb-1">
                                                @csrf
                                                <input type="hidden" name="blog_id" value="{{ $blog->id }}">
                                                <button type="submit" class="Btn4">PayPal</button>
                                            </form>

                                            <form method="POST" action="{{ route('cart.add') }}"
                                                class="d-inline-block mb-1">
                                                @csrf
                                                <input type="hidden" name="blog_id" value="{{ $blog->id }}">
                                                <button type="submit" class="Btn4">Add to Cart</button>
                                            </form>
                                        @endif
                                    </div>

                                    <div class="d-flex align-items-center gap-2 me-4">
                                        <form action="{{ route('upvotes.store', $blog->id) }}" method="POST"
                                            class="m-0 p-0">
                                            @csrf
                                            <button type="submit" class="btn p-0"
                                                style="border:none; background:none; color:#05EEFF; cursor:pointer;">
                                                <i class="fas fa-arrow-up" style="font-size: 1.2rem;"></i>
                                                <span
                                                    style="color:#05EEFF;">({{ number_format($blog->weightedUpvotes(), 1) }})</span>
                                            </button>
                                        </form>

                                        <form action="{{ route('downvotes.store', $blog->id) }}" method="POST"
                                            class="m-0 p-0">
                                            @csrf
                                            <button type="submit" class="btn p-0"
                                                style="border:none; background:none; color:#ff4d4d; cursor:pointer;">
                                                <i class="fas fa-arrow-down" style="font-size: 1.2rem;"></i>
                                                <span
                                                    style="color:#ff4d4d;">({{ number_format($blog->weightedDownvotes(), 1) }})</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                @if (auth()->check() && auth()->id() !== $blog->user_id && ($isFriend || $isFollower))
                                    <form action="{{ route('reviews.store', $blog->id) }}" method="POST"
                                        style="margin-top: 20px;">
                                        @csrf
                                        <p for="rating" style="color: #fff">Rate this story:</p>
                                        <div class="star-rating"
                                            style="font-size: 24px; color: #ffd000; cursor: pointer;"
                                            data-input-name="rating_{{ $blog->id }}">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <i class="far fa-star" data-value="{{ $i }}"></i>
                                            @endfor
                                            <input type="hidden" name="rating_{{ $blog->id }}" required />
                                        </div>

                                        <p for="comment" class="mt-2" style="color: #fff">Review:</p>
                                        <textarea name="comment" rows="3" class="form-control" placeholder="Write your thoughts..."></textarea>

                                        <button type="submit" class="btn btn-dark">Submit review</button>
                                    </form>
                                @endif

                                {{-- Display Reviews --}}
                                @if ($blog->reviews->count())
                                    <div style="margin-top: 20px;">
                                        <h5 style="color: #fff;">Reviews:</h5>
                                        @foreach ($blog->reviews as $review)
                                            <div style="padding: 10px; margin-bottom: 10px; border-radius: 6px;"
                                                class="review">
                                                <strong class="s">{{ $review->user->name }}</strong>
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
                        @endif
                    @empty
                        <p>No stories available.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.star-rating').forEach(container => {
                const stars = container.querySelectorAll('i');
                const input = container.querySelector('input[type=hidden]');

                stars.forEach(star => {
                    star.addEventListener('click', () => {
                        const value = parseInt(star.getAttribute('data-value'));

                        // Clear all stars
                        stars.forEach(s => s.className = 'far fa-star');

                        // Fill selected stars
                        stars.forEach((s, index) => {
                            if (index < value) {
                                s.className = 'fas fa-star';
                            }
                        });

                        // Set hidden input value
                        input.value = value;
                    });
                });
            });
        });
    </script>
</body>

</html>
