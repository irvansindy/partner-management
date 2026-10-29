<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Registrasi Berhasil | Partner Management</title>
    <link rel="icon" type="image/png" href="{{ asset('uploads/logo/icon.png') }}">
    <style>
        :root {
            color-scheme: light;
            --canvas: #f4f6f9;
            --ink: #202638;
            --muted: #667085;
            --line: #dfe4eb;
            --paper: #ffffff;
            --primary: #6558d3;
            --primary-dark: #5043bd;
            --success: #18794e;
            --success-soft: #e7f4ed;
        }

        * { box-sizing: border-box; }
        html { min-height: 100%; }

        body {
            background: var(--canvas);
            color: var(--ink);
            display: grid;
            font-family: Roboto, "Helvetica Neue", Arial, sans-serif;
            grid-template-rows: auto 1fr auto;
            margin: 0;
            min-height: 100vh;
        }

        .success-header {
            background: var(--paper);
            border-bottom: 1px solid var(--line);
        }

        .success-header__inner {
            align-items: center;
            display: flex;
            justify-content: space-between;
            margin: 0 auto;
            max-width: 1180px;
            min-height: 72px;
            padding: 0 28px;
        }

        .success-brand {
            align-items: center;
            display: flex;
            gap: 12px;
        }

        .success-brand img {
            height: 38px;
            object-fit: contain;
            width: 42px;
        }

        .success-brand strong {
            font-size: 15px;
            letter-spacing: 0.01em;
        }

        .success-header__label {
            color: var(--muted);
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.08em;
        }

        .success-main {
            align-items: center;
            display: grid;
            gap: 72px;
            grid-template-columns: minmax(0, 1.15fr) minmax(340px, 0.85fr);
            margin: 0 auto;
            max-width: 1120px;
            padding: 72px 28px 84px;
            width: 100%;
        }

        .success-kicker {
            align-items: center;
            color: var(--success);
            display: inline-flex;
            font-size: 12px;
            font-weight: 800;
            gap: 10px;
            letter-spacing: 0.1em;
            margin: 0 0 22px;
        }

        .success-kicker__mark {
            align-items: center;
            background: var(--success-soft);
            border: 1px solid #b8dfca;
            border-radius: 8px;
            display: inline-flex;
            height: 34px;
            justify-content: center;
            width: 34px;
        }

        .success-kicker__mark svg { height: 18px; width: 18px; }

        .success-title {
            font-size: clamp(36px, 5vw, 58px);
            font-weight: 700;
            letter-spacing: -0.035em;
            line-height: 1.08;
            margin: 0;
            max-width: 720px;
        }

        .success-lead {
            color: var(--muted);
            font-size: 18px;
            line-height: 1.7;
            margin: 26px 0 0;
            max-width: 650px;
        }

        .success-close {
            background: var(--primary);
            border: 1px solid var(--primary);
            border-radius: 8px;
            color: #ffffff;
            cursor: pointer;
            font: inherit;
            font-weight: 700;
            margin-top: 32px;
            min-height: 46px;
            padding: 11px 20px;
            transition: background-color 150ms ease, border-color 150ms ease;
        }

        .success-close:hover {
            background: var(--primary-dark);
            border-color: var(--primary-dark);
        }

        .success-close:focus-visible {
            outline: 3px solid rgba(101, 88, 211, 0.3);
            outline-offset: 3px;
        }

        .success-close-hint {
            color: var(--muted);
            font-size: 13px;
            line-height: 1.55;
            margin: 14px 0 0;
            max-width: 560px;
        }

        .success-panel {
            background: var(--paper);
            border: 1px solid var(--line);
            border-radius: 12px;
            padding: 28px;
        }

        .success-status {
            align-items: center;
            border-bottom: 1px solid var(--line);
            display: flex;
            justify-content: space-between;
            padding-bottom: 24px;
        }

        .success-status__label {
            color: var(--muted);
            font-size: 14px;
            font-weight: 600;
        }

        .success-status__value {
            align-items: center;
            color: var(--success);
            display: inline-flex;
            font-size: 14px;
            font-weight: 800;
            gap: 8px;
        }

        .success-status__dot {
            background: var(--success);
            border-radius: 50%;
            height: 8px;
            width: 8px;
        }

        .success-panel h2 {
            font-size: 17px;
            margin: 26px 0 20px;
        }

        .success-steps {
            display: grid;
            gap: 18px;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .success-step {
            display: grid;
            gap: 12px;
            grid-template-columns: 30px 1fr;
        }

        .success-step__number {
            align-items: center;
            border: 1px solid var(--line);
            border-radius: 7px;
            color: var(--primary-dark);
            display: flex;
            font-size: 12px;
            font-weight: 800;
            height: 30px;
            justify-content: center;
        }

        .success-step strong {
            display: block;
            font-size: 14px;
            margin: 1px 0 4px;
        }

        .success-step p {
            color: var(--muted);
            font-size: 13px;
            line-height: 1.55;
            margin: 0;
        }

        .success-footer {
            border-top: 1px solid var(--line);
            color: var(--muted);
            font-size: 12px;
            padding: 20px 28px;
            text-align: center;
        }

        @media (max-width: 720px) {
            .success-header__inner { min-height: 62px; padding: 0 18px; }
            .success-brand img { height: 32px; width: 36px; }
            .success-header__label { display: none; }
            .success-main {
                align-items: start;
                gap: 38px;
                grid-template-columns: 1fr;
                padding: 46px 18px 54px;
            }
            .success-title { font-size: 38px; }
            .success-lead { font-size: 16px; }
            .success-panel { padding: 22px; }
            .success-close { width: 100%; }
        }

        @media (prefers-reduced-motion: reduce) {
            .success-close { transition: none; }
        }
    </style>
</head>
<body>
    <header class="success-header">
        <div class="success-header__inner">
            <div class="success-brand">
                <img src="{{ asset('uploads/logo/logo.png') }}" alt="Pralon">
                <strong>PARTNER MANAGEMENT</strong>
            </div>
            <span class="success-header__label">SECURE REGISTRATION</span>
        </div>
    </header>

    <main class="success-main">
        <section aria-labelledby="success-title">
            <p class="success-kicker">
                <span class="success-kicker__mark" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12.5 9.2 17 19 7"></path>
                    </svg>
                </span>
                REGISTRASI SELESAI
            </p>
            <h1 class="success-title" id="success-title">Data registrasi telah kami terima.</h1>
            <p class="success-lead">
                Terima kasih telah melengkapi formulir Partner Management. Data Anda sudah masuk ke sistem dan akan
                ditinjau oleh tim terkait.
            </p>
            <button class="success-close" id="close-success-page" type="button">Tutup halaman</button>
            <p class="success-close-hint" id="close-success-hint" hidden role="status">
                Browser tidak mengizinkan halaman ini ditutup otomatis. Silakan tutup tab melalui tombol pada browser.
            </p>
        </section>

        <aside class="success-panel" aria-labelledby="next-step-title">
            <div class="success-status">
                <span class="success-status__label">Status pengajuan</span>
                <span class="success-status__value">
                    <span class="success-status__dot" aria-hidden="true"></span>
                    Terkirim
                </span>
            </div>
            <h2 id="next-step-title">Proses selanjutnya</h2>
            <ol class="success-steps">
                <li class="success-step">
                    <span class="success-step__number" aria-hidden="true">1</span>
                    <div>
                        <strong>Peninjauan data</strong>
                        <p>Tim kami memeriksa kelengkapan informasi dan dokumen yang dikirimkan.</p>
                    </div>
                </li>
                <li class="success-step">
                    <span class="success-step__number" aria-hidden="true">2</span>
                    <div>
                        <strong>Informasi lanjutan</strong>
                        <p>PIC terkait akan menghubungi Anda jika diperlukan klarifikasi atau dokumen tambahan.</p>
                    </div>
                </li>
            </ol>
        </aside>
    </main>

    <footer class="success-footer">
        &copy; {{ date('Y') }} Partner Management. All rights reserved.
    </footer>

    <script>
        document.getElementById('close-success-page').addEventListener('click', function () {
            window.close();
            window.setTimeout(function () {
                document.getElementById('close-success-hint').hidden = false;
            }, 250);
        });
    </script>
</body>
</html>
