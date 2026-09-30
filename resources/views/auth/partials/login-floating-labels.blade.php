<script>
    document.addEventListener('DOMContentLoaded', function () {
        var inputs = document.querySelectorAll('.login-box .form-group .form-control');

        function syncFloatingLabel(input) {
            var group = input.closest('.form-group');
            var label = group ? group.querySelector('label') : null;

            if (label) {
                label.classList.toggle('active', input.matches(':focus') || input.value.length > 0);
            }
        }

        inputs.forEach(function (input) {
            input.addEventListener('focus', function () {
                syncFloatingLabel(input);
            });
            input.addEventListener('blur', function () {
                syncFloatingLabel(input);
            });
            input.addEventListener('input', function () {
                syncFloatingLabel(input);
            });

            syncFloatingLabel(input);
        });

        window.addEventListener('pageshow', function () {
            inputs.forEach(syncFloatingLabel);
        });
    });
</script>
