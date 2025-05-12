<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Create a New Story</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        /* Header styling */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            position: relative;
        }
        /* Search bar styling */
        .search-container {
            flex: 1;
            max-width: 300px;
            position: relative;
        }
        .search-container input[type="text"] {
            width: 100%;
            padding: 8px;
            font-size: 16px;
        }
        /* Button styling for header buttons and links */
        .header-buttons {
            margin-left: 20px;
        }
        .header-buttons a {
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
        }
        .header-buttons a:hover {
            background-color: #0056b3;
        }
        /* Style for header links container */
        .header-links {
            display: flex;
            align-items: center;
            gap: 10px; /* space between buttons */
        }
        /* Links styled as buttons for consistency */
        .header-links a {
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
        }
        .header-links a:hover {
            background-color: #0056b3;
        }
        /* Optional icon for links */
        .header-links a::before {
            content: '📄'; /* Unicode character for a document/page */
            margin-right: 5px;
        }
        /* Styles for search results dropdown */
        #search-results {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: #fff;
            border: 1px solid #ccc;
            max-height: 250px;
            overflow-y: auto;
            display: none;
            z-index: 1000;
        }
        #search-results div {
            padding: 8px;
            cursor: pointer;
        }
        #search-results div:hover {
            background-color: #f1f1f1;
        }
        /* Other styles remain unchanged */
        form { max-width: 600px; margin: auto; }
        textarea { width: 100%; height: 200px; }
        input[type="text"] { width: 100%; padding: 8px; margin-bottom: 10px; }
        button { padding: 10px 20px; margin-top: 10px; cursor: pointer; }
        .success { color: green; }
        .error { color: red; }
        .chat-box {
            border: 1px solid #ccc;
            padding: 15px;
            margin-bottom: 20px;
            background-color: #f9f9f9;
            white-space: pre-wrap;
            word-wrap: break-word;
            max-height: 400px;
            overflow-y: auto;
        }
    </style>
</head>
<body>

    <!-- Header with search bar and links -->
    <div class="header" style="position: relative;">
        <!-- Search Bar -->
        <div class="search-container" style="position: relative;">
            <input type="text" id="user-search" placeholder="Search users..." autocomplete="off" />
            <div id="search-results"></div>
        </div>
        <!-- Links including Drafts, User Profile, and Blog Index -->
        <div class="header-links">
            {{-- Link to the drafts page --}}
            <a href="{{ route('stories.drafts') }}">Drafts</a>
            <!-- Current User Profile Button -->
           <a href="{{ route('user.profile') }}">{{ Auth::user()->name }}</a>
            <!-- Blog Index Button -->
            <a href="{{ url('blogindex.php') }}">Blog Index</a>
        </div>
    </div>

    <h1>Create a New Story</h1>

    @if(session('success'))
        <p class="success">{{ session('success') }}</p>
    @endif

    @if(session('error'))
        <p class="error">{{ session('error') }}</p>
    @endif

    @if($errors->any())
        <div class="error">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Show generated content if exists --}}
    @if(isset($generatedContent))
        <h2>Generated Story Content:</h2>
        <div class="chat-box">
            {{ $generatedContent }}
        </div>

        <h2>Complete Your Story Details</h2>
        <form method="POST" action="{{ route('stories.store') }}">
            @csrf
            <label for="title">Story Title:</label><br>
            <input type="text" name="title" id="title" value="{{ old('title') }}" required><br><br>

            <label for="genre">Genre:</label><br>
            <input type="text" name="genre" id="genre" value="{{ old('genre') }}" required><br><br>

            <button type="submit">Save Story</button>
        </form>
    @else
        {{-- Show the initial prompt form --}}
        <form method="POST" action="{{ route('stories.generate') }}">
            @csrf
            <label for="prompt">Enter your prompt:</label><br>
            <textarea name="prompt" id="prompt" required>{{ old('prompt') }}</textarea><br>
            <button type="submit">Generate Story</button>
        </form>
    @endif

    <!-- Your other page content -->

    <!-- Place the JavaScript just before closing </body> -->
    <script>
  const routeFollow = "{{ url('/follow') }}/";
  const routeUnfollow = "{{ url('/unfollow') }}/";
  const routeSendRequest = "{{ url('/friends/request') }}/";

  document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('user-search');
    const resultsDiv = document.getElementById('search-results');

    searchInput.addEventListener('keyup', function() {
      const query = this.value.trim();

      if (query.length === 0) {
        resultsDiv.style.display = 'none';
        resultsDiv.innerHTML = '';
        return;
      }

      fetch(`{{ route('users.ajaxSearch') }}?query=` + encodeURIComponent(query))
        .then(response => response.json())
        .then(data => {
          resultsDiv.innerHTML = '';

          if (data.length === 0) {
            resultsDiv.style.display = 'none';
            return;
          }

          data.forEach(user => {
            const div = document.createElement('div');
            div.style.padding = '8px';
            div.style.cursor = 'pointer';

            div.innerHTML = `
              <div>
                <strong>${user.name}</strong> (${user.username})
              </div>
              <div style="margin-top: 5px;">
                <!-- Follow Button -->
                <form method="POST" action="${routeFollow}${user.id}" style="display:inline;">
                  @csrf
                  <button type="submit" style="padding:4px 8px; font-size:14px;">Follow</button>
                </form>
                <!-- Add Friend Button -->
                <form method="POST" action="${routeSendRequest}${user.id}" style="display:inline;">
                  @csrf
                  <button type="submit" style="padding:4px 8px; font-size:14px;">Add Friend</button>
                </form>
              </div>
            `;

            // Optional: handle click to select user
            div.onclick = () => {
              searchInput.value = user.name;
              resultsDiv.style.display = 'none';
            };

            resultsDiv.appendChild(div);
          });
          resultsDiv.style.display = 'block';
        });
    });
  });
</script>
</body>
</html>