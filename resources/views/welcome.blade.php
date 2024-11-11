<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Partner Managements</title>

    <!-- Font Icon -->
    {{-- <link rel="stylesheet" href="fonts/material-icon/css/material-design-iconic-font.min.css"> --}}
    <link rel="stylesheet" href="{{ asset('fonts/material-icon/css/material-design-iconic-font.min.css') }}">

    <!-- Main css -->
    {{-- <link rel="stylesheet" href="css/style.css"> --}}
    <link rel="stylesheet" href="{{ asset('css/css_auth/style.css') }}">

</head>
<body>

    {{-- @extends('adminlte::auth.passwords.reset') --}}
    <div class="main" id="section_form_auth"></div>
    <!-- JS -->
    {{-- <script src="vendor/jquery/jquery.min.js"></script> --}}
    <script src="{{ asset('js/vendor_auth/jquery/jquery.min.js') }}"></script>
    {{-- <script src="js/main.js"></script> --}}
    <script src="{{ asset('js/js_auth/main.js') }}"></script>

    <script>
        $(document).ready(function() {
            first_load()
            function first_load() {
                $('#section_form_auth').empty()
                $('#section_form_auth').append(`
                    <!-- Sing in  Form -->
                    <section class="sign-in">
                        <div class="container">
                            <div class="signin-content">
                                <div class="signin-image">
                                    <!-- <figure><img src="images/signin-image.jpg" alt="sing up image"></figure> -->
                                    <figure><img src="images/17-removebg-preview.png" alt="sing up image"></figure>
                                    <p class="signup-image-link create_account_link" style="cursor:pointer;">Create an account</p>
                                </div>
                                <div class="signin-form">
                                    <img src="{{ asset('uploads/logo/logo.png') }}" alt="" width="80">
                                    <h2 class="form-title">Login</h2>
                                    <form method="POST" class="register-form" id="login-form" action="{{ route('login') }}">
                                        <div class="form-group">
                                            <label for="your_name"><i class="zmdi zmdi-account material-icons-name"></i></label>
                                            <input type="text" name="your_name" id="your_name" placeholder="Your Name"/>
                                        </div>
                                        <div class="form-group">
                                            <label for="your_pass"><i class="zmdi zmdi-lock"></i></label>
                                            <input type="password" name="your_pass" id="your_pass" placeholder="Password"/>
                                        </div>
                                        <div class="form-group form-button">
                                            <input type="submit" name="signin" id="signin" class="form-submit" value="Log in"/>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </section>
                `).fadeIn();
            }

            $(document).on('click', '.create_account_link', function() {
                $('#section_form_auth').empty()
                $('#section_form_auth').append(`
                    <!-- Sign up form -->
                    <section class="signup">
                        <div class="container">
                            <div class="signup-content">
                                <div class="signup-form">
                                    <h2 class="form-title">Sign up</h2>
                                    <form method="POST" class="register-form" id="register-form" action="{{ route('register') }}">
                                        <div class="form-group">
                                            <label for="name"><i class="zmdi zmdi-account material-icons-name"></i></label>
                                            <input type="text" name="name" id="name" placeholder="Your Name"/>
                                        </div>
                                        <div class="form-group">
                                            <label for="email"><i class="zmdi zmdi-email"></i></label>
                                            <input type="email" name="email" id="email" placeholder="Your Email"/>
                                        </div>
                                        <div class="form-group">
                                            <label for="pass"><i class="zmdi zmdi-lock"></i></label>
                                            <input type="password" name="pass" id="pass" placeholder="Password"/>
                                        </div>
                                        <div class="form-group">
                                            <label for="re-pass"><i class="zmdi zmdi-lock-outline"></i></label>
                                            <input type="password" name="re_pass" id="re_pass" placeholder="Repeat your password"/>
                                        </div>
                                        <div class="form-group">
                                            <input type="checkbox" name="agree-term" id="agree-term" class="agree-term" />
                                            <label for="agree-term" class="label-agree-term"><span><span></span></span>I agree all statements in  <a href="#" class="term-service">Terms of service</a></label>
                                        </div>
                                        <div class="form-group form-button">
                                            <input type="submit" name="signup" id="signup" class="form-submit" value="Register"/>
                                        </div>
                                    </form>
                                </div>
                                <div class="signup-image">
                                    <figure><img src="images/7-removebg-preview.png" alt="sing up image"></figure>
                                    <p class="signup-image-link login_account_link" style="cursor:pointer;">I am already member</p>
                                </div>
                            </div>
                        </div>
                    </section>
                `).fadeIn();
            })

            $(document).on('click', '.login_account_link', function() {
                first_load()
            })
        })
    </script>
</body><!-- This templates was made by Colorlib (https://colorlib.com) -->
</html>