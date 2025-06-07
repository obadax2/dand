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
        label{
            color: #fff;
        }
    </style>
</head>

<body>
    <div class="hero-section">
        <div>
            <br>
            @include('layout.nav')

            <div class="container4">
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
                                <option value="{{ $story->id }}" data-title="{{ $story->title }}">{{ $story->title }}
                                </option>
                            @endforeach
                        </select>

                        <label for="price">Price:</label><br>
                        <input type="number" name="price" step="0.01" min="0" class="form-control"
                            required>

                        <label for="visibility">Visibility:</label><br>
                        <select name="visibility" class="form-control" required>
                            <option value="full">Full Content</option>
                            <option value="partial">First 50 Lines</option>
                        </select>

                        <button type="submit" class="Blog">Create Blog</button>
                    </form>
                </div>

                {{-- Blog List --}}
                <h2>Blogs</h2>
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
                        </div>
                    </div>
                @empty
                    <p>No blogs available.</p>
                @endforelse
            </div>
        </div>
    </div>
    <script>
        // Auto-set hidden story ID input
        document.querySelector('#story_select').addEventListener('change', function() {
            document.getElementById('story_id_input').value = this.value;
        });

        const select = document.getElementById('story_select');
        if (select.value) {
            document.getElementById('story_id_input').value = select.value;
        }
    </script>
</body>

</html>
