<script>
    window.PartnerAttachments = {
        reset: function (input) {
            if (input.data('fileinput')) {
                input.fileinput('clear');
            }
            input.val('');
        },
        append: function (formData, input) {
            formData.delete('input-multiple-file');
            formData.delete('input-multiple-file[]');
            const files = input.data('fileinput')
                ? input.fileinput('getFileList')
                : Array.from(input[0]?.files || []);
            files.forEach(function (file) {
                formData.append('input-multiple-file[]', file, file.name);
            });
        },
        bind: function (input, onError) {
            let selectionFailed = false;
            let resetTimer;
            function clearFailedSelection() {
                clearTimeout(resetTimer);
                resetTimer = setTimeout(function () {
                    PartnerAttachments.reset(input);
                }, 0);
            }
            input.on('fileerror fileuploaderror filebatchuploaderror', function () {
                selectionFailed = true;
                if (onError) onError();
                clearFailedSelection();
            });
            input.on('filebatchselected', function () {
                if (selectionFailed) {
                    selectionFailed = false;
                    clearFailedSelection();
                }
            });
            input.on('change', function () {
                selectionFailed = false;
            });
        }
    };
</script>
