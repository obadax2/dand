<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <div class="hero-section">
        <div class="container">
            @include('layout.nav')
            <div class="typing-container">
                <div class="line-one">Your website for</div>
                <div class="line-two">
                    generating a <span id="typed-text"></span><span class="cursor">|</span>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="typing-container">
            <div class="line-one">Your website for</div>
            <div class="line-two">
                generating a <span id="typed-text"></span><span class="cursor">|</span>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="typing-container">
            <div class="line-one">Your website for</div>
            <div class="line-two">
                generating a <span id="typed-text"></span><span class="cursor">|</span>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="typing-container">
            <div class="line-one">Your website for</div>
            <div class="line-two">
                generating a <span id="typed-text"></span><span class="cursor">|</span>
            </div>
        </div>
    </div>



    {{-- <div class="card1">
            <div class="card-inner1">
                <div class="card-front1">
                    <p>Front Side</p>
                </div>
                <div class="card-back1">
                    <p>Back Side</p>
                </div>
            </div>
        </div> --}}


    </div>
</body>
<script>
    const options = ["Story", "Map", "Character"];
    const typedText = document.getElementById("typed-text");
    let optionIndex = 0;
    let charIndex = 0;
    let deleting = false;

    function typeLoop() {
        const currentOption = options[optionIndex];
        if (!deleting) {
            typedText.textContent = currentOption.substring(0, charIndex + 1);
            charIndex++;
            if (charIndex === currentOption.length) {
                deleting = true;
                setTimeout(typeLoop, 2000); // Pause before deleting
                return;
            }
        } else {
            typedText.textContent = currentOption.substring(0, charIndex - 1);
            charIndex--;
            if (charIndex === 0) {
                deleting = false;
                optionIndex = (optionIndex + 1) % options.length;
            }
        }
        setTimeout(typeLoop, deleting ? 80 : 150);
    }

    document.addEventListener("DOMContentLoaded", typeLoop);
</script>


</html>
