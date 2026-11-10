<script>
    $(function () {
        let divisionGrid = null;
        let divisions = [];
        const $status = $('#division_status');
        const divisionUrl = @json(url('division-setting'));
        const csrfToken = $('meta[name="csrf-token"]').attr('content');

        function syncDivisionLabels() {
            $('#division_form input[type="text"], #division_form textarea').each(function () {
                $(this).siblings('label[for="' + this.id + '"]').toggleClass('active', Boolean($(this).val()));
            });
        }

        function closeDivisionModal() {
            const $modal = $('#divisionModal');
            $modal.modal('hide');
            window.setTimeout(function () {
                if ($modal.hasClass('show') || $modal.is(':visible')) {
                    $modal.removeClass('show').hide().attr('aria-hidden', 'true').removeAttr('aria-modal');
                    $('.modal-backdrop').remove();
                    $('body').removeClass('modal-open').css('padding-right', '');
                }
            }, 350);
        }

        $('#divisionModal [data-dismiss="modal"]').on('click', function (event) {
            event.preventDefault();
            event.stopPropagation();
            closeDivisionModal();
        });

        function escapeHtml(value) {
            return String(value == null ? '' : value)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function showMessage(icon, title, text) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({ icon: icon, title: title, text: text });
            } else {
                window.alert(text || title);
            }
        }

        function renderDivisionGrid(divisions) {
            const rows = divisions.map(function (division, index) {
                return [
                    division.id,
                    index + 1,
                    division.name || '—',
                    division.gm ? division.gm.name : '—',
                    division.office ? division.office.name : (division.office_id || '—'),
                    null
                ];
            });

            if (divisionGrid) {
                divisionGrid.updateConfig({ data: rows }).forceRender();
                return;
            }

            divisionGrid = new gridjs.Grid({
                columns: [
                    { id: 'id', name: 'id', hidden: true },
                    { name: '#', width: '70px', sort: false },
                    { name: 'Division Name', sort: true },
                    { name: 'General Manager', sort: true },
                    { name: 'Office', sort: true },
                    {
                        name: 'Actions',
                        sort: false,
                        width: '120px',
                        formatter: function (_, row) {
                            const id = Number(row.cells[0].data);
                                const divisionName = escapeHtml(row.cells[2].data);
                            return gridjs.html(`
                                <div class="table-actions" role="group" aria-label="Actions for ${divisionName}">
                                    <button type="button" class="table-action table-action--edit edit_division" data-id="${id}" title="Edit division" aria-label="Edit ${divisionName}">
                                        <i class="fas fa-pen" aria-hidden="true"></i>
                                    </button>
                                    <button type="button" class="table-action table-action--delete delete_division" data-id="${id}" title="Delete division" aria-label="Delete ${divisionName}">
                                        <i class="fas fa-trash" aria-hidden="true"></i>
                                    </button>
                                </div>
                            `);
                        }
                    }
                ],
                data: rows,
                search: true,
                sort: { multiColumn: false },
                pagination: { limit: 10 },
                className: {
                    table: 'table table-striped table-hover align-middle'
                },
                language: {
                    search: { placeholder: 'Search divisions...' },
                    pagination: {
                        previous: '‹',
                        next: '›',
                        showing: 'Showing',
                        of: 'of',
                        to: 'to',
                        results: 'divisions'
                    },
                    noRecordsFound: 'No divisions found',
                    error: 'Unable to load divisions.'
                }
            }).render(document.getElementById('division_table'));
        }

        function loadDivisions() {
            $status.text('Loading divisions...').removeClass('d-none alert-danger').addClass('alert-info');

            $.ajax({
                url: @json(route('fetch-division')),
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    divisions = Array.isArray(response.data) ? response.data : [];
                    renderDivisionGrid(divisions);
                    $status.addClass('d-none').text('');
                },
                error: function () {
                    $status.text('Unable to load divisions. Try refreshing the list.')
                        .removeClass('alert-info d-none')
                        .addClass('alert-danger');
                }
            });
        }

        function clearDivisionErrors() {
            $('[id^="division_"][id$="_error"]').text('');
            $('#division_name, #division_gm_user_id, #division_office_id').removeClass('is-invalid');
        }

        function resetDivisionForm() {
            $('#division_form')[0].reset();
            syncDivisionLabels();
            $('#division_id').val('');
            $('#divisionModalTitle').text('Create Division');
            $('#save_division').html('<i class="material-icons align-middle mr-1">save</i> Save Division');
            clearDivisionErrors();
        }

        $('#create_division').on('click', resetDivisionForm);

        $(document).on('click', '.edit_division', function () {
            const division = divisions.find(function (item) {
                return Number(item.id) === Number($(this).data('id'));
            }.bind(this));

            if (!division) return;

            resetDivisionForm();
            $('#division_id').val(division.id);
            $('#division_name').val(division.name || '');
            syncDivisionLabels();
            $('#division_gm_user_id').val(division.gm_user_id || '');
            $('#division_office_id').val(division.office_id || '');
            $('#divisionModalTitle').text('Edit Division');
            $('#save_division').html('<i class="material-icons align-middle mr-1">save</i> Update Division');
            $('#divisionModal').modal('show');
        });

        $('#division_form').on('submit', function (event) {
            event.preventDefault();
            clearDivisionErrors();

            const divisionId = $('#division_id').val();
            const isUpdate = Boolean(divisionId);
            const data = {
                _token: csrfToken,
                name: $('#division_name').val().trim(),
                gm_user_id: $('#division_gm_user_id').val() || null,
                office_id: $('#division_office_id').val() || null
            };

            $('#save_division').prop('disabled', true);
            $.ajax({
                url: isUpdate ? divisionUrl + '/' + encodeURIComponent(divisionId) : divisionUrl,
                type: isUpdate ? 'PUT' : 'POST',
                data: data,
                headers: { 'X-CSRF-TOKEN': csrfToken },
                success: function (response) {
                    closeDivisionModal();
                    loadDivisions();
                    showMessage('success', 'Saved', response.meta && response.meta.message || 'Division saved successfully.');
                },
                error: function (xhr) {
                    const errors = xhr.responseJSON && xhr.responseJSON.errors;
                    if (errors) {
                        Object.keys(errors).forEach(function (field) {
                            const message = Array.isArray(errors[field]) ? errors[field][0] : errors[field];
                            const inputId = field === 'name' ? 'division_name' : 'division_' + field;
                            $('#' + inputId + '_error').text(message);
                            $('#' + inputId).addClass('is-invalid');
                        });
                        return;
                    }

                    const message = xhr.responseJSON && xhr.responseJSON.meta && xhr.responseJSON.meta.message;
                    showMessage('error', 'Unable to save division', message || 'Please try again.');
                },
                complete: function () {
                    $('#save_division').prop('disabled', false);
                }
            });
        });

        $(document).on('click', '.delete_division', function () {
            const divisionId = $(this).data('id');
            const division = divisions.find(function (item) { return Number(item.id) === Number(divisionId); });
            const divisionName = division ? division.name : 'this division';

            const confirmDelete = typeof Swal !== 'undefined'
                ? Swal.fire({
                    title: 'Delete Division?',
                    text: 'Delete ' + divisionName + '? This action cannot be undone.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Delete',
                    cancelButtonText: 'Cancel'
                }).then(function (result) { return result.isConfirmed; })
                : Promise.resolve(window.confirm('Delete ' + divisionName + '? This action cannot be undone.'));

            confirmDelete.then(function (confirmed) {
                if (!confirmed) return;

                $.ajax({
                    url: divisionUrl + '/' + encodeURIComponent(divisionId),
                    type: 'DELETE',
                    data: { _token: csrfToken },
                    headers: { 'X-CSRF-TOKEN': csrfToken },
                    success: function (response) {
                        loadDivisions();
                        showMessage('success', 'Deleted', response.meta && response.meta.message || 'Division deleted successfully.');
                    },
                    error: function (xhr) {
                        const message = xhr.responseJSON && xhr.responseJSON.meta && xhr.responseJSON.meta.message;
                        showMessage('error', 'Unable to delete division', message || 'Please try again.');
                    }
                });
            });
        });

        $('#refresh_divisions').on('click', loadDivisions);
        loadDivisions();
    });
</script>
