<script>
    $(document).ready(function() {
        $('#eula_tnc_table').DataTable({
            processing: true,
            // serverside: true,
            ajax: {
                url: '{{ route("fetch-end-user-license-agreement") }}',
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
                    "data": "year",
                    "defaultContent": "<i>Not set</i>"
                },
                {
                    "data": "is_active",
                    "defaultContent": "<i>Not set</i>",
                    "render": function (data, type, row) {
                        return data == 1 ? 'Active' : 'Inactive';
                    }
                },
                {
                    'data': null,
                    title: 'Action',
                    wrap: true,
                    "render": function(item) {
                        return '<button type="button" data-id="'+item.id+'" class="btn btn-outline-info btn-sm mt-2 detail_eula_tnc" data-toggle="modal" data-target="#AddOrEditEULA">View</button>'
                    }
                },
            ]
        })

        $(document).on('click', '#for_create_eula', function(e) {
            e.preventDefault()
            $('#form_end_user_license_agreements')[0].reset()
            // clear summernote
            $('#summernote_end_user_license_agreements').summernote('reset');
            $('#summernote_end_user_license_agreements').summernote('fullscreen.isFullscreen');
            // re-setup summernote
            
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('fetch-end-user-license-agreement') }}",
                method: 'GET',
                async: true,
                success: function(res) {
                    $('#summernote_end_user_license_agreements').summernote({
                        placeholder: 'End User License Agreements',
                        tabsize: 2,
                        height: 100,
                        focus: true,
                        codeviewFilter: false,
                        codeviewIframeFilter: true,
                        spellCheck: true,
                    });
                    $('#summernote_end_user_license_agreements').summernote({
                        toolbar: [
                            // Tambahkan toolbar `fontSize`
                            ['font', ['bold', 'italic', 'underline', 'clear']],
                            ['fontsize', [
                                'fontsize'
                            ]], // Menambahkan opsi fontSize
                            ['color', ['color']],
                            ['para', ['ul', 'ol', 'paragraph']],
                            ['height', ['height']]
                        ],
                        fontSizes: ['8', '9', '10', '11', '12', '14', '16', '18',
                            '20', '22', '24', '26', '28', '36', '48', '64',
                            '82', '150'
                        ],
                        popover: {
                            image: [
                                ['image', ['resizeFull', 'resizeHalf',
                                    'resizeQuarter', 'resizeNone'
                                ]],
                                ['float', ['floatLeft', 'floatRight',
                                    'floatNone'
                                ]],
                                ['remove', ['removeMedia']]
                            ],
                            link: [
                                ['link', ['linkDialogShow', 'unlink']]
                            ],
                            table: [
                                ['add', ['addRowDown', 'addRowUp', 'addColLeft',
                                    'addColRight'
                                ]],
                                ['delete', ['deleteRow', 'deleteCol',
                                    'deleteTable'
                                ]],
                            ],
                            air: [
                                ['color', ['color']],
                                ['font', ['bold', 'underline', 'clear']],
                                ['para', ['ul', 'paragraph']],
                                ['table', ['table']],
                                ['insert', ['link', 'picture']]
                            ]
                        }
                    });

                    if (res.data.length != 0) {
                        $('#end_user_license_agreements_id').val(res.data.id)
                        $('#summernote_end_user_license_agreements').summernote('code', res
                            .data.content)
                    }
                }
            })
        })

        $(document).on('click', '#submit_end_user_license_agreements', function(e) {
            e.preventDefault()
            let data_eula = $('#summernote_end_user_license_agreements').val()
            let data_eula_name = $('#end_user_license_agreements_name').val()
            let data_eula_year = $('#end_user_license_agreements_year').val()
            let data_eula_is_active = $('end_user_license_agreements_is_active')
            // if ($("#end_user_license_agreements_is_active").prop('checked', true)) {
            //     alert('checklist bos')
            // }
            let eula_is_active = data_eula_is_active.is(':checked') ? 1 : 0;
            // alert(eula_is_active)
            let data = {
                'id': $('#end_user_license_agreements_id').val(),
                'data_eula': data_eula,
                'data_eula_name': data_eula_name,
                'data_eula_year': data_eula_year,
                'eula_is_active': eula_is_active
            }
            // console.log(data);
            $.ajax({
                url: '{{ route('submit-end-user-license-agreement') }}',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: data,
                success: function(res) {
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
                    $('#eula_tnc_table').DataTable().ajax.reload();
                    $('#AddOrEditEULA').modal('hide')
                },
                error: function(xhr) {
                    $('#modalLoading').modal('hide')
                    let response_error = JSON.parse(xhr.responseText)

                    if (response_error.meta.code === 500 || response_error.meta.code ===
                        400) {
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
                            $('#message_' + i.replace('.', '_')).text(value)
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
                },
            })

        })

        $(document).on('click', '.detail_eula_tnc', function(e) {
            e.preventDefault()
            let data_id = $(this).data('id')
            // clear summernote
            $('#summernote_end_user_license_agreements').summernote('reset');
            $('#summernote_end_user_license_agreements').summernote('fullscreen.isFullscreen');
            // re-setup summernote
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    id: data_id
                },
                url: "{{ route('fetch-end-user-license-agreement-by-id') }}",
                method: 'GET',
                async: true,
                success: function(res) {
                    $('#summernote_end_user_license_agreements').summernote({
                        placeholder: 'End User License Agreements',
                        tabsize: 2,
                        height: 100,
                        focus: true,
                        codeviewFilter: false,
                        codeviewIframeFilter: true,
                        spellCheck: true,
                    });
                    $('#summernote_end_user_license_agreements').summernote({
                        toolbar: [
                            // Tambahkan toolbar `fontSize`
                            ['font', ['bold', 'italic', 'underline', 'clear']],
                            ['fontsize', [
                                'fontsize'
                            ]], // Menambahkan opsi fontSize
                            ['color', ['color']],
                            ['para', ['ul', 'ol', 'paragraph']],
                            ['height', ['height']]
                        ],
                        fontSizes: ['8', '9', '10', '11', '12', '14', '16', '18',
                            '20', '22', '24', '26', '28', '36', '48', '64',
                            '82', '150'
                        ],
                        popover: {
                            image: [
                                ['image', ['resizeFull', 'resizeHalf',
                                    'resizeQuarter', 'resizeNone'
                                ]],
                                ['float', ['floatLeft', 'floatRight',
                                    'floatNone'
                                ]],
                                ['remove', ['removeMedia']]
                            ],
                            link: [
                                ['link', ['linkDialogShow', 'unlink']]
                            ],
                            table: [
                                ['add', ['addRowDown', 'addRowUp', 'addColLeft',
                                    'addColRight'
                                ]],
                                ['delete', ['deleteRow', 'deleteCol',
                                    'deleteTable'
                                ]],
                            ],
                            air: [
                                ['color', ['color']],
                                ['font', ['bold', 'underline', 'clear']],
                                ['para', ['ul', 'paragraph']],
                                ['table', ['table']],
                                ['insert', ['link', 'picture']]
                            ]
                        }
                    });
                    if (res.data.length != 0) {
                        $('#end_user_license_agreements_id').val(res.data.id)
                        $('#end_user_license_agreements_name').val(res.data.name)
                        $('#end_user_license_agreements_year').val(res.data.year)

                        let is_active = res.data.is_active == 1 ? true : false
                        $('#end_user_license_agreements_is_active').prop('checked', is_active)
                        $('#summernote_end_user_license_agreements').summernote('code', res.data.content)
                    }
                }
            })
        })
    })
</script>
