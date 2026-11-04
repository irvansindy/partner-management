<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Form Registration')</title>
    <link rel="icon" type="image/png" href="{{ asset('uploads/logo/icon.png') }}">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- International telephone input -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css">
    <!-- File Input -->
    <link rel="stylesheet" href="{{ asset('css/cdn/file_input_min.css') }}">
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="{{ asset('css/cdn/leaflet.css') }}">
    <link rel="stylesheet" href="{{ asset('css/cdn/leaflet_geocoder.css') }}">

    <style>
        :root {
            --public-ink: #202638;
            --public-muted: #70798b;
            --public-line: #e3e8ef;
            --public-paper: #fff;
            --public-canvas: #f3f5f8;
            --public-primary: #6558d3;
            --public-primary-dark: #5043bd;
            --public-primary-soft: #efedff;
            --public-success: #238768;
        }

        html {
            min-height: 100%;
        }

        body {
            background: var(--public-canvas);
            color: var(--public-ink);
            font-family: 'Roboto', 'Helvetica Neue', sans-serif;
            min-height: 100vh;
        }

        .public-topbar {
            background: #fff;
            border-bottom: 1px solid var(--public-line);
            min-height: 68px;
        }

        .public-topbar-inner {
            align-items: center;
            display: flex;
            justify-content: space-between;
            min-height: 68px;
        }

        .public-brand {
            align-items: center;
            color: var(--public-ink);
            display: inline-flex;
            font-size: 15px;
            font-weight: 700;
            gap: 12px;
            text-decoration: none;
        }

        .public-brand img {
            height: 38px;
            object-fit: contain;
            width: 42px;
        }

        .public-brand-caption {
            border-left: 1px solid var(--public-line);
            color: var(--public-muted);
            font-size: 12px;
            font-weight: 500;
            margin-left: 4px;
            padding-left: 14px;
        }

        .public-header-label {
            color: var(--public-muted);
            font-size: 12px;
            font-weight: 600;
        }

        .public-header-actions,
        .public-locale {
            align-items: center;
            display: flex;
        }

        .public-header-actions { gap: 18px; }
        .public-locale { gap: 8px; }
        .public-locale__label {
            color: var(--public-muted);
            font-size: 12px;
            font-weight: 600;
        }

        .public-locale__control {
            position: relative;
            width: 5rem;
            height: 2rem;
            margin: 0;
            padding: 0;
        }

        .public-locale__input {
            position: absolute;
            inset: 0;
            z-index: 2;
            width: 100%;
            height: 100%;
            margin: 0;
            opacity: 0;
            cursor: pointer;
        }

        .public-locale__track {
            position: relative;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            align-items: center;
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 3px;
            overflow: hidden;
            border: 1px solid #cbd2dc;
            border-radius: 999px;
            background: #f0f2f6;
            cursor: pointer;
        }

        .public-locale__track::before {
            content: "";
            position: absolute;
            top: 3px;
            bottom: 3px;
            left: 3px;
            width: calc(50% - 3px);
            border-radius: 999px;
            background: var(--public-primary);
            box-shadow: 0 1px 3px rgba(32, 38, 56, .2);
            transition: transform .18s ease;
        }

        .public-locale__option {
            position: relative;
            z-index: 1;
            color: #596274;
            font-size: .7rem;
            font-weight: 700;
            line-height: 1;
            text-align: center;
            letter-spacing: .02em;
            transition: color .18s ease;
        }

        .public-locale__input:not(:checked) + .public-locale__track .public-locale__option:first-child,
        .public-locale__input:checked + .public-locale__track .public-locale__option:last-child {
            color: #fff;
        }

        .public-locale__input:checked + .public-locale__track::before {
            transform: translateX(100%);
        }

        .public-locale__input:hover + .public-locale__track {
            border-color: #9ba5b4;
        }

        .public-locale__input:focus-visible + .public-locale__track {
            outline: 3px solid var(--public-primary-dark);
            outline-offset: 2px;
        }

        .public-main {
            margin: 0 auto;
            max-width: 1240px;
            min-height: calc(100vh - 142px);
            padding: 38px 24px 52px;
        }

        .public-footer {
            border-top: 1px solid var(--public-line);
            color: var(--public-muted);
            font-size: 12px;
            padding: 18px 24px;
            text-align: center;
        }

        @media (max-width: 576px) {
            .public-topbar,
            .public-topbar-inner {
                min-height: 60px;
            }

            .public-brand img {
                height: 32px;
                width: 36px;
            }

            .public-brand-caption,
            .public-header-label,
            .public-locale__label {
                display: none;
            }

            .public-main {
                padding: 24px 14px 36px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .public-locale__track::before,
            .public-locale__option {
                transition: none;
            }
        }
    </style>
    @yield('css')
</head>
<body>
    <header class="public-topbar">
        <div class="container-fluid public-topbar-inner px-4">
            <a class="public-brand" href="/">
                <img src="{{ asset('uploads/logo/logo.png') }}" alt="Company logo">
                <span>PARTNER MANAGEMENT</span>
                <span class="public-brand-caption">{{ __('messages.Public Portal Caption') }}</span>
            </a>
            <div class="public-header-actions">
                <span class="public-header-label">{{ __('messages.Public Secure Registration') }}</span>
                <nav class="public-locale" aria-label="{{ __('messages.Public Language') }}">
                    <span class="public-locale__label">{{ __('messages.Public Language') }}</span>
                    @php
                        $isEnglishLocale = app()->getLocale() === 'en';
                        $indonesianLocaleUrl = route('locale.switch', [
                            'locale' => 'id',
                            'return_to' => request()->getRequestUri(),
                        ]);
                        $englishLocaleUrl = route('locale.switch', [
                            'locale' => 'en',
                            'return_to' => request()->getRequestUri(),
                        ]);
                    @endphp
                    <div class="form-check form-switch public-locale__control">
                        <input
                            class="form-check-input public-locale__input"
                            type="checkbox"
                            role="switch"
                            id="public_locale_switch"
                            aria-label="{{ __('messages.Public Language') }}"
                            data-id-url="{{ $indonesianLocaleUrl }}"
                            data-en-url="{{ $englishLocaleUrl }}"
                            @if($isEnglishLocale) checked @endif
                        >
                        <label class="public-locale__track" for="public_locale_switch">
                            <span class="public-locale__option">ID</span>
                            <span class="public-locale__option">EN</span>
                        </label>
                    </div>
                </nav>
            </div>
        </div>
    </header>

    <main class="public-main">
        @yield('content')
    </main>

    <footer class="public-footer">
        &copy; {{ date('Y') }} {{ __('messages.Public Copyright') }}
    </footer>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
    </script>
    <!-- Bootstrap Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Select2 -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- International telephone input -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
    <!-- File Input -->
    <script src="{{ asset('js/cdn/file_input.js') }}"></script>
    <script src="{{ asset('js/cdn/file_input_sortable.js') }}"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="{{ asset('js/cdn/leaflet_geocoder.js') }}"></script>

    <script>
        document.getElementById('public_locale_switch')?.addEventListener('change', function () {
            window.location.assign(this.checked ? this.dataset.enUrl : this.dataset.idUrl);
        });
    </script>

    @yield('js')
</body>
</html>
