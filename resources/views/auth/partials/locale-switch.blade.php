<div class="login-locale">
    <div class="login-locale__control">
        <input type="checkbox" role="switch" id="login_locale_switch"
               class="login-locale__input"
               aria-label="{{ __('messages.Public Language') }}"
               data-id-url="{{ route('locale.switch', ['locale' => 'id', 'return_to' => request()->getRequestUri()]) }}"
               data-en-url="{{ route('locale.switch', ['locale' => 'en', 'return_to' => request()->getRequestUri()]) }}"
               @if(app()->getLocale() === 'en') checked @endif>
        <label class="login-locale__track" for="login_locale_switch">
            <span lang="id">ID</span>
            <span lang="en">EN</span>
        </label>
    </div>
</div>

<style>
    .login-locale { display: flex; justify-content: flex-end; margin-bottom: .5rem; }
    .login-locale__control { position: relative; width: 5rem; height: 2rem; }
    .login-locale__input {
        position: absolute; inset: 0; z-index: 2;
        width: 100%; height: 100%; margin: 0; opacity: 0; cursor: pointer;
    }
    .login-locale__track {
        position: relative; display: grid; grid-template-columns: repeat(2, 1fr);
        align-items: center; width: 100%; height: 100%; margin: 0; padding: 3px;
        border: 1px solid #cbd2dc; border-radius: 999px; background: #f0f2f6;
        cursor: pointer;
    }
    .login-locale__track::before {
        content: ""; position: absolute; top: 3px; bottom: 3px; left: 3px;
        width: calc(50% - 3px); border-radius: 999px; background: #6347f5;
        transition: transform .18s ease;
    }
    .login-locale__track span {
        position: relative; color: #596274; font-size: .7rem;
        font-weight: 700; line-height: 1; text-align: center;
    }
    .login-locale__input:not(:checked) + .login-locale__track span:first-child,
    .login-locale__input:checked + .login-locale__track span:last-child { color: #fff; }
    .login-locale__input:checked + .login-locale__track::before { transform: translateX(100%); }
    .login-locale__input:hover + .login-locale__track { border-color: #6347f5; }
    .login-locale__input:focus-visible + .login-locale__track {
        outline: 2px solid #6347f5; outline-offset: 3px;
    }
    @media (prefers-reduced-motion: reduce) {
        .login-locale__track::before { transition: none; }
    }
</style>
<script>
    document.getElementById('login_locale_switch').addEventListener('change', function () {
        window.location.assign(this.checked ? this.dataset.enUrl : this.dataset.idUrl);
    });
</script>
