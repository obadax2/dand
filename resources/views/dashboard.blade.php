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
        /* your existing styles */
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
                                                    <select name="visibility" class="form-select form-select-sm"
                                                        required>
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

                                                // Define friendship/follow conditions
                                                $isFriend = in_array($blog->user_id, $friends ?? []);
                                                $isFollower = in_array($blog->user_id, $following ?? []);
                                            @endphp

                                            {{-- Optional: hide entire blog if not friend or follower --}}
                                            @if ($isFriend || $isFollower)
                                                <div class="mb-4 p-3 border rounded bg-light">
                                                    <h4 class="text-dark">{{ $story->title }}</h4>
                                                    <p>{{ $displayContent }}</p>

                                                    {{-- Show Buy/Add to Cart ONLY if Friend --}}
                                                    @if ($isFriend)
                                                        <form method="POST" action="{{ route('paypal.create') }}"
                                                            class="d-inline-block me-2">
                                                            @csrf
                                                            <input type="hidden" name="blog_id"
                                                                value="{{ $blog->id }}">
                                                            <button type="submit" class="Btn4">
                                                                PayPal
                                                                <svg class="svgIcon" viewBox="0 0 576 512">
                                                                    <path
                                                                        d="M512 80c8.8 0 16 7.2 16 16v32H48V96c0-8.8 7.2-16 16-16H512zm16 144V416c0 8.8-7.2 16-16 16H64c-8.8 0-16-7.2-16-16V224H528zM64 32C28.7 32 0 60.7 0 96V416c0 35.3 28.7 64 64 64H512c35.3 0 64-28.7 64-64V96c0-35.3-28.7-64-64-64H64zm56 304c-13.3 0-24 10.7-24 24s10.7 24 24 24h48c13.3 0 24-10.7 24-24s-10.7-24-24-24H120zm128 0c-13.3 0-24 10.7-24 24s10.7 24 24 24H360c13.3 0 24-10.7 24-24s-10.7-24-24-24H248z" />
                                                                </svg>
                                                            </button>
                                                        </form>

                                                        <form method="POST" action="{{ route('cart.add') }}"
                                                            class="d-inline-block">
                                                            @csrf
                                                            <input type="hidden" name="blog_id"
                                                                value="{{ $blog->id }}">
                                                            <button type="submit" class="Btn4">
                                                                Add to Cart
                                                                <svg class="svgIcon" viewBox="0 0 576 512">
                                                                    <path
                                                                        d="M528.12 301.319l47.273-208A16 16 0 0 0 560 80H128l-12.75-56.87A16 16 0 0 0 99.57 8H16A16 16 0 0 0 0 24v16a16 16 0 0 0 16 16h66.3l70.2 312.2a48 48 0 1 0 58.3 19.8h214.2a48 48 0 1 0 57.8-19.6l5.4-23.9a16 16 0 0 0-15.2-19.4H183.3l-6.5-28.9h319.3a16 16 0 0 0 15.8-12.1zM192 416a32 32 0 1 1-32-32 32.036 32.036 0 0 1 32 32zm288 0a32 32 0 1 1-32-32 32.036 32.036 0 0 1 32 32z" />
                                                                </svg>
                                                            </button>
                                                        </form>
                                                    @endif

                                                    {{-- Upvote Button --}}
                                                    <form action="{{ route('upvotes.store', $blog->id) }}"
                                                        method="POST" class="d-inline-block me-2 mt-2">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-arrow-up"></i> Upvote
                                                            ({{ number_format($blog->weightedUpvotes(), 1) }})
                                                        </button>
                                                    </form>

                                                    {{-- Downvote Button --}}
                                                    <form action="{{ route('downvotes.store', $blog->id) }}"
                                                        method="POST" class="d-inline-block mt-2">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                                            <i class="fas fa-arrow-down"></i> Downvote
                                                            ({{ number_format($blog->weightedDownvotes(), 1) }})
                                                        </button>
                                                    </form>

                                                    {{-- Show Review Form if NOT owner AND friend or follower --}}
                                                    @if (auth()->check() && auth()->id() !== $blog->user_id && ($isFriend || $isFollower))
                                                        <form action="{{ route('reviews.store', $blog->id) }}"
                                                            method="POST" style="margin-top: 20px;">
                                                            @csrf
                                                            <p style="color:black;">Add a review</p>
                                                            <div class="mb-3">
                                                                <label for="rating"
                                                                    class="form-label">Rating</label>
                                                                <select name="rating" id="rating"
                                                                    class="form-select" required>
                                                                    <option value="">Select a rating</option>
                                                                    @for ($i = 1; $i <= 5; $i++)
                                                                        <option value="{{ $i }}">
                                                                            {{ $i }}
                                                                            Star{{ $i > 1 ? 's' : '' }}</option>
                                                                    @endfor
                                                                </select>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="description"
                                                                    class="form-label">Review</label>
                                                                <textarea name="description" id="description" class="form-control" rows="2"
                                                                    placeholder="Write your review..." required></textarea>
                                                            </div>
                                                            <button type="submit"
                                                                class="btn btn-primary btn-sm">Submit Review</button>
                                                        </form>
                                                    @endif

                                                    {{-- Show existing reviews --}}
                                                    <h5 class="mt-3 text-dark">Reviews</h5>
                                                    @foreach ($blog->reviews as $review)
                                                        <div class="border p-2 mb-2 rounded bg-white"
                                                            style="color: black;">
                                                            <strong>{{ $review->user->name }}</strong> rated
                                                            <strong>{{ $review->rating }}/5</strong>
                                                            <p>{{ $review->description }}</p>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        @empty
                                            <p>No stories available.</p>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Optional: script to fade out alerts after 3 seconds
        setTimeout(() => {
            const alert = document.getElementById('successAlert');
            if (alert) {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            }
        }, 3000);
    </script>
</body>

</html>
