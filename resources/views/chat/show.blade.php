<h2>Chat about: {{ $story->title }}</h2>

<div>
    <h4>Characters (count: {{ $story->characters->count() }})</h4>
    @if($story->characters->isEmpty())
        <p>No characters found.</p>
    @endif
    <ul>
        @foreach ($story->characters as $char)
            <li>{{ $char->name }}: {{ $char->description }}</li>
        @endforeach
    </ul>
    @if ($story->map)
        <p>Map Title: {{ $story->map->title }}</p>
        <p>Map Description: {{ $story->map->description }}</p>
    @endif
</div>

<!-- ONLY messages and pagination inside here -->
<div id="messages-container">
    @include('chat.partials.messages', ['messages' => $messages])
</div>

<!-- SEND MESSAGE FORM OUTSIDE messages container! -->
<form id="chat-form" action="{{ route('chat.send', $conversation) }}" method="POST">
    @csrf
    <input type="text" name="message" required autocomplete="off" placeholder="Type your message...">
    <button type="submit">Send</button>
</form>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(function() {
    // AJAX Pagination click handler
    $(document).on('click', '#messages-container .pagination a', function(e) {
        e.preventDefault();
        let url = $(this).attr('href');

        $.ajax({
            url: url,
            dataType: 'html',
            success: function(data) {
                $('#messages-container').html(data);
            },
            error: function() {
                alert('Could not load messages.');
            }
        });
    });

    // AJAX send message handler
    $('#chat-form').submit(function(e) {
        e.preventDefault();
        let form = $(this);

        $.post(form.attr('action'), form.serialize())
            .done(function(response) {
                if (response.messages_html) {
                    $('#messages-container').html(response.messages_html);
                }
                form.find('input[name=message]').val('').focus();
            })
            .fail(function() {
                alert('Failed to send message.');
            });
    });
});
</script>
