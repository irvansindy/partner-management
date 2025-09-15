<script>
    $(document).ready(function() {
        $('#user_role, #user_job_title, #user_division, #user_department, #user_office, #user_parent').select2({
            dropdownParent: $('#formCreateUser'),
            width: '100%', // biar responsif
            tags: "true",
        })
        $('#update_user_role').select2({
            dropdownParent: $('#formUpdateUser'),
            width: '100%', // biar responsif
        })
        $('#update_user_office').select2({
            dropdownParent: $('#formUpdateUser'),
            width: '100%', // biar responsif
        })
        $('#update_user_department').select2({
            dropdownParent: $('#formUpdateUser'),
            width: '100%', // biar responsif
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

                    // divisions
                    $('#user_division').empty()
                    $.each(res.data.divisions, function(i, division) {
                        $('#user_division').append(`
                            <option value="${division.id}">${division.name}</option>
                        `)
                    })
                    
                    // job_titles
                    $('#user_job_title').empty()
                    $.each(res.data.job_titles, function(i, job_title) {
                        $('#user_job_title').append(`
                            <option value="${job_title.id}">${job_title.name} - ${job_title.level.name}</option>
                        `)
                    })

                    // parents
                    $('#user_parent').empty()
                    $('#user_parent').append(`
                        <option value="">-- Optional --</option>
                    `)
                    $.each(res.data.parents, function(i, parent) {
                        $('#user_parent').append(`
                            <option value="${parent.id}">${parent.name}</option>
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
            let user_id = $(this).data('user_id');

            // Reset form dan error message
            $('#form_detail_current_user')[0].reset();
            $('.message_update_user_name').text('');
            $('.message_update_user_email').text('');
            $('.message_update_user_nik').text('');
            $('.message_update_user_employee_id').text('');
            $('.message_update_user_role').text('');
            $('.message_update_user_office').text('');
            $('.message_update_user_department').text('');

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
                    console.log(user.parent_user_id);
                    
                    // Populate dropdown Role
                    populateDropdown(
                        '#update_user_role',
                        res.data.roles,
                        user.roles.length ? user.roles[0].name : null,
                        '-- Pilih Role --'
                    );

                    // Populate dropdown Parent User
                    populateDropdown(
                        '#update_user_parent',
                        res.data.parents,
                        user.parent_user_id ? user.parent_user_id : null,
                        '-- Pilih Parent User --'
                    );

                    // Populate dropdown Office
                    populateDropdown(
                        '#update_user_office',
                        res.data.offices,
                        user.office ? user.office.id : null,
                        '-- Pilih Office --'
                    );

                    // Populate dropdown Department
                    populateDropdown(
                        '#update_user_department',
                        res.data.departments,
                        user.dept ? user.dept.id : null,
                        '-- Pilih Department --'
                    );

                    // Populate dropdown Division
                    populateDropdown(
                        '#update_user_division',
                        res.data.divisions,
                        user.division ? user.division.id : null,
                        '-- Pilih Division --'
                    );

                    // Populate dropdown Job Title
                    populateDropdown(
                        '#update_user_job_title',
                        res.data.job_titles,
                        user.job_title ? user.job_title.id : null,
                        '-- Pilih Job Title --'
                    );

                    // Set data attribute untuk tombol Update
                    $('.update_user').data('update_user_id', user.id);
                },
                error: function(xhr) {
                    let response_error = JSON.parse(xhr.responseText);
                    $(document).Toasts('create', {
                        title: 'Error',
                        class: 'bg-danger',
                        body: response_error.meta.message
                    });
                }
            });
        });

        function populateDropdown(selector, dataList, selectedId = null, placeholder = '-- Pilih --') {
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
                dropdownParent: $('#formDetailUser, #formUpdateUser'),
                width: '100%'
            });
        }

        $(document).on('click', '.update_user', function () {
            let update_user_id = $(this).data('update_user_id');

            // Ambil semua data input form
            let payload = {
                id: update_user_id,
                name: $('#update_user_name').val(),
                email: $('#update_user_email').val(),
                current_role: $('#update_current_role').val(),
                role: $('#update_user_role').val(),
                office: $('#update_user_office').val(),
                division: $('#update_user_division').val(),
                department: $('#update_user_department').val(),
                job_title: $('#update_user_job_title').val(),
                parent_user: $('#update_user_parent').val(),
            };

            // Clear semua error message sebelumnya
            $('.text-danger').text('');

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
                    // Reload DataTable
                    $('#user_table').DataTable().ajax.reload(null, false);

                    // Tutup modal update
                    $('#formUpdateUser').modal('hide');

                    // Tampilkan toast success
                    $(document).Toasts('create', {
                        title: 'Success',
                        class: 'bg-success',
                        body: res.meta.message ?? 'User berhasil diubah.',
                        delay: 8000,
                        autohide: true
                    });
                },
                error: function (xhr) {
                    let response_error = JSON.parse(xhr.responseText);

                    if (response_error.meta && (response_error.meta.code === 500 || response_error.meta.code === 400)) {
                        // Error server
                        $(document).Toasts('create', {
                            title: 'Error',
                            class: 'bg-danger',
                            body: response_error.meta.message ?? 'Terjadi kesalahan server.',
                            delay: 8000,
                            autohide: true
                        });
                    } else if (response_error.meta && response_error.meta.message && response_error.meta.message.errors) {
                        // Error validasi field
                        $.each(response_error.meta.message.errors, function (field, messages) {
                            $('#message_update_user_' + field).text(messages[0]);
                        });

                        $(document).Toasts('create', {
                            title: 'Validasi Gagal',
                            class: 'bg-warning',
                            body: 'Silakan lengkapi data yang masih kosong atau salah.',
                            delay: 8000,
                            autohide: true
                        });
                    } else {
                        // Error tak terduga
                        $(document).Toasts('create', {
                            title: 'Error',
                            class: 'bg-danger',
                            body: 'Terjadi kesalahan tidak terduga.',
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