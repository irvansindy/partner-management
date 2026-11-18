@once
<style>
    .password-visibility-field { position: relative; }
    #profilePasswordForm .password-visibility-field > .form-control,
    #form_user_password .password-visibility-field > .form-control {
        box-sizing: border-box; width: 100%; height: 48px; min-height: 48px;
        padding-right: 52px; margin-bottom: 0;
    }
    .password-visibility-toggle {
        position: absolute; right: 4px; top: 0; width: 44px; height: 48px;
        display: inline-flex; align-items: center; justify-content: center;
        border: 0; border-radius: 4px; background: transparent; color: #6558d3; cursor: pointer;
    }
    .password-visibility-toggle:hover { background: #efedff; }
    .password-visibility-field > .form-control.is-invalid { background-image: none; }
    .password-visibility-toggle:focus-visible { outline: 2px solid #6558d3; outline-offset: -3px; }
</style>
<script>
    (function () {
        const showLabel = @json(__('user_password.show'));
        const hideLabel = @json(__('user_password.hide'));
        function setVisible(button, visible) {
            const input = document.getElementById(button.dataset.passwordTarget);
            if (!input) return;
            input.type = visible ? 'text' : 'password';
            button.setAttribute('aria-pressed', String(visible));
            button.setAttribute('aria-label', visible ? hideLabel : showLabel);
            button.title = visible ? hideLabel : showLabel;
            button.querySelector('i').className = visible ? 'fas fa-eye-slash' : 'fas fa-eye';
        }
        document.addEventListener('click', function (event) {
            const button = event.target.closest('[data-password-target]');
            if (!button) return;
            setVisible(button, button.getAttribute('aria-pressed') !== 'true');
        });
        document.addEventListener('reset', function (event) {
            event.target.querySelectorAll('[data-password-target]').forEach(function (button) {
                setVisible(button, false);
            });
        });
    })();
</script>
@endonce
