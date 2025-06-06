<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>

<body class="register_body">
    <div class="hero-section">
        <div>
            <section class="wrapper">
                <div class="form signup">
                    <header>Signup</header>
                    <form method="POST" action="{{ route('register') }}" class="form-register">
                        @csrf
                        <input type="text" class="login-input" name="name" placeholder="Full Name" required />
                        <input type="text" class="login-input" name="username" placeholder="Username" required />
                        <div class="dob">
                            <div class="dob-inputs">
                                <input type="text" class="login-input" name="dob_day" placeholder="Day" required maxlength="2" />
                                <input type="text" class="login-input" name="dob_month" placeholder="Month" required maxlength="2" />
                                <input type="text" class="login-input" name="dob_year" placeholder="Year" required maxlength="4" />
                            </div>
                        </div>

                        <input type="email" name="email" class="login-input" placeholder="Email address" required />
                        <input type="password" name="password"class="login-input"  placeholder="Password" required />
                        <input type="password" name="password_confirmation" class="login-input" placeholder="Confirm Password" required />
                        <div class="checkbox">
                            <input type="checkbox" id="signupCheck" required />
                            <label for="signupCheck">I accept all terms & conditions</label>
                        </div>
                        <input type="submit" value="Signup" />
                    </form>
                </div>

                <div class="form login">
                    <header >Login</header>
                    <form method="POST" action="{{ route('login') }}" class="form-register">
                        @csrf
                        <input type="text" name="username" class="login-input" placeholder="Username" required  />
                        <input type="password" name="password"  class="login-input" placeholder="Password" required />
                        <a href="#" style="color: #fff" class="reset-link">Forgot password?</a>
                        <input type="submit" value="Login" />
                    </form>
                </div>
        </div>
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
