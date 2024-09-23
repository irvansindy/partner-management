<script>
    $(document).ready(function () {
        $('#role_table').DataTable({
            processing: true,
            // serverside: true,
            ajax: {
                url: '{{ route("fetch-role") }}',
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
                    'data': null,
                    title: 'Action',
                    wrap: true,
                    "render": function(item) {
                        return '<button type="button" data-role_id="'+item.id+'" class="btn btn-outline-info btn-sm mt-2 detail_role" data-toggle="modal" data-target="#formUpdateRole">View</button> <button type="button" data-role_id="'+item.id+'" class="btn btn-outline-secondary btn-sm mt-2 add_permission" data-toggle="modal" data-target="#formAddPermission">+ Permission</button> <button type="button" data-role_id="'+item.id+'" class="btn btn-outline-danger btn-sm mt-2 delete_role" data-toggle="modal" data-target="#confirmDeleteRole">Delete</button>'
                    }
                },
            ]
        })
        
        $('#permission_table').DataTable({
            processing: true,
            // serverside: false,
            ajax: {
                url: '{{ route("fetch-permission") }}',
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
                    'data': null,
                    title: 'Action',
                    wrap: true,
                    "render": function(item) {
                        return '<button type="button" data-permission_id="'+item.id+'" class="btn btn-outline-info btn-sm mt-2 detail_permission" data-toggle="modal" data-target="#formUpdatePermission">View</button>'
                    }
                },
            ]
        })

        $(document).on('click', '#for_create_role', function(e) {
            e.preventDefault()
            $('#form_create_new_role')[0].reset();
        })

        $(document).on('click', '#save_role', function(e) {
            e.preventDefault()
            let role_name = $('#role_name').val()
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route("store-role") }}',
                type: 'POST',
                data: {
                    role_name: role_name
                },
                dataType: 'json',
                async: true,
                success: function(res) {
                    $('#role_table').DataTable().ajax.reload();
                    $('#formCreateRole').modal('toggle');
                    $(document).Toasts('create', {
                        title: 'Success',
                        class: 'bg-success',
                        body: 'Role '+role_name+' berhasil dibuat.'
                    });
                },
                error: function(xhr, status, error) {
                    let response_error = JSON.parse(xhr.responseText);
                    $(document).Toasts('create', {
                        title: 'Error',
                        class: 'bg-danger',
                        body: response_error.meta.message
                    });
                }
            })
        })

        $(document).on('click', '#for_create_permission', function(e) {
            e.preventDefault()
            $('#form_create_new_permission')[0].reset();
            $('#get_menu').empty()
        })

        $(document).on('change', '.for_set_menu_or_submenu', function() {
            if ($('#select_menu').is(':checked')) {
                $('#get_menu').empty()
            } else if ($('#select_submenu').is(':checked')) {
                $('#get_menu').empty()
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '{{ route("fetch-menu") }}',
                    type: 'GET',
                    async: true,
                    success: function(res) {
                        $('#get_menu').empty()
                        $('#get_menu').append(`
                            <div class="form-group">
                                <label for=""></label>
                                <select class="form-select" aria-label="select menu" id="select_list_menu">
                                </select>
                            </div>
                        `)
                        $('#select_list_menu').empty()
                        $.each(res.data, function(i, menu) {
                            $('#select_list_menu').append(`
                                <option value="${menu.id}">${menu.name_text}</option>
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
                
            }
        })

        $(document).on('click', '#save_permission', function(e) {
            e.preventDefault()
            let permission_name = $('#permission_name').val()
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route("store-permission") }}',
                type: 'POST',
                data: {
                    permission_name: permission_name
                },
                dataType: 'json',
                async: true,
                success: function(res) {
                    $('#permission_table').DataTable().ajax.reload();
                    $('#formCreatePermission').modal('toggle');
                    $(document).Toasts('create', {
                        title: 'Success',
                        class: 'bg-success',
                        body: 'Role '+permission_name+' berhasil dibuat.'
                    });
                },
                error: function(xhr, status, error) {
                    let response_error = JSON.parse(xhr.responseText)
                    $(document).Toasts('create', {
                        title: 'Error',
                        class: 'bg-danger',
                        body: response_error.meta.message
                    });
                }
            })
        })

        $(document).on('click', '.detail_role', function(e) {
            e.preventDefault()
            let role_id = $(this).data('role_id')
            $('#form_update_new_role')[0].reset()
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route("detail-role") }}',
                type: 'GET',
                data: {
                    role_id: role_id
                },
                dataType: 'json',
                async: true,
                success: function(res) {
                    $('#update_role_id').val(res.data.id)
                    $('#update_role_name').val(res.data.name)
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
        
        $(document).on('click', '#update_role', function(e) {
            e.preventDefault()
            let update_role_id = $('#update_role_id').val()
            let update_role_name = $('#update_role_name').val()
            
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route("update-role") }}',
                type: 'POST',
                data: {
                    role_id: update_role_id,
                    role_name: update_role_name
                },
                dataType: 'json',
                async: true,
                success: function(res) {
                    $('#role_table').DataTable().ajax.reload();
                    $('#formUpdateRole').modal('toggle');
                    $(document).Toasts('create', {
                        title: 'Success',
                        class: 'bg-success',
                        body: 'Role '+update_role_name+' berhasil diubah.'
                    });
                },
                error: function(xhr, status, error) {
                    let response_error = JSON.parse(xhr.responseText);
                    $(document).Toasts('create', {
                        title: 'Error',
                        class: 'bg-danger',
                        body: response_error.meta.message
                    });
                }
            })
        })

        $(document).on('click', '.detail_permission', function(e) {
            e.preventDefault()
            let permission_id = $(this).data('permission_id')
            $('#form_update_new_permission')[0].reset()
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route("detail-permission") }}',
                type: 'GET',
                data: {
                    permission_id: permission_id
                },
                dataType: 'json',
                async: true,
                success: function(res) {
                    $('#update_permission_id').val(res.data.id)
                    $('#update_permission_name').val(res.data.name)
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

        $(document).on('click', '#update_permission', function(e) {
            e.preventDefault()
            let update_permission_id = $('#update_permission_id').val()
            let update_permission_name = $('#update_permission_name').val()

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route("update-permission") }}',
                type: 'POST',
                data: {
                    permission_id: update_permission_id,
                    permission_name: update_permission_name
                },
                dataType: 'json',
                async: true,
                success: function(res) {
                    $('#permission_table').DataTable().ajax.reload();
                    $('#formUpdatePermission').modal('toggle');
                    $(document).Toasts('create', {
                        title: 'Success',
                        class: 'bg-success',
                        body: 'Permission '+update_permission_name+' berhasil diubah.'
                    });
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

        $(document).on('click', '.add_permission', function(e) {
            e.preventDefault()
            let role_id = $(this).data('role_id')
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route("fetch-permission-in-role") }}',
                type: 'GET',
                data: {
                    role_id: role_id
                },
                dataType: 'json',
                async: true,
                success: function(res) {
                    $('#list_permission_data').empty()
                    $.each(res.data['permissions'], function(i, permission) {
                        $('#list_permission_data').append(`
                            <tr>
                                <td>
                                    <input class="form-check-input ms-3 permission-checkbox" type="checkbox" value="${permission.name}" id="${permission.name}" data-permission_id="${permission.id}">
                                </td>
                                <td>
                                    <p>${permission.name}</p>
                                </td>
                            </tr>
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

        $(document).on('change', '.permission-checkbox', function() {
            // let role_id 
            let permission_id = $(this).data('permission_id')
            var is_checked = $(this).is(':checked');
            
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route("add-or-remove-permission") }}',
                type: 'POST',
                data: {
                    role_id: update_role_id,
                    role_name: update_role_name
                },
                dataType: 'json',
                async: true,
            })
        })
    });
</script>