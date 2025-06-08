<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Submit a Complaint</title>
</head>
<body style="font-family: Arial, sans-serif; background: #f4f4f4; padding: 2rem;">
    <div style="max-width: 600px; margin: 0 auto; background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 0 8px rgba(0,0,0,0.1);">
        <h2 style="margin-bottom: 1.5rem;">Submit a Complaint</h2>

        @if(session('success'))
            <p style="color: green; font-weight: bold;">{{ session('success') }}</p>
        @endif

        <form action="{{ route('tickets.store') }}" method="POST">
            @csrf
            <div style="margin-bottom: 1rem;">
                <label for="content" style="display: block; margin-bottom: 0.5rem;">Your Complaint:</label>
                <textarea name="content" id="content" rows="5" required style="width: 100%; padding: 0.5rem; border: 1px solid #ccc; border-radius: 4px;"></textarea>
            </div>

            <button type="submit" style="background: #28a745; color: white; padding: 0.75rem 1.25rem; border: none; border-radius: 4px; cursor: pointer;">
                Submit
            </button>
        </form>
    </div>
</body>
</html>
