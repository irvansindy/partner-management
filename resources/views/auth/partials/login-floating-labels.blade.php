<script>
    document.addEventListener('DOMContentLoaded', function () {
        var inputs = document.querySelectorAll('.login-box .form-group .form-control');

        function syncFloatingLabel(input) {
            var group = input.closest('.form-group');
            var label = group ? group.querySelector('label') : null;

            if (!label) {
                return;
            }

            var hasValue = input.value.trim().length > 0 || input.matches(':focus');
            label.classList.toggle('active', hasValue);
            group.classList.toggle('has-value', hasValue);
        }

        inputs.forEach(function (input) {
            ['focus', 'blur', 'input', 'change'].forEach(function (eventName) {
                input.addEventListener(eventName, function () {
                    syncFloatingLabel(input);
                });
            });

            syncFloatingLabel(input);
        });

        window.addEventListener('pageshow', function () {
            inputs.forEach(syncFloatingLabel);
        });
    });
</script>
