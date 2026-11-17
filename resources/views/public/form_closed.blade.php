<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.Public Form Closed Page Title') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('uploads/logo/icon.png') }}">
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; background: #f3f5f8; color: #202638; font-family: 'Roboto', 'Helvetica Neue', Arial, sans-serif; line-height: 1.6; }
        .status-header { background: #fff; border-bottom: 1px solid #e3e8ef; }
        .status-header-inner { max-width: 1120px; margin: auto; padding: 20px 24px; font-size: 15px; font-weight: 700; }
        main { min-height: calc(100vh - 65px); display: grid; align-items: center; padding: 48px 24px 96px; }
        .status-panel { width: 100%; max-width: 580px; margin: auto; background: #fff; border: 1px solid #e3e8ef; border-radius: 12px; padding: 40px; }
        .status-icon { display: flex; align-items: center; justify-content: center; width: 48px; height: 48px; margin-bottom: 24px; border-radius: 10px; background: #efedff; color: #6558d3; }
        h1 { margin: 0 0 12px; font-size: clamp(24px, 4vw, 30px); line-height: 1.3; font-weight: 600; letter-spacing: -.4px; }
        .status-message { margin: 0; color: #596274; font-size: 16px; overflow-wrap: anywhere; }
        .status-help { margin: 24px 0 0; padding-top: 24px; border-top: 1px solid #e3e8ef; color: #70798b; font-size: 14px; }
        .status-home { display: inline-flex; align-items: center; gap: 8px; min-height: 44px; margin-top: 24px; color: #5043bd; font-size: 14px; font-weight: 600; text-decoration: none; }
        .status-home:hover { text-decoration: underline; }
        .status-home:focus-visible { outline: 2px solid #6558d3; outline-offset: 5px; border-radius: 2px; }
        @media (max-width: 575px) {
            main { padding: 32px 16px; align-items: start; }
            .status-panel { padding: 28px 24px; }
            .status-header-inner { padding: 18px 24px; }
        }
    </style>
</head>
<body>
    <header class="status-header">
        <div class="status-header-inner">{{ config('app.name', 'Partner Management') }}</div>
    </header>
    <main>
        <section class="status-panel" aria-labelledby="status-title">
            <div class="status-icon" aria-hidden="true">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    <path d="M14 2v6h6M8 13h8M8 17h5"/>
                </svg>
            </div>
            <h1 id="status-title">{{ __('messages.Public Form Closed') }}</h1>
            <p class="status-message">{{ $message }}</p>
            <p class="status-help">{{ __('public_form.closed_contact_help') }}</p>
            <a href="{{ url('/') }}" class="status-home">
                <svg aria-hidden="true" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="m12 19-7-7 7-7M5 12h14"/></svg>
                {{ __('messages.Public Go Home') }}
            </a>
        </section>
    </main>
</body>
</html>
