<script>
    const officeText = {
        create: @json(__('messages.Office Setting Create')),
        detail: @json(__('messages.Office Setting Detail', ['name' => ':name'])),
        close: @json(__('messages.Office Setting Close')),
        save: @json(__('messages.Office Setting Save')),
        saveChanges: @json(__('messages.Office Setting Save Changes')),
        success: @json(__('messages.Office Setting Success')),
        error: @json(__('messages.Office Setting Error')),
        saveSuccess: @json(__('messages.Office Setting Save Success')),
        completeRequired: @json(__('messages.Office Setting Complete Required')),
        loadFailed: @json(__('messages.Office Setting Load Failed')),
        notSet: @json(__('messages.Office Setting Not Set')),
        actions: @json(__('messages.Office Setting Actions')),
        officeActions: @json(__('messages.Office Setting Office Actions')),
        view: @json(__('messages.Office Setting View')),
        tableEmpty: @json(__('messages.Office Setting Table Empty')),
        tableLoading: @json(__('messages.Office Setting Table Loading')),
        tableSearch: @json(__('messages.Office Setting Table Search')),
        tableInfo: @json(__('messages.Office Setting Table Info')),
        tableInfoEmpty: @json(__('messages.Office Setting Table Info Empty')),
        tableZeroRecords: @json(__('messages.Office Setting Table Zero Records')),
        tablePrevious: @json(__('messages.Office Setting Table Previous')),
        tableNext: @json(__('messages.Office Setting Table Next')),
    };

    function syncOfficeLabels() {
        $('#form_data_office input[type="text"], #form_data_office textarea').each(function () {
            $(this).siblings('label[for="' + this.id + '"]')
                .toggleClass('active', Boolean($(this).val()));
        });
    }

    function closeOfficeModal() {
        let modal = $('#formOffice');

        modal.modal('hide');

        setTimeout(function() {
            if (modal.hasClass('show') || modal.is(':visible')) {
                modal.removeClass('show').attr('aria-hidden', 'true').css('display', 'none');
                $('body').removeClass('modal-open').css('padding-right', '');
                $('.modal-backdrop').remove();
            }
        }, 350);
    }

    $(document).ready(function() {

        $('#office_table').DataTable({
            processing: true,
            language: Object.assign(@json(__('settings.datatable')), {
                emptyTable: officeText.tableEmpty,
                loadingRecords: officeText.tableLoading,
                processing: officeText.tableLoading,
                search: officeText.tableSearch,
                info: officeText.tableInfo,
                infoEmpty: officeText.tableInfoEmpty,
                zeroRecords: officeText.tableZeroRecords,
                paginate: { previous: officeText.tablePrevious, next: officeText.tableNext }
            }),

            ajax: {
                url: "{{ route('fetch-office-setting') }}",
                type: "GET",
            },
            columns: [
                {
                    "data": null,
                    "render": function(data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                { data: 'name', name: 'name', defaultContent: officeText.notSet },
                {
                    'data': null,
                    title: officeText.actions,
                    wrap: true,
                    "render": function(item) {
                        let viewButton = $('<button>', {
                            type: 'button',
                            class: 'table-action table-action--view detail_office',
                            html: '<i class="fas fa-eye" aria-hidden="true"></i>',
                            title: officeText.view,
                            'aria-label': officeText.view,
                            'data-office_id': item.id,
                            'data-office_name': item.name,
                            'data-toggle': 'modal',
                            'data-target': '#formOffice'
                        });

                        return $('<div>', {
                            class: 'table-actions',
                            role: 'group',
                            'aria-label': officeText.officeActions
                        })
                            .append(viewButton)
                            .prop('outerHTML');
                    }
                },
            ]
        });
    });
    
    $(document).on('click', '#for_create_office', function(e) {
        e.preventDefault();
        $('#formOfficeLabel').text(officeText.create);
        $('#form_data_office')[0].reset();
        syncOfficeLabels();
        $('.text-danger').text('')
        $('#button_action_office').empty();
        $('#button_action_office').append(`
            <button type="button" class="btn btn-secondary" data-dismiss="modal">${officeText.close}</button>
            <button type="button" class="btn btn-primary" id="submit_office">${officeText.save}</button>
        `);
    });

    $(document).on('click', '.detail_office', function(e) {
        e.preventDefault();
        let id = $(this).data('office_id');
        let name = $(this).data('office_name');
        $('#formOfficeLabel').text(officeText.detail.replace(':name', name));
        $('#form_data_office')[0].reset();
        $('.text-danger').text('')
        $('#office_id').val(id);
        $('#office_name').val(name);
        syncOfficeLabels();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "GET",
            url: "{{ route('fetch-office-setting-by-id') }}",
            data: {
                id: id
            },
            success: function(res) {
                $('#office_address').val(res.data.address);
                syncOfficeLabels();
            },
            error: function(xhr) {
                let response_error = JSON.parse(xhr.responseText)
                    if (response_error.meta.code === 500 || response_error.meta.code === 400) {
                        $(document).Toasts('create', {
                            title: officeText.error,
                            class: 'bg-danger',
                            body: response_error.meta.message,
                            delay: 10000,
                            autohide: true,
                            fade: true,
                            close: true,
                            autoremove: true,
                        });
                    }
            }
        })
        $('#button_action_office').empty();
        $('#button_action_office').append(`
            <button type="button" class="btn btn-secondary" data-dismiss="modal">${officeText.close}</button>
            <button type="button" class="btn btn-primary" id="update_office">${officeText.saveChanges}</button>
        `);
    });

    $(document).on('click', '#submit_office, #update_office', function(e) {
        e.preventDefault();
        var formData = new FormData($('#form_data_office')[0]);
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            url: "{{ route('submit-office-setting') }}",
            data: formData,
            contentType: false,
            processData: false,
            success: function(res) {
                $('.text-danger').text('')
                closeOfficeModal();
                $('#office_table').DataTable().ajax.reload();
                $(document).Toasts('create', {
                    title: officeText.success,
                    class: 'bg-success',
                    body: officeText.saveSuccess,
                    delay: 10000,
                    autohide: true,
                    fade: true,
                    close: true,
                    autoremove: true,
                });
            },
            error: function(xhr) {
                let response_error = JSON.parse(xhr.responseText)
                    if (response_error.meta.code === 500 || response_error.meta.code === 400) {
                        $(document).Toasts('create', {
                            title: officeText.error,
                            class: 'bg-danger',
                            body: response_error.meta.message,
                            delay: 10000,
                            autohide: true,
                            fade: true,
                            close: true,
                            autoremove: true,
                        });
                    } else {
                        $('.text-danger').text('')
                        $.each(response_error.meta.message.errors, function(i, value) {
                            // alert(value)
                            $('#message_' + i).text(value)
                        })
                        $(document).Toasts('create', {
                            title: officeText.error,
                            class: 'bg-danger',
                            body: officeText.completeRequired,
                            delay: 10000,
                            autohide: true,
                            fade: true,
                            close: true,
                            autoremove: true,
                        });
                    }
            }
        });
    });
</script>
