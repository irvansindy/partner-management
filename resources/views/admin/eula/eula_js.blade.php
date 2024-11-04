<script>
    $(document).ready(function() {
        fetchEULA()
        function fetchEULA() {
            $('#data_eula').empty()
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('fetch-end-user-license-agreement') }}",
                method: 'GET',
                async: true,
                success: function(res) {
                    console.log(res.data);
                    
                    if (res.data == null) {
                        $('#data_eula').append(`
                            <p>End User License Agreement is empty</p>
                        `)
                    } else {
                        $('#data_eula').append(`
                            ${res.data.content}
                        `)
                    }
                }
            })
        }

        $(document).on('click', '#for_add_or_edit_eula', function(e) {
            e.preventDefault()
            $('#form_end_user_license_agreements')[0].reset()
            // clear summernote
            $('#summernote_end_user_license_agreements').summernote('reset');
            $('#summernote_end_user_license_agreements').summernote('fullscreen.isFullscreen');
            // re-setup summernote
            $('#summernote_end_user_license_agreements').summernote({
                placeholder: 'End User License Agreements',
                tabsize: 2,
                height: 100,
                focus: true,
            });
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('fetch-end-user-license-agreement') }}",
                method: 'GET',
                async: true,
                success: function(res) {
                    console.log(res.data.length);
                    if (res.data.length != 0) {
                        $('#end_user_license_agreements_id').val(res.data.id)
                        $('#summernote_end_user_license_agreements').summernote('code', res.data.content)
                    }
                }
            })
        })

        $(document).on('click', '#submit_end_user_license_agreements', function(e) {
            e.preventDefault()
            let data_eula = $('#summernote_end_user_license_agreements').val()
            let data = {
                'id': $('#end_user_license_agreements_id').val(),
                'data_eula': data_eula
            }
            console.log(data);
            $.ajax({
                url: '{{ route("submit-end-user-license-agreement") }}',
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
                },
                error: function(xhr) {
                    $('#modalLoading').modal('hide')
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
    })
</script>