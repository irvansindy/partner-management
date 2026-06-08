<script>
    $(document).ready(function() {
        if (typeof $.fn.modal === 'undefined') {
            console.error('Bootstrap modal is not loaded!');
        }

        function csrfHeaders() {
            return { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') };
        }

        fetchMenu()

        function fetchMenu() {
            $.ajax({
                headers: csrfHeaders(),
                url: '{{ route('fetch-menu') }}',
                method: 'GET',
                success: function(res) {
                    const menus = res.data || []
                    if ($.fn.DataTable.isDataTable('#menu_table')) {
                        $('#menu_table').DataTable().clear().destroy();
                    }
                    $('#menu_table tbody').empty();
                    menus.forEach((menu, i) => {
                        let actions = `<button class="btn btn-sm btn-info detail_menu" data-detail_id="${menu.id}" data-toggle="modal" data-target="#formCreateMenu">Edit</button>`;
                        if (menu.type == 1) {
                            actions += ` <button class="btn btn-sm btn-secondary check_list_submenu" data-master_id="${menu.id}" data-toggle="modal" data-target="#ModalListSubMenu">Submenus</button>`;
                        }
                        actions += ` <button class="btn btn-sm btn-danger delete_menu" data-id="${menu.id}">Delete</button>`;

                        $('#menu_table tbody').append(`
                            <tr>
                                <td>${i + 1}</td>
                                <td>${menu.name_text || ''}</td>
                                <td>${menu.url_name || ''}</td>
                                <td>${actions}</td>
                            </tr>
                        `);
                    });

                    $('#menu_table').dataTable({
                        scrollX: true
                    })
                },
                error: function(xhr) {
                    alert('An error occurred: ' + xhr.responseText);
                }
            })
        }

        // Open create modal
        $(document).on('click', '#for_create_menu', function(e) {
            e.preventDefault();
            $('#form_create_new_menu')[0].reset();
            $('#menu_id').val('');
            $('#formCreateMenuLabel').html('Create Menu');
            $('#parent_menu').empty();
            // prepare roles select2
            $('#roles').val(null).trigger('change');
            $('[name="menu_type"]').prop('checked', false);
            // fetch parent options
            $.ajax({
                headers: csrfHeaders(),
                url: '{{ route('fetch-parent-menu') }}',
                method: 'GET',
                success: function(res) {
                    const parent_menu = res.data || [];
                    // attach change handler
                    $('[name="menu_type"]').off('change').on('change', function() {
                        if ($('[name="menu_type"]:checked').val() === 'children') {
                            $('#parent_menu').html(`
                                <label for="parent_id">Parent</label>
                                <select class="form-control" name="parent_id" id="parent_id" style="width: 100%"></select>
                            `);
                            $('#parent_id').select2({
                                dropdownParent: $('#formCreateMenu'),
                                data: parent_menu.map(parent => ({ id: parent.id, text: parent.name_text })),
                                placeholder: 'Select One',
                            });
                        } else {
                            $('#parent_menu').empty();
                        }
                    });
                    $('#roles').select2({ dropdownParent: $('#formCreateMenu'), placeholder: 'Select One' });
                }
            })
        })

        // Submit create or update
        $(document).on('click', '#submit_create_menu', function(e) {
            e.preventDefault();
            let menuId = $('#menu_id').val();
            let url = menuId ? '{{ route('update-menu') }}' : '{{ route('store-menu') }}';
            let form_data = new FormData($('#form_create_new_menu')[0]);
            $('#submit_create_menu').prop('disabled', true);
            $.ajax({
                headers: csrfHeaders(),
                url: url,
                method: 'POST',
                data: form_data,
                contentType: false,
                processData: false,
                success: function(res) {
                    $('#formCreateMenu').modal('hide');
                    setTimeout(function() { $('.modal-backdrop').remove(); $('body').removeClass('modal-open').css('padding-right', '') }, 400);
                    $(document).Toasts('create', { title: 'Success', class: 'bg-success', body: (res.meta && res.meta.message) ? res.meta.message : res.message || 'Success', delay: 4000, autohide: true });
                    fetchMenu();
                    $('#submit_create_menu').prop('disabled', false);
                },
                error: function(xhr) {
                    let msg = 'An error occurred';
                    try { msg = JSON.parse(xhr.responseText).message || JSON.parse(xhr.responseText).meta.message; } catch (e) {}
                    $(document).Toasts('create', { title: 'Error', class: 'bg-danger', body: msg, delay: 5000, autohide: true });
                    $('#submit_create_menu').prop('disabled', false);
                }
            })
        });

        // Edit / view menu
        $(document).on('click', '.detail_menu, .detail_submenu', function(e) {
            e.preventDefault();
            let detail_id = $(this).data('detail_id');
            if ($(this).hasClass('detail_menu')) {
                $('#formCreateMenuLabel').html('Edit Menu');
            } else {
                $('#formCreateMenuLabel').html('Edit Sub Menu');
                $('#ModalListSubMenu').modal('hide');
            }
            $('#parent_menu').empty();
            $.ajax({
                headers: csrfHeaders(),
                url: '{{ route('fetch-menu-by-id') }}',
                method: 'GET',
                data: { id: detail_id },
                success: function(res) {
                    const menu = res.data;
                    if (!menu) return;
                    $('#form_create_new_menu')[0].reset();
                    $('#menu_id').val(menu.id);
                    $('[name="menu_name"]').val(menu.name_text);
                    $('[name="menu_url"]').val(menu.url_name);
                    $('[name="menu_icon"]').val(menu.icon);
                    let menu_type = menu.type == 1 ? 'parent' : 'children';
                    $('[name="menu_type"]').filter('[value="' + menu_type + '"]').prop('checked', true);

                    // populate parent select if children
                    if (menu_type === 'children') {
                        // fetch parents
                        $.ajax({
                            headers: csrfHeaders(),
                            url: '{{ route('fetch-parent-menu') }}',
                            method: 'GET',
                            success: function(pr) {
                                const parent_menu = pr.data || [];
                                $('#parent_menu').html(`<label for="parent_id">Parent</label><select class="form-control" name="parent_id" id="parent_id" style="width: 100%"></select>`);
                                $('#parent_id').select2({ dropdownParent: $('#formCreateMenu'), data: parent_menu.map(p => ({ id: p.id, text: p.name_text })), placeholder: 'Select One' });
                                if (menu.parent_id) $('#parent_id').val(menu.parent_id).trigger('change');
                            }
                        })
                    } else {
                        $('#parent_menu').empty();
                    }

                    // roles
                    let roles = [];
                    try { roles = menu.permission && menu.permission.roles ? menu.permission.roles.map(r => r.id) : []; } catch (e) { roles = []; }
                    $('#roles').val(roles).trigger('change');
                    $('#roles').select2({ dropdownParent: $('#formCreateMenu'), placeholder: 'Select One' });
                },
                error: function(xhr) {
                    $(document).Toasts('create', { title: 'Error!', class: 'bg-danger', body: 'Gagal memuat data.', delay: 4000, autohide: true });
                }
            })
        })

        // Delete menu
        $(document).on('click', '.delete_menu', function(e) {
            e.preventDefault();
            if (!confirm('Yakin ingin menghapus menu ini?')) return;
            let id = $(this).data('id');
            $.ajax({
                headers: csrfHeaders(),
                url: '{{ route('delete-menu') }}',
                method: 'POST',
                data: { id: id },
                success: function(res) {
                    $(document).Toasts('create', { title: 'Success', class: 'bg-success', body: (res.meta && res.meta.message) ? res.meta.message : res.message || 'Deleted', delay: 3000, autohide: true });
                    fetchMenu();
                },
                error: function(xhr) {
                    $(document).Toasts('create', { title: 'Error', class: 'bg-danger', body: 'Gagal menghapus.', delay: 4000, autohide: true });
                }
            })
        })

        // List submenus
        $(document).on('click', '.check_list_submenu', function(e) {
            e.preventDefault()
            let master_id = $(this).data('master_id')
            $.ajax({
                headers: csrfHeaders(),
                url: '{{ route('fetch-children-menu') }}',
                method: 'GET',
                data: { id: master_id },
                success: function(res) {
                    const submenus = res.data || []
                    if ($.fn.DataTable.isDataTable('#list_submenu_table')) {
                        $('#list_submenu_table').DataTable().clear().destroy();
                    }
                    $('#list_submenu_table tbody').empty();
                    submenus.forEach((submenu, i) => {
                        $('#list_submenu_table tbody').append(`
                            <tr>
                                <td>${i + 1}</td>
                                <td>${submenu.name_text || ''}</td>
                                <td>${submenu.url_name || ''}</td>
                                <td>
                                    <button class="btn btn-outline-info mx-1 my-1 detail_submenu" data-toggle="modal" data-target="#formCreateMenu" data-detail_id="${submenu.id}" title="detail menu">
                                        <i class="fas fa-eye" aria-hidden="true"></i>
                                    </button>
                                </td>
                            </tr>
                        `);
                    });

                    $('#list_submenu_table').dataTable({ scrollX: true })
                },
                error: function(xhr) { alert('An error occurred: ' + xhr.responseText); }
            })
        })
    })
</script>
