<script>
    $(document).ready(function() {
        $('#user_role').select2({
            dropdownParent: $('#formCreateUser'),
            tags: "true",
            selectOnClose: true
        })
        $('#user_office').select2({
            dropdownParent: $('#formCreateUser'),
            tags: "true",
            selectOnClose: true
        })
        $('#user_department').select2({
            dropdownParent: $('#formCreateUser'),
            tags: "true",
            selectOnClose: true
        })
        $('#update_user_role').select2({
            dropdownParent: $('#formUpdateUser')
        })
        $('#update_user_office').select2({
            dropdownParent: $('#formUpdateUser')
        })
        $('#update_user_department').select2({
            dropdownParent: $('#formUpdateUser')
        })

        $('#user_table').DataTable({
            processing: true,
            // serverside: true,
            ajax: {
                url: '{{ route("fetch-user") }}',
                type: 'GET',
            },
            columns: [
                {
                    "data": null,
                    "render": function(data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                {
                    "data": "name",
                    "defaultContent": "<i>Not set</i>"
                },
                {
                    "data": "roles[0].name",
                    "defaultContent": "<i>Not set</i>"
                },
                {
                    'data': null,
                    title: 'Action',
                    wrap: true,
                    "render": function(item) {
                        return '<button type="button" data-user_id="'+item.id+'" class="btn btn-outline-info btn-sm mt-2 detail_user" data-toggle="modal" data-target="#formUpdateUser">View</button> <button type="button" data-user_id="'+item.id+'" data-user_name="'+item.name+'" data-role_user="'+item.roles[0].name+'" class="btn btn-outline-danger btn-sm mt-2 delete_user" data-toggle="modal" data-target="#confirmDeleteUser">Delete</button>'
                    }
                },
            ]
        })

        $(document).on('click', '#for_create_user', function(e) {
            e.preventDefault()
            $('#form_create_new_user')[0].reset()
            $('.message_user_name').text('')
            $('.message_user_email').text('')
            $('.message_user_role').text('')
            $('.message_user_office').text('')
            $('.message_user_department').text('')
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route("fetch-role-office-dept") }}',
                type: 'GET',
                dataType: 'json',
                async: true,
                success: function(res) {
                    $('#user_role').empty()
                    $.each(res.data.roles, function(i, role) {
                        $('#user_role').append(`
                            <option value="${role.name}">${role.name}</option>
                        `)
                    })
                    $('#user_office').empty()
                    $.each(res.data.offices, function(i, office) {
                        $('#user_office').append(`
                            <option value="${office.id}">${office.name}</option>
                        `)
                    })
                    $('#user_department').empty()
                    $.each(res.data.departments, function(i, office) {
                        $('#user_department').append(`
                            <option value="${office.id}">${office.name}</option>
                        `)
                    })
                },
                error: function(xhr) {
                    let response_error = JSON.parse(xhr.responseText)
                    $(document).Toasts('create', {
                        title: 'Error',
                        class: 'bg-danger',
                        body: response_error.meta.message
                    });
                }
            })
        })

        $(document).on('click', '#save_user', function(e) {
            e.preventDefault()
            let user_name = $('#user_name').val()
            let user_email = $('#user_email').val()
            let user_role = $('#user_role').val()
            let user_office = $('#user_office').val()
            let user_department = $('#user_department').val()
            // superusersales@gmail.com
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route("store-user") }}',
                type: 'POST',
                data: {
                    name: user_name,
                    email: user_email,
                    role: user_role,
                    office: user_office,
                    department: user_department,
                },
                dataType: 'json',
                async: true,
                success: function(res) {
                    $('#user_table').DataTable().ajax.reload();
                    $('#formCreateUser').modal('toggle');
                    $(document).Toasts('create', {
                        title: 'Success',
                        class: 'bg-success',
                        body: 'User berhasil dibuat.',
                        delay: 10000,
                        autohide: true,
                        fade: true,
                        close: true,
                        autoremove: true,
                    });
                },
                error: function(xhr, status, error) {
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
                            $('#message_user_' + i).text(value)
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
            })
        })

        $(document).on('click', '.detail_user', function(e) {
            let user_id = $(this).data('user_id')
            $('#form_detail_current_user')[0].reset()
            $('.message_update_user_name').text('')
            $('.message_update_user_email').text('')
            $('.message_update_user_role').text('')
            $('.message_update_user_office').text('')
            $('.message_update_user_department').text('')
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route("detail-user") }}',
                type: 'GET',
                data: {
                    id: user_id
                },
                dataType: 'json',
                async: true,
                success: function(res) {
                    $('#update_user_name').val(res.data[0].name)
                    $('#update_user_email').val(res.data[0].email)

                    $('#update_current_role').val(res.data[0].roles[0].name)
                    $('#update_user_role').empty()
                    $('#update_user_role').append(`
                        <option value="${res.data[0].roles[0].name}">${res.data[0].roles[0].name}</option>
                    `)
                    $.each(res.data.roles, function(i, role) {
                        $('#update_user_role').append(`
                            <option value="${role.name}">${role.name}</option>
                        `)
                    })

                    $('#update_current_office').val(res.data[0].office.name)
                    $('#update_user_office').empty()
                    $('#update_user_office').append(`
                        <option value="${res.data[0].office.id}">${res.data[0].office.name}</option>
                    `)
                    $.each(res.data.offices, function(i, office) {
                        $('#update_user_office').append(`
                            <option value="${office.id}">${office.name}</option>
                        `)
                    })
                    
                    // $('#update_current_department').val(res.data[0].office.name)
                    $('#update_user_department').empty()
                    $('#update_user_department').append(`
                        <option value="${res.data[0].office.id}">${res.data[0].dept.name}</option>
                    `)
                    $.each(res.data.departments, function(i, department) {
                        $('#update_user_department').append(`
                            <option value="${department.id}">${department.name}</option>
                        `)
                    })
                    // $('.update_user').attr('data-update_user_id', res.data[0].id)
                    $('.update_user').data('update_user_id', res.data[0].id)
                },
                error: function(xhr) {
                    let response_error = JSON.parse(xhr.responseText)
                    $(document).Toasts('create', {
                        title: 'Error',
                        class: 'bg-danger',
                        body: response_error.meta.message
                    });
                }
            })
        })

        $(document).on('click', '.update_user', function() {
            // e.preventDefault()
            let update_user_id = $(this).data('update_user_id')
            let update_user_name = $('#update_user_name').val()
            let update_user_email = $('#update_user_email').val()
            let update_current_role = $('#update_current_role').val()
            let update_user_role = $('#update_user_role').val()
            let update_user_office = $('#update_user_office').val()
            let update_user_department = $('#update_user_department').val()

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route("update-user") }}',
                type: 'POST',
                data: {
                    id: update_user_id,
                    name: update_user_name,
                    email: update_user_email,
                    current_role: update_current_role,
                    role: update_user_role,
                    office: update_user_office,
                    department: update_user_department,
                },
                dataType: 'json',
                async: true,
                success: function(res) {
                    $('#user_table').DataTable().ajax.reload();
                    $('#formUpdateUser').modal('toggle');
                    $(document).Toasts('create', {
                        title: 'Success',
                        class: 'bg-success',
                        body: 'User berhasil diubah.',
                        delay: 10000,
                        autohide: true,
                        fade: true,
                        close: true,
                        autoremove: true,
                    });
                },
                error: function(xhr, status, error) {
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
                            $('#message_update_user_' + i).text(value)
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
            })
        })

        $(document).on('click', '.delete_user', function() {
            let delete_user_id = $(this).data('user_id')
            let delete_user_name = $(this).data('user_name')
            let delete_role_user = $(this).data('role_user')
            $('#delete_user_name').html('Yakin akan menghapus user '+delete_user_name+' ?')
            $('.confirm_delete_user').removeData('delete_user_id')
            $('.confirm_delete_user').attr('data-delete_user_id', delete_user_id)
            $('.confirm_delete_user').removeData('delete_role_user')
            $('.confirm_delete_user').attr('data-delete_role_user', delete_role_user)
        })

        $(document).on('click', '#confirm_delete_user', function(e) {
            e.preventDefault()
            let delete_user_id = $(this).data('delete_user_id')
            let delete_role_user = $(this).data('delete_role_user')
            // alert([delete_user_id, delete_role_user])
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route("delete-user") }}',
                type: 'POST',
                data: {
                    id: delete_user_id,
                    current_role: delete_role_user,
                },
                dataType: 'json',
                async: true,
                success: function(res) {
                    $('#user_table').DataTable().ajax.reload();
                    $('#confirmDeleteUser').modal('toggle');
                    $(document).Toasts('create', {
                        title: 'Success',
                        class: 'bg-success',
                        body: 'User berhasil dihapus.'
                    });
                },
                error: function(xhr) {
                    let response_error = JSON.parse(xhr.responseText)
                    $(document).Toasts('create', {
                        title: 'Error',
                        class: 'bg-danger',
                        body: 'gagal delete, silahkan hubungi pihak ICT.'
                    });
                }
            })

        })
    })
</script>