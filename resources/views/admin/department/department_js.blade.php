<script>
    $(document).ready(function() {
        $('#department_table').DataTable({
            processing: true,
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
                    title: 'Action',
                    wrap: true,
                    "render": function(item) {
                        return '<button type="button" data-department_id="'+item.id+'" data-department_name="'+item.name+'" class="btn btn-outline-info btn-sm mt-2 detail_department" data-toggle="modal" data-target="#formDepartment">View</button>'
                    }
                },
            ]
        });
    });
    
    $(document).on('click', '#for_create_department', function(e) {
        e.preventDefault();
        $('#formDepartmentLabel').html('Create New Department');
        $('#form_data_department')[0].reset();
        $('.text-danger').text('')
        $('#button_action_department').empty();
        $('#button_action_department').append(`
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" id="submit_department">Submit</button>
        `);
    });

    $(document).on('click', '.detail_department', function(e) {
        e.preventDefault();
        let id = $(this).data('department_id');
        let name = $(this).data('department_name');
        $('#formDepartmentLabel').html('Department '+name);
        $('#form_data_department')[0].reset();
        $('.text-danger').text('')
        $('#department_id').val(id);
        $('#department_name').val(name);
        $('#button_action_department').empty();
        $('#button_action_department').append(`
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" id="update_department">Submit</button>
        `);
    });

    $(document).on('click', '#submit_department, #update_department', function(e) {
        e.preventDefault();
        var formData = new FormData($('#form_data_department')[0]);
        $.ajax({
            header: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            url: "{{ route('submit-department-setting') }}",
            data: formData,
            contentType: false,
            processData: false,
            success: function(res) {
                $('.text-danger').text('')
                $('#formDepartment').modal('toggle');
                $('#department_table').DataTable().ajax.reload();
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