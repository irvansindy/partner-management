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

    @stack('scripts')
</body>
</html>