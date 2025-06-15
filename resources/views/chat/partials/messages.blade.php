<style>
    .message.bot {
    background-color: #222;
    border-left: 6px solid red;
    color: #fff;
}

</style>

@foreach ($messages as $msg)
    <div class="message {{ $msg->sender }}">
        <strong>{{ ucfirst($msg->sender) }} :</strong> {{ $msg->message }}
    </div>
@endforeach


{{--
{!! $messages->links('pagination::bootstrap-4') !!} --}}
