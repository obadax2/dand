<form method="POST" action="{{ route('register') }}">
    @csrf
    <div>
        <label for="name">Full Name:</label>
        <input type="text" name="name" required>
    </div>
    <div>
        <label for="username">Username:</label>
        <input type="text" name="username" required>
    </div>
    <div>
        <label for="dob_day">Day:</label>
        <input type="number" name="dob_day" min="1" max="31" required>
    </div>
    <div>
        <label for="dob_month">Month:</label>
        <input type="number" name="dob_month" min="1" max="12" required>
    </div>
    <div>
        <label for="dob_year">Year:</label>
        <input type="number" name="dob_year" min="1900" max="{{ date('Y') }}" required>
    </div>
    <div>
        <label for="email">Email:</label>
        <input type="email" name="email" required>
    </div>
    <div>
        <label for="password">Password:</label>
        <input type="password" name="password" required>
    </div>
    <div>
        <label for="password_confirmation">Confirm Password:</label>
        <input type="password" name="password_confirmation" required>
    </div>
    <button type="submit">Register</button>
</form>