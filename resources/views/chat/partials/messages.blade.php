@foreach ($messages as $msg)
    <p><strong>{{ $msg->sender }}:</strong> {{ $msg->message }}</p>
@endforeach

{{-- Use unescaped output so pagination HTML renders properly --}}
{!! $messages->links('pagination::bootstrap-4') !!}