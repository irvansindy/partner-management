<script>
    $(document).ready(function() {
        $('#office_table').DataTable({
            processing: true,
            // serverSide: true,

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
                { data: 'name', name: 'name' },
                {
                    'data': null,
                    title: 'Action',
                    wrap: true,
                    "render": function(item) {
                        return '<button type="button" data-office_id="'+item.id+'" data-office_name="'+item.name+'" class="btn btn-outline-info btn-sm mt-2 detail_office" data-toggle="modal" data-target="#formOffice">View</button>'
                    }
                },
            ]
        });
    });
    
    $(document).on('click', '#for_create_office', function(e) {
        e.preventDefault();
        $('#formOfficeLabel').html('Create New office');
        $('#form_data_office')[0].reset();
        $('.text-danger').text('')
        $('#button_action_office').empty();
        $('#button_action_office').append(`
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" id="submit_office">Submit</button>
        `);
    });

    $(document).on('click', '.detail_office', function(e) {
        e.preventDefault();
        let id = $(this).data('office_id');
        let name = $(this).data('office_name');
        $('#formOfficeLabel').html('Detail '+name);
        $('#form_data_office')[0].reset();
        $('.text-danger').text('')
        $('#office_id').val(id);
        $('#office_name').val(name);
        $.ajax({
            header: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "GET",
            url: "{{ route('fetch-office-setting-by-id') }}",
            data: {
                id: id
            },
            success: function(res) {
                $('#office_address').val(res.data.address);
            },
            error: function(xhr) {
                let response_error = JSON.parse(xhr.responseText)
                    if (response_error.meta.code === 500 || response_error.meta.code === 400) {
                        $(document).Toasts('create', {
                            title: 'Error',
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
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" id="update_office">Submit</button>
        `);
    });

    $(document).on('click', '#submit_office, #update_office', function(e) {
        e.preventDefault();
        var formData = new FormData($('#form_data_office')[0]);
        $.ajax({
            header: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            url: "{{ route('submit-office-setting') }}",
            data: formData,
            contentType: false,
            processData: false,
            success: function(res) {
                $('.text-danger').text('')
                $('#formoffice').modal('toggle');
                $('#formoffice').modal('hide');
                $('#office_table').DataTable().ajax.reload();
                $(document).Toasts('create', {
                    title: 'Success',
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
                            title: 'Error',
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
                            title: 'Error',
                            class: 'bg-danger',
                            body: 'Silahkan isi data yang masih kosong',
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