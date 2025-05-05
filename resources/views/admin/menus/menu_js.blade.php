<script>
    $(document).ready(function() {
        fetchMenu()

        function fetchMenu() {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('fetch-menu') }}',
                method: 'GET',
                success: function(res) {
                    const menus = res.data
                    $('#menu_table').DataTable().clear().destroy();
                    $('#menu_table tbody').empty();
                    menus.forEach((menu, i) => {
                        $('#menu_table tbody').append(`
                            <tr>
                                <td>${i + 1}</td>
                                <td>${menu.name_text != null ? menu.name_text : 'not set'}</td>
                                <td>${menu.url_name != null ? menu.url_name : 'not set'}</td>
                                <td>
                                    <button class="btn btn-outline-secondary mx-1 my-1 check_list_submenu" id="" style="float: right !important;" data-toggle="modal" data-target="#ModalListSubMenu" data-master_id="${menu.id}" title="list submenu">
                                        <i class="fa fa-list-alt" aria-hidden="true"></i>
                                    </button>
                                    <button class="btn btn-outline-info mx-1 my-1 detail_menu" id="" style="float: right !important;" data-toggle="modal" data-target="#formCreateMenu" data-detail_id="${menu.id}" title="detail menu">
                                        <i class="fas fa-eye" aria-hidden="true"></i>
                                    </button>
                                </td>
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

        $(document).on('click', '#for_create_menu', function(e) {
            e.preventDefault()
            $('#form_create_new_menu')[0].reset()
            $('#formCreateMenuLabel').html('Data Menu')
            $('#parent_id').empty()
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('fetch-parent-menu') }}',
                method: 'GET',
                success: function(res) {
                    const parent_menu = res.data
                    $('#parent_menu').empty();
                    $('[name="menu_type"]').on('change', function () {
                        if ($('[name="menu_type"]:checked').val() === 'children') { 
                            $('#parent_menu').empty();
                            $('#parent_menu').html(`
                                <label for="parent_id">Permission</label>
                                <select class="form-control" name="parent_id" id="parent_id" style="width: 100%">

                                </select>
                            `);
                            $('#parent_id').append(`
                                <option value="">Select One</option>
                            `)
                            $('#parent_id').select2({
                                dropdownParent: $('#formCreateMenu'),
                                data: parent_menu.map(parent => ({
                                    id: parent.id,
                                    text: parent.name_text
                                })),
                                placeholder: 'Select One',
                                // allowClear: true,
                            })
                        } else {
                            $('#parent_menu').empty();
                        }
                    });
                    $('#roles').select2({
                        dropdownParent: $('#formCreateMenu'),
                        placeholder: 'Select One',
                    })
                }
            })
        })

        $(document).on('click', '#submit_create_menu', function(e) {
            e.preventDefault()
            let form_data = new FormData($('#form_create_new_menu')[0])

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('store-menu') }}',
                method: 'POST',
                data: form_data,
                contentType: false,
                processData: false,
                success: function(res){
                    $('#formCreateMenu').modal('hide')
                    $(document).Toasts('create', {
                        title: 'Success',
                        class: 'bg-success',
                        body: res.meta.message,
                        delay: 5000,
                        autohide: true,
                        fade: true,
                        close: true,
                        autoremove: true,
                    });
                    fetchMenu()
                },
                error: function(xhr) {
                    let response_error = JSON.parse(xhr.responseText)
                    $(document).Toasts('create', {
                        title: 'Error!',
                        class: 'bg-error',
                        body: 'gagal memuat data, silahkan hubungi admin.',
                        delay: 5000,
                        autohide: true,
                        fade: true,
                        close: true,
                        autoremove: true,
                    });
                }
            })
        });

        $(document).on('click', '.detail_menu, .detail_submenu', function(e) {
            e.preventDefault()
            let detail_id = $(this).data('detail_id')
            
            // ✅ Cek class mana yang diklik
            if ($(this).hasClass('detail_menu')) {
                $('#formCreateMenuLabel').html('Data Menu')
            } else if ($(this).hasClass('detail_submenu')) {
                $('#formCreateMenuLabel').html('Data Sub Menu')
                $('#ModalListSubMenu').modal('hide')
            }
            $('#parent_menu').empty();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('fetch-menu-by-id') }}',
                method: 'GET',
                data: {
                    id: detail_id
                },
                success: function(res) {
                    const menu = res.data
                    let type = menu.type
                    let menu_type = type == 1 ? 'parent' : 'children'
                    let roles = menu.permission.roles
                    $('#form_create_new_menu')[0].reset()
                    $('[name="menu_name"]').val(menu.name_text)
                    $('[name="menu_url"]').val(menu.url_name)
                    $('[name="menu_icon"]').val(menu.icon)
                    $('[name="menu_type"]').filter('[value="' + menu_type + '"]').prop('checked', true);
                    
                    // const roleIds = selectedRoles.map(role => role.id);
                    $('[name="roles[]"]').val(roles.map(role => role.id)).trigger('change')

                    $('#roles').select2({
                        dropdownParent: $('#formCreateMenu'),
                        placeholder: 'Select One',
                    })
                },
                error : function(xhr) {
                    
                    $(document).Toasts('create', {
                        title: 'Error!',
                        class: 'bg-error',
                        body: 'gagal memuat data, silahkan hubungi admin.',
                        delay: 5000,
                        autohide: true,
                        fade: true,
                        close: true,
                        autoremove: true,
                    });
                }
            })
        })

        $(document).on('click', '.check_list_submenu', function(e) {
            e.preventDefault()
            let master_id = $(this).data('master_id')
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('fetch-children-menu') }}',
                method: 'GET',
                data: {
                    id: master_id
                },
                success: function(res) {
                    const submenus = res.data
                    $('#list_submenu_table').DataTable().clear().destroy();
                    $('#list_submenu_table tbody').empty();
                    submenus.forEach((submenu, i) => {
                        $('#list_submenu_table tbody').append(`
                            <tr>
                                <td>${i + 1}</td>
                                <td>${submenu.name_text != null ? submenu.name_text : 'not set'}</td>
                                <td>${submenu.url_name != null ? submenu.url_name : 'not set'}</td>
                                <td>
                                    <button class="btn btn-outline-info mx-1 my-1 detail_submenu" id="" style="float: right !important;" data-toggle="modal" data-target="#formCreateMenu" data-detail_id="${submenu.id}" title="detail menu">
                                        <i class="fas fa-eye" aria-hidden="true"></i>
                                    </button>
                                </td>
                            </tr>
                        `);
                    });

                    $('#list_submenu_table').dataTable({
                        scrollX: true
                    })
                },
                error: function(xhr) {
                    alert('An error occurred: ' + xhr.responseText);
                }
            })
        })
    })
</script>