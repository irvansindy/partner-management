<script>
    $(document).ready(function() {
        const userText = {
            notSet: @json(__('messages.User Management Not Set')),
            actions: @json(__('messages.User Management Actions')),
            viewUser: @json(__('messages.User Management View User')),
            deleteUser: @json(__('messages.User Management Delete User Label')),
            actionsFor: @json(__('messages.User Management Actions For', ['name' => '__NAME__'])),
            selectRole: @json(__('messages.User Management Select Role')),
            selectOffice: @json(__('messages.User Management Select Office')),
            selectDivision: @json(__('messages.User Management Select Division')),
            selectDepartment: @json(__('messages.User Management Select Department')),
            selectJobTitle: @json(__('messages.User Management Select Job Title')),
            selectSupervisor: @json(__('messages.User Management Select Supervisor')),
            success: @json(__('messages.User Management Success')),
            error: @json(__('messages.User Management Error')),
            validationFailed: @json(__('messages.User Management Validation Failed')),
            supportLoadFailed: @json(__('messages.User Management Support Load Failed')),
            createSuccess: @json(__('messages.User Management Create Success')),
            createFailed: @json(__('messages.User Management Create Failed')),
            completeRequired: @json(__('messages.User Management Complete Required')),
            detailLoadFailed: @json(__('messages.User Management Detail Load Failed')),
            updateSuccess: @json(__('messages.User Management Update Success')),
            updateFailed: @json(__('messages.User Management Update Failed')),
            serverError: @json(__('messages.User Management Server Error')),
            unexpectedError: @json(__('messages.User Management Unexpected Error')),
            deleteSuccess: @json(__('messages.User Management Delete Success')),
            deleteFailed: @json(__('messages.User Management Delete Failed')),
            deletePrompt: @json(__('messages.User Management Delete Prompt', ['name' => '__NAME__'])),
            tableEmpty: @json(__('messages.User Management Table Empty')),
            tableLoading: @json(__('messages.User Management Table Loading')),
            tableSearch: @json(__('messages.User Management Table Search')),
            tableInfo: @json(__('messages.User Management Table Info')),
            tableInfoEmpty: @json(__('messages.User Management Table Info Empty')),
            tableZeroRecords: @json(__('messages.User Management Table Zero Records')),
            tablePrevious: @json(__('messages.User Management Table Previous')),
            tableNext: @json(__('messages.User Management Table Next'))
        };

        function parseAjaxError(xhr, fallbackMessage) {
            if (xhr && xhr.responseJSON && typeof xhr.responseJSON === 'object') {
                return xhr.responseJSON;
            }

            let responseText = xhr && typeof xhr.responseText === 'string'
                ? xhr.responseText.trim()
                : '';

            if (responseText !== '') {
                try {
                    return JSON.parse(responseText);
                } catch (error) {
                    // The server may return an HTML error page or plain text.
                }
            }

            return {
                meta: {
                    code: xhr && xhr.status ? xhr.status : 0,
                    message: fallbackMessage
                }
            };
        }

        function getAjaxErrorMessage(response, fallbackMessage) {
            let message = response && response.meta ? response.meta.message : null;
            return typeof message === 'string' ? message : fallbackMessage;
        }

        function clearUserFormErrors(formSelector) {
            let form = $(formSelector);
            form.find('.user-field-error').text('');
            form.find('.is-invalid').removeClass('is-invalid');
            form.find('.select2-selection.is-invalid').removeClass('is-invalid');
        }

        function showUserFieldError(inputSelector, messageSelector, message) {
            let input = $(inputSelector);
            input.addClass('is-invalid');
            if (input.hasClass('select2-hidden-accessible')) {
                input.next('.select2').find('.select2-selection').addClass('is-invalid');
            }
            $(messageSelector).text(Array.isArray(message) ? message[0] : message);
        }

        function closeUserModal(selector) {
            let modal = $(selector);

            if (!modal.length) {
                return;
            }

            let closed = false;
            modal.one('hidden.bs.modal', function() {
                closed = true;
                $(this).removeAttr('style');

                if (!$('.modal.show').length) {
                    $('body').removeClass('modal-open').css('padding-right', '');
                    $('.modal-backdrop').remove();
                }
            });
            modal.modal('hide');

            setTimeout(function() {
                if (!closed && (modal.hasClass('show') || modal.is(':visible'))) {
                    modal.removeClass('show').attr('aria-hidden', 'true').css('display', 'none');
                    $('body').removeClass('modal-open').css('padding-right', '');
                    $('.modal-backdrop').remove();
                    modal.trigger('hidden.bs.modal');
                }
            }, 500);
        }

        $('#user_role, #user_job_title, #user_division, #user_department, #user_office, #user_parent').select2({
                language: { noResults: function () { return @json(__('settings.no_results')); }, searching: function () { return @json(__('settings.searching')); } },
            dropdownParent: $('#formCreateUser'),
            width: '100%'
        });
        $('#update_user_role, #update_user_job_title, #update_user_division, #update_user_department, #update_user_office, #update_user_parent').select2({
                language: { noResults: function () { return @json(__('settings.no_results')); }, searching: function () { return @json(__('settings.searching')); } },
            dropdownParent: $('#formUpdateUser'),
            width: '100%'
        });

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
                    "defaultContent": '<i>' + userText.notSet + '</i>'
                },
                {
                    "data": "roles[0].name",
                    "defaultContent": '<i>' + userText.notSet + '</i>'
                },
                {
                    'data': null,
                    title: userText.actions,
                    wrap: true,
                    "render": function(item) {
                        let viewButton = $('<button>', {
                            type: 'button',
                            class: 'table-action table-action--view detail_user',
                            html: '<i class="fas fa-eye" aria-hidden="true"></i>',
                            title: userText.viewUser,
                            'aria-label': userText.viewUser + ' ' + item.name,
                            'data-user_id': item.id,
                            'data-toggle': 'modal',
                            'data-target': '#formUpdateUser'
                        });
                        let deleteButton = $('<button>', {
                            type: 'button',
                            class: 'table-action table-action--delete delete_user',
                            html: '<i class="fas fa-trash" aria-hidden="true"></i>',
                            title: userText.deleteUser,
                            'aria-label': userText.deleteUser + ' ' + item.name,
                            'data-user_id': item.id,
                            'data-user_name': item.name,
                            'data-toggle': 'modal',
                            'data-target': '#confirmDeleteUser'
                        });

                        return $('<div>', {
                            class: 'table-actions',
                            role: 'group',
                            'aria-label': userText.actionsFor.replace('__NAME__', item.name)
                        })
                            .append(viewButton, deleteButton)
                            .prop('outerHTML');
                    }
                },
            ],
            language: Object.assign(@json(__('settings.datatable')), {
                emptyTable: userText.tableEmpty,
                processing: userText.tableLoading,
                search: userText.tableSearch,
                info: userText.tableInfo,
                infoEmpty: userText.tableInfoEmpty,
                zeroRecords: userText.tableZeroRecords,
                paginate: {
                    previous: userText.tablePrevious,
                    next: userText.tableNext
                }
            })
        })

        $(document).on('click', '#for_create_user', function(e) {
            e.preventDefault()
            $('#form_create_new_user')[0].reset()
            clearUserFormErrors('#form_create_new_user')
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route("fetch-role-office-dept") }}',
                type: 'GET',
                dataType: 'json',
                async: true,
                success: function(res) {
                    $('#user_role').empty().append($('<option>', { value: '', text: userText.selectRole }))
                    $.each(res.data.roles, function(i, role) {
                        $('#user_role').append(`
                            <option value="${role.name}">${role.name}</option>
                        `)
                    })

                    $('#user_office').empty().append($('<option>', { value: '', text: userText.selectOffice }))
                    $.each(res.data.offices, function(i, office) {
                        $('#user_office').append(`
                            <option value="${office.id}">${office.name}</option>
                        `)
                    })
                    
                    $('#user_department').empty().append($('<option>', { value: '', text: userText.selectDepartment }))
                    $.each(res.data.departments, function(i, office) {
                        $('#user_department').append(`
                            <option value="${office.id}">${office.name}</option>
                        `)
                    })

                    // divisions
                    $('#user_division').empty().append($('<option>', { value: '', text: userText.selectDivision }))
                    $.each(res.data.divisions, function(i, division) {
                        $('#user_division').append(`
                            <option value="${division.id}">${division.name}</option>
                        `)
                    })
                    
                    // job_titles
                    $('#user_job_title').empty().append($('<option>', { value: '', text: userText.selectJobTitle }))
                    $.each(res.data.job_titles, function(i, job_title) {
                        $('#user_job_title').append(`
                            <option value="${job_title.id}">${job_title.name} - ${job_title.level.name}</option>
                        `)
                    })

                    // parents
                    $('#user_parent').empty()
                    $('#user_parent').append($('<option>', { value: '', text: userText.selectSupervisor }))
                    $.each(res.data.parents, function(i, parent) {
                        $('#user_parent').append(`
                            <option value="${parent.id}">${parent.name}</option>
                        `)
                    })
                },
                error: function(xhr) {
                    let response_error = parseAjaxError(xhr, userText.supportLoadFailed);
                    $(document).Toasts('create', {
                        title: userText.error,
                        class: 'bg-danger',
                        body: getAjaxErrorMessage(response_error, userText.supportLoadFailed)
                    });
                }
            })
        })

        $(document).on('submit', '#form_create_new_user', function(e) {
            e.preventDefault()
            let user_name = $('#user_name').val()
            let user_email = $('#user_email').val()
            let user_role = $('#user_role').val()
            let user_nik = $('#user_nik').val()
            let user_employee_id = $('#user_employee_id').val()
            let user_office = $('#user_office').val()
            let user_job_title = $('#user_job_title').val()
            let user_division = $('#user_division').val()
            let user_department = $('#user_department').val()
            let user_parent = $('#user_parent').val()
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
                    nik: user_nik,
                    employee_id: user_employee_id,
                    office: user_office,
                    job_title: user_job_title,
                    division: user_division,
                    department: user_department,
                    parent: user_parent,
                },
                dataType: 'json',
                async: true,
                success: function(res) {
                    closeUserModal('#formCreateUser');
                    $('#user_table').DataTable().ajax.reload();
                    $(document).Toasts('create', {
                        title: userText.success,
                        class: 'bg-success',
                        body: userText.createSuccess,
                        delay: 10000,
                        autohide: true,
                        fade: true,
                        close: true,
                        autoremove: true,
                    });
                },
                error: function(xhr, status, error) {
                    let response_error = parseAjaxError(xhr, userText.createFailed);
                    let meta = response_error.meta || {};
                    let validationErrors = meta.message && meta.message.errors;

                    if (meta.code === 500 || meta.code === 400 || !validationErrors) {
                        $(document).Toasts('create', {
                            title: userText.error,
                            class: 'bg-danger',
                            body: getAjaxErrorMessage(response_error, userText.createFailed),
                            delay: 10000,
                            autohide: true,
                            fade: true,
                            close: true,
                            autoremove: true,
                        });
                    } else {
                        clearUserFormErrors('#form_create_new_user')
                        $.each(validationErrors, function(i, value) {
                            showUserFieldError('#user_' + i, '#message_user_' + i, value)
                        })
                        $(document).Toasts('create', {
                            title: userText.error,
                            class: 'bg-danger',
                            body: userText.completeRequired,
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
            let user_id = $(this).data('user_id');

            // Reset form dan error message
            $('#form_detail_current_user')[0].reset();
            clearUserFormErrors('#form_detail_current_user');

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route("detail-user") }}',
                type: 'GET',
                data: { id: user_id },
                dataType: 'json',
                async: true,
                success: function(res) {
                    const user = res.data.user;

                    // Isi input name & email
                    $('#update_user_name').val(user.name);
                    $('#update_user_email').val(user.email);
                    $('#update_user_nik').val(user.nik);
                    $('#update_user_employee_id').val(user.employee_id);
                    $('#update_current_role').val(user.roles.length ? user.roles[0].name : '');
                    $('#form_detail_current_user input:not([type="hidden"])').each(function () {
                        $(this).prev('label').toggleClass('active', Boolean($(this).val()));
                    });
                    
                    // Populate dropdown Role
                    populateDropdown(
                        '#update_user_role',
                        res.data.roles,
                        user.roles.length ? user.roles[0].name : null,
                        userText.selectRole
                    );

                    // Populate dropdown Parent User
                    populateDropdown(
                        '#update_user_parent',
                        res.data.parents,
                        user.parent_user_id ? user.parent_user_id : null,
                        userText.selectSupervisor
                    );

                    // Populate dropdown Office
                    populateDropdown(
                        '#update_user_office',
                        res.data.offices,
                        user.office ? user.office.id : null,
                        userText.selectOffice
                    );

                    // Populate dropdown Department
                    populateDropdown(
                        '#update_user_department',
                        res.data.departments,
                        user.dept ? user.dept.id : null,
                        userText.selectDepartment
                    );

                    // Populate dropdown Division
                    populateDropdown(
                        '#update_user_division',
                        res.data.divisions,
                        user.division ? user.division.id : null,
                        userText.selectDivision
                    );

                    // Populate dropdown Job Title
                    populateDropdown(
                        '#update_user_job_title',
                        res.data.job_titles,
                        user.job_title ? user.job_title.id : null,
                        userText.selectJobTitle
                    );

                    // Set data attribute untuk tombol Update
                    $('.update_user').data('update_user_id', user.id);
                },
                error: function(xhr) {
                    let response_error = parseAjaxError(xhr, userText.detailLoadFailed);
                    $(document).Toasts('create', {
                        title: userText.error,
                        class: 'bg-danger',
                        body: getAjaxErrorMessage(response_error, userText.detailLoadFailed)
                    });
                }
            });
        });

        function populateDropdown(selector, dataList, selectedId = null, placeholder = '') {
            $(selector).empty();
            $(selector).append(`<option value="">${placeholder}</option>`);

            // Cek jika selector adalah role
            const isRoleDropdown = selector.includes('user_role');

            $.each(dataList, function (i, item) {
                // Pakai name sebagai value untuk Role, id untuk lainnya
                let value = isRoleDropdown ? item.name : item.id;

                // Selected jika value sama dengan selectedId
                let selected = (value === selectedId) ? 'selected' : '';

                $(selector).append(`
                    <option value="${value}" ${selected}>${item.name}</option>
                `);
            });

            if ($(selector).hasClass('select2-hidden-accessible')) {
                $(selector).select2('destroy');
            }
            $(selector).select2({
                language: { noResults: function () { return @json(__('settings.no_results')); }, searching: function () { return @json(__('settings.searching')); } },
                dropdownParent: $(selector).closest('.modal'),
                width: '100%'
            });
        }

        $(document).on('submit', '#form_detail_current_user', function (e) {
            e.preventDefault();
            let update_user_id = $(this).data('update_user_id');

            if (!update_user_id) {
                update_user_id = $('#update_user').data('update_user_id');
            }

            // Ambil semua data input form
            let payload = {
                id: update_user_id,
                name: $('#update_user_name').val(),
                email: $('#update_user_email').val(),
                nik: $('#update_user_nik').val(),
                employee_id: $('#update_user_employee_id').val(),
                current_role: $('#update_current_role').val(),
                role: $('#update_user_role').val(),
                office: $('#update_user_office').val(),
                division: $('#update_user_division').val(),
                department: $('#update_user_department').val(),
                job_title: $('#update_user_job_title').val(),
                parent_user: $('#update_user_parent').val(),
            };

            // Clear semua error message sebelumnya
            clearUserFormErrors('#form_detail_current_user');

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route("update-user") }}',
                type: 'POST',
                data: payload,
                dataType: 'json',
                async: true,
                success: function (res) {
                    // Tutup modal update
                    closeUserModal('#formUpdateUser');

                    // Reload DataTable
                    $('#user_table').DataTable().ajax.reload(null, false);

                    // Tampilkan toast success
                    $(document).Toasts('create', {
                        title: userText.success,
                        class: 'bg-success',
                        body: res.meta.message ?? userText.updateSuccess,
                        delay: 8000,
                        autohide: true
                    });
                },
                error: function (xhr) {
                    let response_error = parseAjaxError(xhr, userText.updateFailed);
                    let meta = response_error.meta || {};
                    let message = meta.message;

                    if (meta.code === 500 || meta.code === 400) {
                        // Error server
                        $(document).Toasts('create', {
                            title: userText.error,
                            class: 'bg-danger',
                            body: getAjaxErrorMessage(response_error, userText.serverError),
                            delay: 8000,
                            autohide: true
                        });
                    } else if (message && message.errors) {
                        // Error validasi field
                        $.each(message.errors, function (field, messages) {
                            showUserFieldError(
                                '#update_user_' + field,
                                '#message_update_user_' + field,
                                messages
                            );
                        });

                        $(document).Toasts('create', {
                            title: userText.validationFailed,
                            class: 'bg-warning',
                            body: userText.completeRequired,
                            delay: 8000,
                            autohide: true
                        });
                    } else {
                        // Error tak terduga
                        $(document).Toasts('create', {
                            title: userText.error,
                            class: 'bg-danger',
                            body: userText.unexpectedError,
                            delay: 8000,
                            autohide: true
                        });
                    }
                }
            });
        });

        $(document).on('click', '.delete_user', function() {
            let delete_user_id = $(this).data('user_id')
            let delete_user_name = $(this).data('user_name')
            $('#delete_user_name').text(userText.deletePrompt.replace('__NAME__', delete_user_name))
            $('.confirm_delete_user').data('delete_user_id', delete_user_id)
        })

        $(document).on('click', '#confirm_delete_user', function(e) {
            e.preventDefault()
            let delete_user_id = $(this).data('delete_user_id')
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route("delete-user") }}',
                type: 'POST',
                data: {
                    id: delete_user_id,
                },
                dataType: 'json',
                async: true,
                success: function(res) {
                    closeUserModal('#confirmDeleteUser');
                    $('#user_table').DataTable().ajax.reload(null, false);
                    $(document).Toasts('create', {
                        title: userText.success,
                        class: 'bg-success',
                        body: userText.deleteSuccess
                    });
                },
                error: function(xhr) {
                    $(document).Toasts('create', {
                        title: userText.error,
                        class: 'bg-danger',
                        body: userText.deleteFailed
                    });
                }
            })

        })
    })
</script>
