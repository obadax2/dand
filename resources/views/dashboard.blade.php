<!-- resources/views/dashboard.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Dashboard</title>
<style>
    body { font-family: Arial, sans-serif; background:#f0f2f5; margin:0; padding:20px; }
    header { background:#fff; padding:10px 20px; display:flex; justify-content: space-between; align-items:center; margin-bottom:20px; }
    .user-info { font-weight:bold; }
    .create-blog { background:#fff; padding:15px; border-radius:8px; margin-bottom:30px; }
    .story { background:#fff; padding:15px; border-radius:8px; margin-bottom:20px; box-shadow:0 2px 5px rgba(0,0,0,0.1); }
    h2 { margin-top:0; }
    form input[type="number"], form select, form button { padding:8px; margin-right:10px; border-radius:4px; border:1px solid #ccc; }
    .story-content { margin-top:10px; }
</style>
</head>
<body>

<header>
    <div class="user-info">
        Welcome, {{ $user->name }} | <a href="#">Profile</a> | 
        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
    </div>
</header>

<!-- Create Blog Form -->
<div class="create-blog">
    <h3>Create a New Blog</h3>
    <form method="POST" action="{{ route('blogs.create') }}">
        @csrf
        <!-- Hidden input to store selected story ID -->
        <input type="hidden" name="story_id" id="story_id_input" />

        <label for="story_select">Select Your Story:</label>
        <select id="story_select" name="story_id" required>
            <option value="">--Select Your Story--</option>
            @foreach($myStories as $story)
                <option value="{{ $story->id }}" data-title="{{ $story->title }}">{{ $story->title }}</option>
            @endforeach
        </select>

        <br/><br/>

        <label for="price">Price:</label>
        <input type="number" name="price" step="0.01" min="0" required />

        <br/><br/>

        <label for="visibility">Visibility:</label>
        <select name="visibility" required>
            <option value="full">Full Content</option>
            <option value="partial">First 50 Lines</option>
        </select>

        <br/><br/>

        <button type="submit">Create Blog</button>
    </form>
</div>



<h2>Blogs</h2>
@forelse($blogs as $blog)
    @php
        $story = $blog->story;
        $storyContent = $story->content;
        $fullContent = $storyContent;
        $displayContent = $storyContent;
        if ($blog->visibility === 'partial') {
            $displayContent = mb_substr($storyContent, 0, 50) . '...';
        }
    @endphp
    <div class="story">
        <h3>{{ $story->title }} by {{ $story->user->name }}</h3>
        <div class="story-content">
            <p class="{{ $blog->visibility }}">{{ $displayContent }}</p>
            <p><strong>Price:</strong> ${{ number_format($blog->price, 2) }}</p>
            <p><strong>Visibility:</strong> {{ ucfirst($blog->visibility) }}</p>
            <!-- Purchase Button -->
            <form method="POST" action="{{ route('paypal.create') }}">
    @csrf
    <input type="hidden" name="blog_id" value="{{ $blog->id }}">
    <button type="submit">Buy this Blog via PayPal</button>
</form>
        </div>
    </div>
@empty
    <p>No blogs available.</p>
@endforelse

<script>
    // Update hidden input when story is selected
    document.querySelector('#story_select').addEventListener('change', function() {
        document.getElementById('story_id_input').value = this.value;
    });
    // Set initial value if selected
    const select = document.getElementById('story_select');
    if (select.value) {
        document.getElementById('story_id_input').value = select.value;
    }
</script>

</body>
</html>