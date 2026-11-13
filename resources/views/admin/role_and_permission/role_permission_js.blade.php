<script>
    const rolePermissionText = {
        success: @json(__('messages.Role Permission Success')),
        error: @json(__('messages.Role Permission Error')),
        notSet: @json(__('messages.Role Permission Not Set')),
        actions: @json(__('messages.Role Permission Actions')),
        viewRole: @json(__('messages.Role Permission View Role')),
        deleteRole: @json(__('messages.Role Permission Delete Role')),
        viewPermission: @json(__('messages.Role Permission View Permission')),
        managePermissions: @json(__('messages.Role Permission Manage Permissions')),
        roleActions: @json(__('messages.Role Permission Role Actions')),
        permissionActions: @json(__('messages.Role Permission Permission Actions')),
        selectMenu: @json(__('messages.Role Permission Select Menu')),
        deleteWarning: @json(__('messages.Role Permission Delete Warning', ['name' => ':name'])),
        deleteSuccess: @json(__('messages.Role Permission Delete Success')),
        role: @json(__('messages.Role Permission Role')),
        permission: @json(__('messages.Role Permission Permission')),
        saveSuccess: @json(__('messages.Role Permission Save Success', ['type' => ':type', 'name' => ':name'])),
        updateSuccess: @json(__('messages.Role Permission Update Success', ['type' => ':type', 'name' => ':name'])),
        tableEmpty: @json(__('messages.Role Permission Table Empty')),
        tableLoading: @json(__('messages.Role Permission Table Loading')),
        tableSearch: @json(__('messages.Role Permission Table Search')),
        tableInfo: @json(__('messages.Role Permission Table Info')),
        tableInfoEmpty: @json(__('messages.Role Permission Table Info Empty')),
        tableZeroRecords: @json(__('messages.Role Permission Table Zero Records')),
        tablePrevious: @json(__('messages.Role Permission Table Previous')),
        tableNext: @json(__('messages.Role Permission Table Next')),
    };

    function rolePermissionMessage(template, type, name) {
        return template.replace(':type', type).replace(':name', name);
    }

    const rolePermissionTableLanguage = {
        emptyTable: rolePermissionText.tableEmpty,
        loadingRecords: rolePermissionText.tableLoading,
        processing: rolePermissionText.tableLoading,
        search: rolePermissionText.tableSearch,
        info: rolePermissionText.tableInfo,
        infoEmpty: rolePermissionText.tableInfoEmpty,
        zeroRecords: rolePermissionText.tableZeroRecords,
        paginate: { previous: rolePermissionText.tablePrevious, next: rolePermissionText.tableNext }
    };

    $(document).ready(function () {
        function closeRolePermissionModal(selector) {
            let modal = $(selector);

            if (!modal.length) {
                return;
            }

            modal.modal('hide');

            setTimeout(function() {
                if (modal.hasClass('show') || modal.is(':visible')) {
                    modal.removeClass('show').attr('aria-hidden', 'true').css('display', 'none');
                    $('body').removeClass('modal-open').css('padding-right', '');
                    $('.modal-backdrop').remove();
                }
            }, 350);
        }

        $('#role_table').DataTable({
            processing: true,
            language: Object.assign(@json(__('settings.datatable')), rolePermissionTableLanguage),
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
                    "defaultContent": rolePermissionText.notSet
                },
                {
                    'data': null,
                    title: rolePermissionText.actions,
                    wrap: true,
                    "render": function(item) {
                        let viewButton = $('<button>', {
                            type: 'button',
                            class: 'table-action table-action--view detail_role',
                            html: '<i class="fas fa-eye" aria-hidden="true"></i>',
                            title: rolePermissionText.viewRole,
                            'aria-label': rolePermissionText.viewRole,
                            'data-role_id': item.id,
                            'data-role_name': item.name,
                            'data-toggle': 'modal',
                            'data-target': '#formUpdateRole'
                        });
                        let permissionButton = $('<button>', {
                            type: 'button',
                            class: 'table-action table-action--copy add_permission',
                            html: '<i class="fas fa-key" aria-hidden="true"></i>',
                            title: rolePermissionText.managePermissions,
                            'aria-label': rolePermissionText.managePermissions,
                            'data-role_id': item.id,
                            'data-toggle': 'modal',
                            'data-target': '#formAddPermission'
                        });
                        let deleteButton = $('<button>', {
                            type: 'button',
                            class: 'table-action table-action--delete delete_role',
                            html: '<i class="fas fa-trash" aria-hidden="true"></i>',
                            title: rolePermissionText.deleteRole,
                            'aria-label': rolePermissionText.deleteRole,
                            'data-role_id': item.id,
                            'data-role_name': item.name,
                            'data-toggle': 'modal',
                            'data-target': '#confirmDeleteRole'
                        });

                        return $('<div>', {
                            class: 'table-actions',
                            role: 'group',
                            'aria-label': rolePermissionText.roleActions
                        })
                            .append(viewButton, permissionButton, deleteButton)
                            .prop('outerHTML');
                    }
                },
            ]
        })
        
        $('#permission_table').DataTable({
            processing: true,
            language: Object.assign(@json(__('settings.datatable')), rolePermissionTableLanguage),
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
                    "defaultContent": rolePermissionText.notSet
                },
                {
                    'data': null,
                    title: rolePermissionText.actions,
                    wrap: true,
                    "render": function(item) {
                        let viewButton = $('<button>', {
                            type: 'button',
                            class: 'table-action table-action--view detail_permission',
                            html: '<i class="fas fa-eye" aria-hidden="true"></i>',
                            title: rolePermissionText.viewPermission,
                            'aria-label': rolePermissionText.viewPermission,
                            'data-permission_id': item.id,
                            'data-toggle': 'modal',
                            'data-target': '#formUpdatePermission'
                        });

                        return $('<div>', {
                            class: 'table-actions',
                            role: 'group',
                            'aria-label': rolePermissionText.permissionActions
                        })
                            .append(viewButton)
                            .prop('outerHTML');
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
                    closeRolePermissionModal('#formCreateRole');
                    $('#role_table').DataTable().ajax.reload();
                    $(document).Toasts('create', {
                        title: rolePermissionText.success,
                        class: 'bg-success',
                        body: rolePermissionMessage(rolePermissionText.saveSuccess, rolePermissionText.role, role_name)
                    });
                },
                error: function(xhr, status, error) {
                    let response_error = JSON.parse(xhr.responseText);
                    $(document).Toasts('create', {
                        title: rolePermissionText.error,
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
                                    <option value="">${rolePermissionText.selectMenu}</option>
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
                        title: rolePermissionText.error,
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
                    closeRolePermissionModal('#formCreatePermission');
                    $('#permission_table').DataTable().ajax.reload();
                    $(document).Toasts('create', {
                        title: rolePermissionText.success,
                        class: 'bg-success',
                        body: rolePermissionMessage(rolePermissionText.saveSuccess, rolePermissionText.permission, permission_name)
                    });
                },
                error: function(xhr, status, error) {
                    let response_error = JSON.parse(xhr.responseText)
                    $(document).Toasts('create', {
                        title: rolePermissionText.error,
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
                        .prev('label').toggleClass('active', Boolean(res.data.name));
                },
                error: function(xhr) {
                    let response_error = JSON.parse(xhr.responseText)
                    $(document).Toasts('create', {
                        title: rolePermissionText.error,
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
                    closeRolePermissionModal('#formUpdateRole');
                    $('#role_table').DataTable().ajax.reload();
                    $(document).Toasts('create', {
                        title: rolePermissionText.success,
                        class: 'bg-success',
                        body: rolePermissionMessage(rolePermissionText.updateSuccess, rolePermissionText.role, update_role_name)
                    });
                },
                error: function(xhr, status, error) {
                    let response_error = JSON.parse(xhr.responseText);
                    $(document).Toasts('create', {
                        title: rolePermissionText.error,
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
                        .prev('label').toggleClass('active', Boolean(res.data.name));
                },
                error: function(xhr) {
                    let response_error = JSON.parse(xhr.responseText)
                    $(document).Toasts('create', {
                        title: rolePermissionText.error,
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
                    closeRolePermissionModal('#formUpdatePermission');
                    $('#permission_table').DataTable().ajax.reload();
                    $(document).Toasts('create', {
                        title: rolePermissionText.success,
                        class: 'bg-success',
                        body: rolePermissionMessage(rolePermissionText.updateSuccess, rolePermissionText.permission, update_permission_name)
                    });
                },
                error: function(xhr) {
                    let response_error = JSON.parse(xhr.responseText)
                    $(document).Toasts('create', {
                        title: rolePermissionText.error,
                        class: 'bg-danger',
                        body: response_error.meta.message
                    });
                }
            })
        })

        $(document).on('click', '.add_permission', function(e) {
            e.preventDefault()
            let role_id = $(this).data('role_id')
            // alert(role_id)
            $('.permission-checkbox').removeAttr('checked');
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
                    $('#role_id_for_permission').val(role_id)
                    $('#list_permission_data').empty()
                    let permissions = res.data.permissions;
                    let permission_by_role = res.data.permission_by_role;
                    
                    $.each(permissions, function(i, permission) {
                        let is_checked = permission_by_role.some(rp => rp.id === permission.id) ? 'checked' : '';
                        $('#list_permission_data').append(`
                            <tr>
                                <td>
                                    <input class="form-check-input ms-3 permission-checkbox" type="checkbox" name="permissions[]" value="${permission.name}" id="${permission.name}" data-permission_id="${permission.id}" ${is_checked}>
                                </td>
                                <td>
                                    <label class="form-check-label" for="${permission.name}">
                                        ${permission.name}
                                    </label>
                                </td>
                            </tr>
                        `)
                    })
                },
                error: function(xhr) {
                    let response_error = JSON.parse(xhr.responseText)
                    $(document).Toasts('create', {
                        title: rolePermissionText.error,
                        class: 'bg-danger',
                        body: response_error.meta.message
                    });
                }
            })
            
        })

        $(document).on('change', '.permission-checkbox', function() {
            var formData = new FormData($('#form_add_remove_permissions')[0]);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route("add-or-remove-permission") }}',
                type: 'POST',
                data: formData,
                processData: false,   // Prevent jQuery from processing the data
                contentType: false,   // Prevent jQuery from setting content type
                cache: false,
                success: function(res) {
                    $('#role_table').DataTable().ajax.reload();
                    // $('#formAddPermission').modal('toggle');
                    $(document).Toasts('create', {
                        title: rolePermissionText.success,
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
                    $(document).Toasts('create', {
                        title: rolePermissionText.error,
                        class: 'bg-danger',
                        body: response_error.meta.message,
                        delay: 10000,
                        autohide: true,
                        fade: true,
                        close: true,
                        autoremove: true,
                    });
                }
            })
        })

        $(document).on('click', '.delete_role', function() {
            const roleId = $(this).data('role_id');
            const roleName = $(this).data('role_name');
            $('#delete_role_id').val(roleId);
            $('#delete_role_warning').text(rolePermissionText.deleteWarning.replace(':name', roleName));
        });

        $(document).on('click', '#confirm_delete_role', function() {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route("delete-role") }}',
                type: 'POST',
                data: { role_id: $('#delete_role_id').val() },
                dataType: 'json',
                success: function() {
                    closeRolePermissionModal('#confirmDeleteRole');
                    $('#role_table').DataTable().ajax.reload();
                    $(document).Toasts('create', {
                        title: rolePermissionText.success,
                        class: 'bg-success',
                        body: rolePermissionText.deleteSuccess
                    });
                },
                error: function(xhr) {
                    const response = xhr.responseJSON;
                    $(document).Toasts('create', {
                        title: rolePermissionText.error,
                        class: 'bg-danger',
                        body: response && response.meta ? response.meta.message : rolePermissionText.error
                    });
                }
            });
        });
    });
</script>
