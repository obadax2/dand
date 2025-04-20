<!-- resources/views/auth/verify_email.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Verify Your Email</h1>
    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif
    <form action="{{ route('email.verify') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="verification_code">Verification Code</label>
            <input type="text" name="verification_code" id="verification_code" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Verify Email</button>
    </form>
</div>
@endsection