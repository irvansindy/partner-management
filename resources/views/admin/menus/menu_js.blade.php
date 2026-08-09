<script>
    $(document).ready(function () {

        // ==== Bootstrap 5 modal helper ====
        // Bootstrap 5 tidak lagi punya jQuery plugin ($(el).modal()).
        // getOrCreateInstance (tersedia sejak BS 5.2) otomatis pakai instance
        // yang sudah ada, atau membuat baru kalau belum ada — jadi tidak perlu
        // manual cek getInstance() null seperti sebelumnya.
        function getModal(id) {
            var el = document.getElementById(id);
            if (!el) return null;
            return bootstrap.Modal.getOrCreateInstance(el);
        }

        function showModal(id) {
            var modal = getModal(id);
            if (modal) modal.show();
        }

        function hideModal(id) {
            var modal = getModal(id);
            if (modal) modal.hide();
        }

        window.current_master_id = null;

        // ==== Grid.js instances ====
        // Grid.js tidak punya method .destroy() + init ulang seperti dua library lain;
        // pola resminya adalah .updateConfig({...}).forceRender() pada instance yang sama.
        let menuGrid = null;
        let submenuGrid = null;

        // Formatter kolom Action dipakai ulang untuk tabel menu & submenu.
        // row.cells[0].data mengambil nilai dari kolom pertama (id, disembunyikan lewat hidden:true),
        // jadi kita tidak perlu menyisipkan id ke dalam markup tombol secara manual di data.
        function menuActionFormatter(showSubmenuButton) {
            return (_, row) => {
                const id = row.cells[0].data;
                return gridjs.html(`
                    <div class="d-flex justify-content-end gap-2">
                        ${showSubmenuButton ? `
                        <button type="button" class="btn btn-outline-secondary waves-effect waves-light check_list_submenu" data-bs-toggle="modal" data-bs-target="#ModalListSubMenu" data-master_id="${id}" title="list submenu">
                            <i class="fa fa-list-alt" aria-hidden="true"></i>
                        </button>` : ''}
                        <button type="button" class="btn btn-outline-info waves-effect waves-light ${showSubmenuButton ? 'detail_menu' : 'detail_submenu'}" data-bs-toggle="modal" data-bs-target="#formCreateMenu" data-detail_id="${id}" title="detail menu">
                            <i class="fas fa-eye" aria-hidden="true"></i>
                        </button>
                        <button type="button" class="btn btn-outline-danger waves-effect waves-light delete_menu" data-id="${id}" title="delete menu">
                            <i class="fas fa-trash" aria-hidden="true"></i>
                        </button>
                    </div>
                `);
            };
        }

        // node-waves tidak otomatis mendeteksi elemen baru yang muncul setelah page load
        // (ini keterbatasan resmi library-nya, lihat: github.com/fians/Waves/issues/71).
        // Grid.js juga tidak punya event yang konsisten fired setiap kali DOM-nya berubah
        // (event 'ready' cuma sekali di render pertama, tidak ikut fired saat sort/page/search
        // — lihat: github.com/grid-js/gridjs/discussions/1159).
        // Solusi paling robust: pasang MutationObserver di container, jadi setiap kali
        // Grid.js mengubah isi tabel (render awal, forceRender, sort, ganti halaman, search),
        // tombol aksi otomatis di-attach ulang ke Waves tanpa perlu tahu event spesifiknya.
        function attachWavesEffect(containerId) {
            if (typeof Waves === 'undefined') return;
            Waves.attach(`#${containerId} .waves-effect`);
            Waves.init();
        }

        function watchGridForWaves(containerId) {
            if (typeof Waves === 'undefined') return;
            const container = document.getElementById(containerId);
            if (!container) return;
            const observer = new MutationObserver(() => attachWavesEffect(containerId));
            observer.observe(container, { childList: true, subtree: true });
        }

        const gridColumns = (showSubmenuButton) => ([
            { id: 'id', name: 'id', hidden: true },
            { name: '#', sort: false, width: '60px' },
            // sort di-set eksplisit true (bukan hanya mengandalkan default global)
            // supaya jelas kolom mana yang bisa di-klik untuk sorting.
            { name: 'Name', sort: true },
            { name: 'URL', sort: true },
            { name: 'Action', sort: false, width: '160px', formatter: menuActionFormatter(showSubmenuButton) }
        ]);

        fetchMenu()

        function fetchMenu() {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('fetch-menu') }}',
                method: 'GET',
                success: function (res) {
                    const menus = res.data

                    // data dikirim mentah (termasuk id di kolom pertama yang hidden),
                    // markup tombol aksi dibangun oleh formatter, bukan disisipkan manual di sini.
                    const rows = menus.map((menu, i) => ([
                        menu.id,
                        i + 1,
                        menu.name_text != null ? menu.name_text : 'not set',
                        menu.url_name != null ? menu.url_name : 'not set',
                        null // kolom Action diisi oleh formatter, nilai mentahnya tidak dipakai
                    ]));

                    renderMenuGrid(rows);
                },
                error: function (xhr) {
                    alert('An error occurred: ' + xhr.responseText);
                }
            })
        }

        function renderMenuGrid(rows) {
            if (menuGrid) {
                // Grid.js: cukup update config + forceRender, tidak perlu destroy()
                menuGrid.updateConfig({ data: rows }).forceRender();
                return;
            }

            menuGrid = new gridjs.Grid({
                columns: gridColumns(true),
                data: rows,
                search: true,
                sort: { multiColumn: false },
                pagination: { limit: 10 },
                className: {
                    table: 'table table-striped table-hover align-middle',
                },
                language: {
                    search: { placeholder: 'Search...' },
                    pagination: {
                        previous: '‹',
                        next: '›',
                        showing: 'Showing',
                        of: 'of',
                        to: 'to',
                        results: 'entries',
                    },
                    noRecordsFound: 'No entries found',
                    error: 'Gagal memuat data, silahkan hubungi admin.',
                },
            }).render(document.getElementById('menu_table'));

            attachWavesEffect('menu_table');
            watchGridForWaves('menu_table');
        }

        function reloadSubmenus(master_id) {
            if (!master_id) return;
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('fetch-children-menu') }}',
                method: 'GET',
                data: {
                    id: master_id
                },
                success: function (res) {
                    const submenus = res.data

                    const rows = submenus.map((submenu, i) => ([
                        submenu.id,
                        i + 1,
                        submenu.name_text != null ? submenu.name_text : 'not set',
                        submenu.url_name != null ? submenu.url_name : 'not set',
                        null
                    ]));

                    renderSubmenuGrid(rows);
                },
                error: function (xhr) {
                    alert('An error occurred: ' + xhr.responseText);
                }
            })
        }

        function renderSubmenuGrid(rows) {
            if (submenuGrid) {
                submenuGrid.updateConfig({ data: rows }).forceRender();
                return;
            }

            submenuGrid = new gridjs.Grid({
                columns: gridColumns(false),
                data: rows,
                search: true,
                sort: { multiColumn: false },
                pagination: { limit: 10 },
                className: {
                    table: 'table table-striped table-hover align-middle',
                },
                language: {
                    search: { placeholder: 'Search...' },
                    noRecordsFound: 'No entries found',
                },
            }).render(document.getElementById('list_submenu_table'));

            attachWavesEffect('list_submenu_table');
            watchGridForWaves('list_submenu_table');
        }

        function loadParentMenus(selectedParentId = null) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('fetch-parent-menu') }}',
                method: 'GET',
                success: function (res) {
                    const parent_menu = res.data;
                    $('#parent_menu').empty();
                    // <label for="parent_id">Parent Menu</label>
                    $('#parent_menu').html(`
                        <select class="js-states form-control" name="parent_id" id="parent_id" style="width: 100%">
                            <option value="">Select One</option>
                        </select>
                    `);
                    $('#parent_id').select2({
                        dropdownParent: $('#formCreateMenu'),
                        data: parent_menu.map(parent => ({
                            id: parent.id,
                            text: parent.name_text
                        })),
                        placeholder: 'Select One',
                    });

                    if (selectedParentId) {
                        $('#parent_id').val(selectedParentId).trigger('change');
                    }
                }
            });
        }

        $(document).on('change', '[name="menu_type"]', function () {
            if ($(this).val() === 'children') {
                loadParentMenus();
            } else {
                $('#parent_menu').empty();
            }
        });

        $(document).on('click', '#for_create_menu', function (e) {
            e.preventDefault()
            $('#form_create_new_menu')[0].reset()
            $('#menu_id').val('')
            $('#formCreateMenuLabel').html('Create Menu')
            $('#parent_menu').empty()
            $('[name="roles[]"]').val([]).trigger('change')

            $('#roles').select2({
                dropdownParent: $('#formCreateMenu'),
                placeholder: 'Select One',
            })
        })

        $(document).on('click', '#submit_create_menu', function (e) {
            e.preventDefault()
            let form_data = new FormData($('#form_create_new_menu')[0])
            let menuId = $('#menu_id').val()
            let url = menuId ? '{{ route('update-menu') }}' : '{{ route('store-menu') }}'

            $('#submit_create_menu').prop('disabled', true)
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: url,
                method: 'POST',
                data: form_data,
                contentType: false,
                processData: false,
                success: function (res) {
                    hideModal('formCreateMenu')
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
                    if (window.current_master_id) {
                        reloadSubmenus(window.current_master_id);
                    }
                    $('#submit_create_menu').prop('disabled', false)
                },
                error: function (xhr) {
                    let response_error = JSON.parse(xhr.responseText)
                    $('#submit_create_menu').prop('disabled', false)
                    let bodyMessage = 'Gagal memuat data, silahkan hubungi admin.';
                    if (response_error.meta && response_error.meta.message) {
                        if (typeof response_error.meta.message === 'string') {
                            bodyMessage = response_error.meta.message;
                        } else if (response_error.meta.message.errors) {
                            bodyMessage = Object.values(response_error.meta.message.errors).flat().join('<br>');
                        }
                    }
                    $(document).Toasts('create', {
                        title: 'Error!',
                        class: 'bg-danger',
                        body: bodyMessage,
                        delay: 5000,
                        autohide: true,
                        fade: true,
                        close: true,
                        autoremove: true,
                    });
                }
            })
        });

        $(document).on('click', '.detail_menu, .detail_submenu', function (e) {
            e.preventDefault()
            let detail_id = $(this).data('detail_id')

            if ($(this).hasClass('detail_menu')) {
                $('#formCreateMenuLabel').html('Data Menu')
            } else if ($(this).hasClass('detail_submenu')) {
                $('#formCreateMenuLabel').html('Data Sub Menu')
                hideModal('ModalListSubMenu')
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
                success: function (res) {
                    const menu = res.data
                    let type = menu.type
                    let menu_type = type == 1 ? 'parent' : 'children'
                    let roles = (menu.permission && menu.permission.roles) ? menu.permission.roles : []
                    $('#form_create_new_menu')[0].reset()
                    $('#menu_id').val(menu.id)
                    $('[name="menu_name"]').val(menu.name_text)
                    $('[name="menu_url"]').val(menu.url_name)
                    $('[name="menu_icon"]').val(menu.icon)
                    $('[name="menu_type"]').filter('[value="' + menu_type + '"]').prop(
                        'checked', true);

                    if (menu_type === 'children') {
                        loadParentMenus(menu.parent_id);
                    } else {
                        $('#parent_menu').empty();
                    }

                    $('[name="roles[]"]').val(roles.map(role => role.id)).trigger('change')

                    $('#roles').select2({
                        dropdownParent: $('#formCreateMenu'),
                        placeholder: 'Select One',
                    })
                },
                error: function (xhr) {
                    $(document).Toasts('create', {
                        title: 'Error!',
                        class: 'bg-danger',
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

        $(document).on('click', '.check_list_submenu', function (e) {
            e.preventDefault()
            let master_id = $(this).data('master_id')
            window.current_master_id = master_id;
            reloadSubmenus(master_id);
        })

        $(document).on('click', '.delete_menu', function (e) {
            e.preventDefault();
            let delete_id = $(this).data('id');

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Menu yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: '{{ route('delete-menu') }}',
                        method: 'POST',
                        data: {
                            id: delete_id
                        },
                        success: function (res) {
                            hideModal('ModalListSubMenu');
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
                            fetchMenu();
                            if (window.current_master_id) {
                                reloadSubmenus(window.current_master_id);
                            }
                        },
                        error: function (xhr) {
                            $(document).Toasts('create', {
                                title: 'Error!',
                                class: 'bg-danger',
                                body: 'Gagal menghapus menu, silahkan hubungi admin.',
                                delay: 5000,
                                autohide: true,
                                fade: true,
                                close: true,
                                autoremove: true,
                            });
                        }
                    });
                }
            });
        });
    })
</script>