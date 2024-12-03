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
                                    <button class="btn btn-outline-danger mx-1 my-1 delete_menu" id="" style="float: right !important;" data-toggle="" data-target="#" data-delete_id="${menu.id}" title="delete menu">
                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                    </button>
                                    <button class="btn btn-outline-secondary mx-1 my-1 check_list_submenu" id="" style="float: right !important;" data-toggle="modal" data-target="#listSubMenu" data-master_id="${menu.id}" title="list submenu">
                                        <i class="fa fa-list-alt" aria-hidden="true"></i>
                                    </button>
                                    <button class="btn btn-outline-info mx-1 my-1 detail_menu" id="" style="float: right !important;" data-toggle="modal" data-target="#createMenu" data-detail_id="${menu.id}" title="detail menu">
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
            $('#menu_permission').empty()
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('fetch-permission-view') }}',
                method: 'GET',
                success: function(res) {
                    const list_permission = res.data
                    // console.log(list_permission);
                    $('#menu_permission').empty()
                    $('#menu_permission').append(`<option value="">Select One</option>`)
                    $.each(list_permission, (i, permission) => {
                        $('#menu_permission').append(`<option value="${permission.name}">${permission.name}</option>`)
                    })
                }
            })
        })

        $(document).on('click', '#submit_create_menu', function(e) {
            e.preventDefault()
            let menu_name = $('#menu_name').val()
            let menu_url = $('#menu_url').val()
            let menu_icon = $('#menu_icon').val()

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('store-menu') }}',
                method: 'POST',
                data: {
                    name: menu_name,
                    url: menu_url,
                    icon: menu_icon,
                },
                dataType: 'json',
                async: true,
                success: function(res){
                    $('#formCreateMenu').modal('hide')
                    Swal.fire({
                        icon: "success",
                        title: 'Success!',
                        text: res.meta.message,
                        delay: 3000,
                        autohide: true,
                        fade: true,
                        close: true,
                        autoremove: true,
                    })
                    fetchMenu()
                },
                error: function(xhr) {
                    let response_error = JSON.parse(xhr.responseText)
                    Swal.fire({
                        icon: "error",
                        title: 'Error!',
                        text: 'gagal memuat data, silahkan hubungi admin.',
                        delay: 3000,
                        autohide: true,
                        fade: true,
                        close: true,
                        autoremove: true,
                    })
                }
            })
        })

        $('#menu_permission').select2({
            dropdownParent: $('#formCreateMenu')
        })
    })
</script>