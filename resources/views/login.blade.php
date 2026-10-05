<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Responsive Admin Dashboard Template">
    <meta name="keywords" content="admin,dashboard">
    <meta name="author" content="stacks">
    <meta name="msapplication-tap-highlight" content="no"/>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login — {{ config('app.name', 'Partner Management') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('uploads/pt/1713190566.jpg') }}">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.30.1/moment-with-locales.min.js" integrity="sha512-4F1cxYdMiAW98oomSLaygEwmCnIP38pb4Kx70yQYqRwLVCs3DbRumfBq82T08g/4LJ/smbFGFpmeFlQgoDccgg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{ mix('js/app.js') }}"></script>

    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Two+Tone|Material+Icons+Round|Material+Icons+Sharp" rel="stylesheet">

    <!-- Plugin Styles — hanya yang dipakai halaman login -->
    <link href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/font-awesome/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/waves/waves.min.css') }}" rel="stylesheet">

    <link href="{{ asset('assets/css/alpha.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet">

    <style>
        :root {
            --login-bg: #f4f7fb;
            --login-card: rgba(255, 255, 255, 0.96);
            --login-line: #e5e7ef;
            --login-input: #f8fafc;
            --login-primary: #6558d3;
            --login-primary-dark: #5044bf;
            --login-text: #1b2430;
            --login-muted: #697586;
            --login-error: #dc3545;
        }

        body.login-page {
            background: linear-gradient(140deg, #eef2ff 0%, #f8fafc 42%, #edf6ff 100%);
            min-height: 100vh;
        }

        .alpha-app {
            min-height: 100vh;
        }

        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 32px 16px;
        }

        .login-box {
            border: 1px solid var(--login-line);
            border-radius: 20px;
            background: var(--login-card);
            box-shadow: 0 18px 40px rgba(31, 41, 55, 0.10);
            overflow: hidden;
        }

        .login-box .card-body {
            padding: 30px 28px 24px;
        }

        .login-box .card-title {
            color: var(--login-text);
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            letter-spacing: -0.03em;
        }

        .login-box form {
            margin-top: 0;
        }

        .login-box .form-group {
            position: relative;
            margin-bottom: 1.35rem;
        }

        .login-box label {
            display: block;
            position: absolute;
            left: 14px;
            top: 13px;
            padding: 0 6px;
            margin: 0;
            background: rgba(255,255,255,0.9);
            color: var(--login-muted);
            font-size: 0.74rem;
            font-weight: 600;
            letter-spacing: 0.02em;
            transition: all 0.2s ease;
            pointer-events: none;
            z-index: 2;
        }

        .login-box label.active {
            color: var(--login-primary);
            top: -9px;
            font-size: 0.7rem;
        }

        .login-box .form-control {
            height: 52px;
            border-radius: 12px;
            border: 1px solid #dbe2ee;
            background: var(--login-input);
            color: var(--login-text);
            padding: 1.2rem 0.9rem 0.55rem;
            transition: border-color 0.2s ease, box-shadow 0.2s ease, background-color 0.2s ease;
        }

        .login-box .form-control:focus {
            background: #fff;
            border-color: var(--login-primary);
            box-shadow: 0 0 0 3px rgba(101, 88, 211, 0.12);
        }

        .login-box .form-control.is-invalid {
            background: #fff5f5;
            border-color: var(--login-error);
        }

        .login-box .invalid-feedback {
            display: block !important;
            margin-top: 0.4rem;
            font-size: 0.76rem;
            color: var(--login-error);
        }

        .login-box .form-check {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            margin-bottom: 1.25rem;
        }

        .login-box .form-check-input {
            margin-right: 0.6rem;
            width: 1.05rem;
            height: 1.05rem;
            border-radius: 0.35rem;
            border-color: #cbd5e1;
        }

        .login-box .form-check-input:checked {
            background-color: var(--login-primary);
            border-color: var(--login-primary);
            box-shadow: none;
        }

        .login-box .form-check-label {
            position: static;
            background: transparent;
            font-size: 0.9rem;
            color: var(--login-muted);
            padding: 0;
            margin: 0;
        }

        .login-box .btn-primary {
            height: 48px;
            border: 0;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--login-primary) 0%, var(--login-primary-dark) 100%);
            box-shadow: 0 12px 28px rgba(101, 88, 211, 0.24);
            font-weight: 700;
            letter-spacing: 0.02em;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .login-box .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 14px 30px rgba(101, 88, 211, 0.28);
        }

        @media (max-width: 575.98px) {
            .login-box .card-body {
                padding: 24px 18px 20px;
            }

            .login-box .card-title {
                font-size: 1.5rem;
            }
        }
    </style>

    <link href="{{ mix('css/app.css') }}" rel="stylesheet"/>

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    @stack('styles')
</head>
<body class="login-page sign-in">
    <div class="loader">
        <div class="spinner-border text-primary" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>

    <div class="alpha-app">
        <div class="container">
            <div class="login-container">
                <div class="row justify-content-center row align-items-center">
                    <div class="col-lg-4 col-md-6">
                        <div class="card login-box">
                            <div class="card-body">
                                <h5 class="card-title">Sign In</h5>

                                {{-- Tampilkan error umum (mis. "email/password salah")
                                     yang tidak terikat ke field spesifik --}}
                                @if ($errors->has('email') && session('status') === null)
                                    {{-- error per-field ditampilkan di bawah masing-masing input --}}
                                @endif

                                <form action="{{ route('login') }}" method="POST">
                                    @csrf

                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email"
                                               name="email"
                                               id="email"
                                               class="form-control @error('email') is-invalid @enderror"
                                               value="{{ old('email') }}"
                                               placeholder="Email"
                                               autofocus
                                               required>
                                        @error('email')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <input type="password"
                                               name="password"
                                               id="password"
                                               class="form-control @error('password') is-invalid @enderror"
                                               placeholder="Password"
                                               required>
                                        @error('password')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-group form-check">
                                        <input type="checkbox" class="form-check-input" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="remember">Ingat saya</label>
                                    </div>

                                    <button type="submit" class="btn btn-primary w-100">
                                        Sign In
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Javascripts — hanya yang dipakai halaman login (bukan chart/dashboard).
         alpha.min.js SENGAJA TIDAK disertakan karena script tersebut mencoba
         bind event ke elemen sidebar/topbar (mis. tombol toggle) yang tidak
         ada di halaman login — itu penyebab error "Cannot read properties of
         null (reading 'dispatchEvent')" sebelumnya. Kalau nanti ada efek
         visual dari alpha.min.js yang ternyata dibutuhkan di sini (misal show/hide
         password, animasi loader), kita ekstrak potongan kecil relevannya saja,
         bukan load seluruh file. -->
    <script src="{{ asset('assets/plugins/jquery/jquery-3.4.1.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap/popper.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/waves/waves.min.js') }}"></script>
    <script>
        // Inisialisasi efek ripple Waves secara manual, karena biasanya
        // dipanggil dari dalam alpha.min.js (yang sengaja kita skip di atas)
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof Waves !== 'undefined') {
                Waves.attach('.btn', ['waves-effect']);
                Waves.init();
            }

            // Sembunyikan loader & tambahkan class "loaded" ke body.
            // Ini biasanya dilakukan oleh alpha.min.js (yang sengaja kita
            // skip) setelah halaman selesai load. Tanpa ini, div.loader
            // akan menutupi halaman selamanya karena class "loaded" tidak
            // pernah ditambahkan.
            document.body.classList.add('loaded');
            var loader = document.querySelector('.loader');
            if (loader) {
                loader.style.display = 'none';
            }
        });
    </script>

    @include('auth.partials.login-floating-labels')

    @stack('scripts')
</body>
</html>