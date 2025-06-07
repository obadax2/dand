<h2>Select a Story</h2>
<form action="{{ route('chat.start') }}" method="POST">
    @csrf
    <select name="story_id" required>
        @foreach ($stories as $story)
            <option value="{{ $story->id }}">{{ $story->title }}</option>
        @endforeach
    </select>
    <button type="submit">Start Chat</button>
</form>