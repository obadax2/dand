<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="style2.css">
</head>

<body>
    <section class="wrapper">
        <div class="form signup">
            <header>Signup</header>
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <input type="text" name="name" placeholder="Full Name" required />
                <input type="text" name="username" placeholder="Username" required />
                <div class="dob">
                    <div class="dob-inputs">
                        <input type="text" name="dob_day" placeholder="Day" required maxlength="2" />
                        <input type="text" name="dob_month" placeholder="Month" required maxlength="2" />
                        <input type="text" name="dob_year" placeholder="Year" required maxlength="4" />
                    </div>
                </div>

                <input type="email" name="email" placeholder="Email address" required />
                <input type="password" name="password" placeholder="Password" required />
                <input type="password" name="password_confirmation" placeholder="Confirm Password" required />
                <div class="checkbox">
                    <input type="checkbox" id="signupCheck" required />
                    <label for="signupCheck">I accept all terms & conditions</label>
                </div>
                <input type="submit" value="Signup" />
            </form>
        </div>

        <div class="form login">
            <header>Login</header>
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <input type="text" name="username" placeholder="Username" required />
                <input type="password" name="password" placeholder="Password" required />
                <a href="#">Forgot password?</a>
                <input type="submit" value="Login" />
            </form>
        </div>


        <script>
            const wrapper = document.querySelector(".wrapper"),
                signupHeader = document.querySelector(".signup header"),
                loginHeader = document.querySelector(".login header");
            loginHeader.addEventListener("click", () => {
                wrapper.classList.add("active");
            });
            signupHeader.addEventListener("click", () => {
                wrapper.classList.remove("active");
            });
        </script>
    </section>

</body>

</html>
