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

<li class="nav-item admin-locale-switch">
    <div class="form-check form-switch admin-locale-switch__control">
        <input
            class="form-check-input admin-locale-switch__input"
            type="checkbox"
            role="switch"
            id="admin_locale_switch"
            aria-label="{{ __('messages.Public Language') }}"
            data-id-url="{{ $indonesianLocaleUrl }}"
            data-en-url="{{ $englishLocaleUrl }}"
            @if($isEnglishLocale) checked @endif
        >
        <label class="admin-locale-switch__track" for="admin_locale_switch">
            <span class="admin-locale-switch__option">ID</span>
            <span class="admin-locale-switch__option">EN</span>
        </label>
    </div>
</li>

@once
    <style>
        .admin-locale-switch {
            display: flex;
            align-items: center;
            flex: 0 0 auto;
            padding: 0 .65rem;
        }

        .admin-locale-switch__control {
            position: relative;
            width: 5rem;
            height: 2rem;
            margin: 0;
            padding: 0;
        }

        .admin-locale-switch__input {
            position: absolute;
            inset: 0;
            z-index: 2;
            width: 100%;
            height: 100%;
            margin: 0;
            opacity: 0;
            cursor: pointer;
        }

        .admin-locale-switch__track {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            align-items: center;
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 3px;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, .72);
            border-radius: 999px;
            background: rgba(31, 41, 55, .28);
            box-shadow: inset 0 1px 2px rgba(17, 24, 39, .18);
            cursor: pointer;
        }

        .admin-locale-switch__track::before {
            content: "";
            position: absolute;
            top: 3px;
            bottom: 3px;
            left: 3px;
            width: calc(50% - 3px);
            border-radius: 999px;
            background: #ffffff;
            box-shadow: 0 1px 3px rgba(17, 24, 39, .24);
            transition: transform .18s ease;
        }

        .admin-locale-switch__option {
            position: relative;
            z-index: 1;
            color: rgba(255, 255, 255, .82);
            font-size: .7rem;
            font-weight: 700;
            line-height: 1;
            text-align: center;
            letter-spacing: .02em;
            transition: color .18s ease;
        }

        .admin-locale-switch__input:not(:checked) + .admin-locale-switch__track .admin-locale-switch__option:first-child,
        .admin-locale-switch__input:checked + .admin-locale-switch__track .admin-locale-switch__option:last-child {
            color: #4338ca;
        }

        .admin-locale-switch__input:checked + .admin-locale-switch__track::before {
            transform: translateX(100%);
        }

        .admin-locale-switch__input:hover + .admin-locale-switch__track {
            background: rgba(31, 41, 55, .38);
        }

        .admin-locale-switch__input:focus-visible + .admin-locale-switch__track {
            outline: 3px solid #fbbf24;
            outline-offset: 2px;
        }

        @media (max-width: 575.98px) {
            .admin-locale-switch {
                padding: 0 .35rem;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .admin-locale-switch__track::before,
            .admin-locale-switch__option {
                transition: none;
            }
        }
    </style>

    <script>
        document.addEventListener('change', function (event) {
            if (!event.target.matches('#admin_locale_switch')) {
                return;
            }

            window.location.assign(
                event.target.checked ? event.target.dataset.enUrl : event.target.dataset.idUrl
            );
        });
    </script>
@endonce
