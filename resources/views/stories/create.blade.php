<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Create a New Story</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.lineicons.com/3.0/lineicons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('style.css') }}">
</head>

<body>
    @if (session('success'))
            <div class="alert alert-success custom-alert bg-custom-success" id="successAlert">
                {{ session('success') }}
            </div>
        @endif


        @if ($errors->any())
            <div class="alert alert-danger custom-alert" id="successAlert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div>
            @include('layout.nav')
            <div class="d-flex" style="padding: 20px;">
                <div class="container3 flex-grow-1">
                    <div class="page-header mb-4">
                        <div class="d-flex justify-content-between align-items-center flex-wrap w-100">
                            <h1 class="m-0">Create a New Story</h1>
                            <div class="d-flex align-items-center gap-4 ms-auto mt-4" style="min-width: 350px;">
                                @if (isset($stories) && count($stories))
                                    <form method="POST" action="{{ route('characters.generate.images') }}"
                                        class="d-flex align-items-center mb-0">
                                        @csrf
                                        <select name="story_id" class="form-select me-2" required>
                                            <option value="" disabled selected>Select a Story</option>
                                            @foreach ($stories as $story)
                                                <option value="{{ $story->id }}">{{ $story->title }}</option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="ch">Character Images</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if (isset($characters) && count($characters))
                        <h3 class="mt-3">Generated Characters</h3>
                        <ul class="list-group mb-4">
                            @foreach ($characters as $char)
                                <li class="list-group-item">
                                    <strong>{{ $char['name'] }}</strong>: {{ $char['description'] }}
                                    @if (!empty($char['image_url']))
                                        <br>
                                        <img src="{{ asset($char['image_url']) }}" alt="Character Image" width="150"
                                            class="mt-2">
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @endif

                    @if (isset($generatedContent))
                        <h2>Generated Story Content</h2>
                        <div class="chat-box">{{ $generatedContent }}</div>

                        <h2 class="mt-4">Complete Your Story Details</h2>
                        <form method="POST" action="{{ route('stories.store') }}">
                            @csrf
                            <p for="title">Story Title:</p>
                            <input type="text" name="title" id="title" value="{{ old('title') }}" required
                                autocomplete="off">

                            <p for="genre">Genre:</p>
                            <input type="text" name="genre" id="genre" value="{{ old('genre') }}" required
                                autocomplete="off">

                            <button class="userButton" type="submit">Save Story</button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('stories.generate') }}">
                            @csrf
                            <p for="prompt">Enter your prompt:</p>
                            <textarea name="prompt" id="prompt" maxlength="2000" required>{{ old('prompt') }}</textarea>
                            <small id="charCount" class="text-muted">0 / 2000 characters</small>

                            <button class="genButton" type="submit">Generate Story</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const textarea = document.getElementById('prompt');
            const charCount = document.getElementById('charCount');

            const autoResize = (el) => {
                el.style.height = 'auto';
                el.style.height = el.scrollHeight + 'px';
            };

            if (textarea) {
                textarea.addEventListener('input', () => {
                    autoResize(textarea);
                    if (charCount) {
                        charCount.textContent = `${textarea.value.length} / 2000 characters`;
                    }
                });
            }
        });
    </script>
</body>

</html>
