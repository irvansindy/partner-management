<script>
    $(document).ready(function() {
        $('#user_role').select2({
            dropdownParent: $('#formCreateUser'),
            tags: "true",
            selectOnClose: true
            // placeholder: "Select an option",
            // allowClear: true
        })
        $('#update_user_role').select2({
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
            
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route("fetch-role") }}',
                type: 'GET',
                dataType: 'json',
                async: true,
                success: function(res) {
                    $.each(res.data, function(i, role) {
                        $('#user_role').append(`
                            <option value="${role.name}">${role.name}</option>
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
                },
                dataType: 'json',
                async: true,
                success: function(res) {
                    $('#user_table').DataTable().ajax.reload();
                    $('#formCreateUser').modal('toggle');
                    $(document).Toasts('create', {
                        title: 'Success',
                        class: 'bg-success',
                        body: 'User berhasil dibuat.'
                    });
                },
                error: function(xhr, status, error) {
                    let response_error = JSON.parse(xhr.responseText)
                    $(document).Toasts('create', {
                        title: 'Error',
                        class: 'bg-danger',
                        body: 'User gagal dibuat, silahkan hubungi pihak ICT.'
                    });
                }
            })
        })

        $(document).on('click', '.detail_user', function(e) {
            let user_id = $(this).data('user_id')
            $('#form_detail_current_user')[0].reset()
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
                    $.each(res.data[1], function(i, role) {
                        $('#update_user_role').append(`
                            <option value="${role.name}">${role.name}</option>
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
                },
                dataType: 'json',
                async: true,
                success: function(res) {
                    $('#user_table').DataTable().ajax.reload();
                    $('#formUpdateUser').modal('toggle');
                    $(document).Toasts('create', {
                        title: 'Success',
                        class: 'bg-success',
                        body: 'User berhasil diubah.'
                    });
                },
                error: function(xhr) {
                    let response_error = JSON.parse(xhr.responseText)
                    $(document).Toasts('create', {
                        title: 'Error',
                        class: 'bg-danger',
                        body: 'gagal update, silahkan hubungi pihak ICT.'
                    });
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