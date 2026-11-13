<script>
    $('#formDepartment').on('shown.bs.modal', function () {
        $(this).find('input[type="text"], textarea').each(function () {
            $(this).siblings('label[for="' + this.id + '"]').toggleClass('active', Boolean($(this).val()));
        });
    });
    function closeDepartmentModal() {
        let modal = $('#formDepartment');

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
        $('#department_table').DataTable({
            processing: true,
            language: @json(__('settings.datatable')),
            // serverSide: true,

            ajax: {
                url: "{{ route('fetch-department-setting') }}",
                type: "GET",
            },
            columns: [
                {
                    "data": null,
                    "render": function(data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                { data: 'name', name: 'name' },
                {
                    'data': null,
                    title: @json(__('settings.Action')),
                    wrap: true,
                    "render": function(item) {
                        let viewButton = $('<button>', {
                            type: 'button',
                            class: 'table-action table-action--view detail_department',
                            html: '<i class="fas fa-eye" aria-hidden="true"></i>',
                            title: @json(__('settings.View department')),
                            'aria-label': @json(__('settings.View department')),
                            'data-department_id': item.id,
                            'data-department_name': item.name,
                            'data-toggle': 'modal',
                            'data-target': '#formDepartment'
                        });

                        return $('<div>', {
                            class: 'table-actions',
                            role: 'group',
                            'aria-label': @json(__('settings.Department actions'))
                        })
                            .append(viewButton)
                            .prop('outerHTML');
                    }
                },
            ]
        });
    });
    
    $(document).on('click', '#for_create_department', function(e) {
        e.preventDefault();
        $('#formDepartmentLabel').html(@json(__('settings.Create New Department')));
        $('#form_data_department')[0].reset();
        $('.text-danger').text('')
        $('#button_action_department').empty();
        $('#button_action_department').append(`
            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('admin.Close') }}</button>
            <button type="button" class="btn btn-primary" id="submit_department">{{ __('admin.Submit') }}</button>
        `);
    });

    $(document).on('click', '.detail_department', function(e) {
        e.preventDefault();
        let id = $(this).data('department_id');
        let name = $(this).data('department_name');
        $('#formDepartmentLabel').text(@json(__('settings.department_named')).replace(':name', name));
        $('#form_data_department')[0].reset();
        $('.text-danger').text('')
        $('#department_id').val(id);
        $('#department_name').val(name);
        $('#button_action_department').empty();
        $('#button_action_department').append(`
            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('admin.Close') }}</button>
            <button type="button" class="btn btn-primary" id="update_department">{{ __('admin.Submit') }}</button>
        `);
    });

    $(document).on('click', '#submit_department, #update_department', function(e) {
        e.preventDefault();
        var formData = new FormData($('#form_data_department')[0]);
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            url: "{{ route('submit-department-setting') }}",
            data: formData,
            contentType: false,
            processData: false,
            success: function(res) {
                $('.text-danger').text('')
                closeDepartmentModal();
                $('#department_table').DataTable().ajax.reload();
                $(document).Toasts('create', {
                    title: @json(__('settings.Success')),
                    class: 'bg-success',
                    body: res.meta.message,
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
                            title: @json(__('settings.Error')),
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
                            title: @json(__('settings.Error')),
                            class: 'bg-danger',
                            body: @json(__('settings.Harap isi data yang masih kosong')),
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
