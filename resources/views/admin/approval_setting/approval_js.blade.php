<script>
    $(function () {
        let availableUsers = [];
        const csrfToken = $('meta[name="csrf-token"]').attr('content');

        function escapeHtml(value) {
            return $('<div>').text(value == null ? '' : String(value)).html();
        }

        function toast(title, message, type) {
            $(document).Toasts('create', {
                title: title,
                class: type === 'success' ? 'bg-success' : 'bg-danger',
                body: escapeHtml(message),
                delay: 5000,
                autohide: true,
                close: true,
                autoremove: true
            });
        }

        function clearValidation($form) {
            $form.find('.is-invalid').removeClass('is-invalid');
            $form.find('[data-field-error]').text('');
        }

        function responseErrors(xhr) {
            const response = xhr.responseJSON || {};
            const message = response.meta && response.meta.message;
            return (message && message.errors) || response.errors || {};
        }

        function responseMessage(xhr, fallback) {
            const response = xhr.responseJSON || {};
            const message = response.meta && response.meta.message;
            return typeof message === 'string' ? message : fallback;
        }

        function showValidationErrors($form, xhr) {
            const errors = responseErrors(xhr);
            Object.keys(errors).forEach(function (field) {
                const baseField = field.split('.')[0];
                const message = Array.isArray(errors[field]) ? errors[field][0] : errors[field];
                $form.find('[name="' + baseField + '"], [name="' + baseField + '[]"]').addClass('is-invalid');
                $form.find('[data-field-error="' + baseField + '"]').text(message);
            });
            toast('Unable to save', responseMessage(xhr, 'Please check the highlighted fields.'), 'error');
        }

        function setButtonLoading($button, loading, loadingText) {
            if (loading) {
                $button.data('original-html', $button.html()).prop('disabled', true)
                    .html('<span class="spinner-border spinner-border-sm mr-1" aria-hidden="true"></span>' + loadingText);
            } else {
                $button.prop('disabled', false).html($button.data('original-html'));
            }
        }

        const table = $('#approval_table').DataTable({
            processing: true,
            ajax: { url: '{{ route('fetch-approval') }}', type: 'GET' },
            order: [[0, 'asc']],
            columns: [
                {
                    data: null,
                    orderable: false,
                    render: function (data, type, row, meta) { return meta.row + meta.settings._iDisplayStart + 1; }
                },
                { data: 'name', render: function (data) { return '<strong>' + escapeHtml(data || 'Not set') + '</strong>'; } },
                { data: 'office', render: function (data) { return escapeHtml(data || '-'); } },
                { data: 'department', render: function (data) { return escapeHtml(data || '-'); } },
                { data: 'total_steps', className: 'text-center', render: function (data) { return '<span class="badge badge-light">' + Number(data || 0) + '</span>'; } },
                { data: 'status', render: function (data) { const active = data === 'Active'; return '<span class="badge ' + (active ? 'badge-success' : 'badge-secondary') + '">' + escapeHtml(data) + '</span>'; } },
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: function (item) {
                        return '<button type="button" data-master-approval-id="' + Number(item.id) +
                            '" class="btn btn-primary btn-sm detail_approval_detail" data-toggle="modal" data-target="#addViewApprovalDetail"' +
                            ' aria-label="Arrange approvers for ' + escapeHtml(item.name) + '">' +
                            '<i class="fas fa-list-ol mr-1" aria-hidden="true"></i>Approvers</button>';
                    }
                }
            ],
            language: {
                emptyTable: 'No approval templates have been created.',
                processing: 'Loading approval templates...'
            }
        });

        function initializeMasterSelects() {
            $('.option_approval_master').select2({
                width: '100%',
                dropdownParent: $('#formCreateApproval'),
                dropdownCssClass: 'approval-select2-dropdown',
                placeholder: 'Select an option'
            });
        }

        $('#for_create_approval').on('click', function () {
            const $form = $('#data_approval_master');
            $form[0].reset();
            clearValidation($form);
            const $office = $('#stagging_approval_office').empty().append('<option value="">Loading offices...</option>').prop('disabled', true);
            const $department = $('#stagging_approval_department').empty().append('<option value="">Loading departments...</option>').prop('disabled', true);

            $.ajax({
                url: "{{ route('fetch-office-department') }}",
                method: 'GET',
                headers: { 'X-CSRF-TOKEN': csrfToken },
                success: function (response) {
                    $office.empty().append('<option value=""></option>').prop('disabled', false);
                    $department.empty().append('<option value=""></option>').prop('disabled', false);
                    response.data.offices.forEach(function (office) {
                        $office.append(new Option(office.name, office.id));
                    });
                    response.data.departments.forEach(function (department) {
                        $department.append(new Option(department.name, department.id));
                    });
                    initializeMasterSelects();
                },
                error: function (xhr) {
                    $office.empty().append('<option value="">Unable to load offices</option>');
                    $department.empty().append('<option value="">Unable to load departments</option>');
                    toast('Unable to load data', responseMessage(xhr, 'Office and department data could not be loaded.'), 'error');
                }
            });
        });

        function userOptions(selectedId) {
            let options = '<option value=""></option>';
            availableUsers.forEach(function (user) {
                const selected = Number(user.id) === Number(selectedId) ? ' selected' : '';
                options += '<option value="' + Number(user.id) + '"' + selected + '>' + escapeHtml(user.name) + ' (' + escapeHtml(user.email) + ')</option>';
            });
            return options;
        }

        function renderApproverRow(selectedId, removable) {
            return '<div class="approver-step-card" data-approver-row>' +
                '<span class="approver-step-card__number" data-step-number></span>' +
                '<div class="form-group"><label class="sr-only">Approver</label>' +
                '<select class="form-control option_approval_detail" name="stagging_approval_name[]" required>' + userOptions(selectedId) + '</select></div>' +
                (removable ? '<button type="button" class="btn btn-outline-danger approver-step-card__remove delete_stagging" aria-label="Remove approval step"><i class="fas fa-trash-alt" aria-hidden="true"></i></button>' : '<span aria-hidden="true"></span>') +
                '</div>';
        }

        function refreshApproverRows() {
            const $rows = $('[data-approver-list] [data-approver-row]');
            $rows.each(function (index) {
                $(this).find('[data-step-number]').text(index + 1);
                $(this).find('label').text('Approver for step ' + (index + 1));
                $(this).find('select').attr('aria-label', 'Approver for step ' + (index + 1));
            });
            $('#add_stagging').prop('disabled', $rows.length >= 10);
        }

        function initializeApproverSelects() {
            $('.option_approval_detail').not('.select2-hidden-accessible').select2({
                width: '100%',
                dropdownParent: $('#addViewApprovalDetail'),
                dropdownCssClass: 'approval-select2-dropdown',
                placeholder: 'Select approver'
            });
        }

        $(document).on('click', '.detail_approval_detail', function () {
            const templateId = $(this).data('master-approval-id');
            const $modal = $('#addViewApprovalDetail');
            const $form = $('#data_approval_detail');
            clearValidation($form);
            $('#master_approval_id').val(templateId);
            $('[data-approver-list]').empty();
            $('[data-approval-error]').prop('hidden', true).text('');
            $('[data-approval-loading]').prop('hidden', false);
            $('#add_stagging, #create_approval_detail_data').prop('disabled', true);

            $.ajax({
                url: "{{ route('fetch-user-approval') }}",
                method: 'GET',
                data: { master_approval_id: templateId },
                headers: { 'X-CSRF-TOKEN': csrfToken },
                success: function (response) {
                    availableUsers = response.data.users || [];
                    const template = response.data.template;
                    $('#master_approval_name').text(template.name);
                    $('[data-template-office]').text(template.office);
                    $('[data-template-department]').text(template.department);
                    $('[data-approval-loading]').prop('hidden', true);

                    const existing = response.data.existing_approvers || [];
                    if (existing.length) {
                        existing.forEach(function (approval, index) {
                            $('[data-approver-list]').append(renderApproverRow(approval.user_id, index > 0));
                        });
                    } else {
                        $('[data-approver-list]').append(renderApproverRow('', false));
                    }
                    initializeApproverSelects();
                    refreshApproverRows();
                    $('#create_approval_detail_data').prop('disabled', availableUsers.length === 0);
                },
                error: function (xhr) {
                    $('[data-approval-loading]').prop('hidden', true);
                    $('[data-approval-error]').prop('hidden', false).text(responseMessage(xhr, 'Approver data could not be loaded.'));
                }
            });
        });

        $('#add_stagging').on('click', function () {
            const count = $('[data-approver-list] [data-approver-row]').length;
            if (count >= 10) return;
            $('[data-approver-list]').append(renderApproverRow('', true));
            initializeApproverSelects();
            refreshApproverRows();
        });

        $(document).on('click', '.delete_stagging', function () {
            const $row = $(this).closest('[data-approver-row]');
            $row.find('.option_approval_detail').select2('destroy');
            $row.remove();
            refreshApproverRows();
        });

        $('#data_approval_master').on('submit', function (event) {
            event.preventDefault();
            const $form = $(this);
            const $button = $('#create_approval_master_data');
            clearValidation($form);
            setButtonLoading($button, true, 'Creating...');
            $.ajax({
                url: '{{ route('store-approval-master') }}',
                method: 'POST',
                data: new FormData(this),
                processData: false,
                contentType: false,
                headers: { 'X-CSRF-TOKEN': csrfToken },
                success: function (response) {
                    table.ajax.reload(null, false);
                    $('#formCreateApproval').modal('hide');
                    toast('Template created', response.meta.message, 'success');
                },
                error: function (xhr) { showValidationErrors($form, xhr); },
                complete: function () { setButtonLoading($button, false); }
            });
        });

        $('#data_approval_detail').on('submit', function (event) {
            event.preventDefault();
            const $form = $(this);
            const values = $form.find('[name="stagging_approval_name[]"]').map(function () { return $(this).val(); }).get().filter(Boolean);
            if (!values.length || new Set(values).size !== values.length) {
                $('[data-field-error="stagging_approval_name"]').text(values.length ? 'Each approval step must use a different user.' : 'Add at least one approver.');
                return;
            }
            const $button = $('#create_approval_detail_data');
            clearValidation($form);
            setButtonLoading($button, true, 'Saving...');
            $.ajax({
                url: '{{ route('submit-approval-detail') }}',
                method: 'POST',
                data: new FormData(this),
                processData: false,
                contentType: false,
                headers: { 'X-CSRF-TOKEN': csrfToken },
                success: function (response) {
                    table.ajax.reload(null, false);
                    $('#addViewApprovalDetail').modal('hide');
                    toast('Sequence saved', response.meta.message, 'success');
                },
                error: function (xhr) { showValidationErrors($form, xhr); },
                complete: function () { setButtonLoading($button, false); }
            });
        });

        initializeMasterSelects();
    });
</script>
