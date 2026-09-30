<script>
    function closeIpAddressModal() {
        let modal = $('#formIpAddress');

        modal.modal('hide');

        setTimeout(function() {
            if (modal.hasClass('show') || modal.is(':visible')) {
                modal.removeClass('show').attr('aria-hidden', 'true').css('display', 'none');
                $('body').removeClass('modal-open').css('padding-right', '');
                $('.modal-backdrop').remove();
            }
        }, 350);
    }

    $(document).ready(function() {
        function stripHtml(value) {
            return $('<div>').html(value || '').text().trim();
        }

        function updateIpAddressLabel() {
            let input = $('#ip_address');
            $('label[for="ip_address"]').toggleClass('active', input.is(':focus') || input.val() !== '');
        }

        $('#ip_address').on('focus input blur', updateIpAddressLabel);

        var table = $('#ip_address_table').DataTable({
            processing: true,
            // serverSide: true,
            ajax: {
                url: "{{ route('ip-whitelist.fetch') }}",
                type: "GET",
            },
            columns: [{
                    "data": null,
                    "render": function(data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                {
                    "data": "ip_address",
                    "defaultContent": "<i>Not set</i>"
                },
                {
                    "data": "description",
                    "defaultContent": "<i>Not set</i>",
                    "render": function(data) {
                        return stripHtml(data) || '<i>Not set</i>';
                    }
                },
                {
                    'data': null,
                    title: 'Action',
                    wrap: true,
                    "render": function(item) {
                        return `
                            <button type="button" data-id="${item.id}" class="btn btn-outline-info btn-sm mt-2 edit_ip_address" data-toggle="modal" data-target="#formIpAddress">Edit</button>
                            <button type="button" data-id="${item.id}" class="btn btn-outline-danger btn-sm mt-2 delete_ip_address">Delete</button>
                        `;
                    }
                }
            ],
            order: [
                [0, 'asc']
            ]
        });

        $(document).on('click', '#for_create_ip_address', function() {
            $('#data_form_ip_address')[0].reset();
            $('#id_ip_address').val('');
            updateIpAddressLabel();
            $('#button-ip_address').empty();
            $('.text-danger').text('');
            $('#button-ip_address').append(`
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="create_ip_address">Submit</button>
            `);
        });

        $(document).on('click', '.edit_ip_address', function(e) {
            e.preventDefault();

            var id = $(this).data('id');
            $('#data_form_ip_address')[0].reset();
            $('.text-danger').text('');
            $('#button-ip_address').empty();
            $('#button-ip_address').append(`
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="update_ip_address">Update</button>
            `);

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('ip-whitelist.fetch-by-id') }}",
                type: 'GET',
                data: { id: id },
                dataType: 'json',
                success: function(res) {
                    $('#id_ip_address').val(res.data.id);
                    $('#ip_address').val(res.data.ip_address);
                    $('#description').val(stripHtml(res.data.description));
                    updateIpAddressLabel();
                },
                error: function(xhr) {
                    var response = xhr.responseJSON || {};
                    var message = response.meta && response.meta.message
                        ? response.meta.message
                        : 'Gagal mengambil detail IP address.';
                    toastr.error(message, 'Error');
                }
            });
        });

        $(document).on('click', '.delete_ip_address', function(e) {
            e.preventDefault();

            var id = $(this).data('id');
            var ipAddress = $(this).closest('tr').find('td').eq(1).text().trim();
            $('#delete_ip_address_message').text('Yakin akan menghapus IP address ' + ipAddress + ' ?');
            $('#confirm_delete_ip_address').attr('data-delete_ip_id', id);
            $('#confirmDeleteIpAddress').modal('show');
        });

        $(document).on('click', '#confirm_delete_ip_address', function(e) {
            e.preventDefault();

            var id = $(this).attr('data-delete_ip_id');
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('ip-whitelist.delete') }}",
                type: 'POST',
                data: { id: id },
                dataType: 'json',
                success: function(res) {
                    $('#confirmDeleteIpAddress').modal('hide');
                    table.ajax.reload(null, false);
                    var successMessage = res.meta && res.meta.message
                        ? res.meta.message
                        : 'IP address berhasil dihapus.';
                    toastr.success(successMessage, 'Success');
                },
                error: function(xhr) {
                    var response = xhr.responseJSON || {};
                    var errorMessage = response.meta && response.meta.message
                        ? response.meta.message
                        : 'Gagal menghapus IP address.';
                    toastr.error(errorMessage, 'Error');
                }
            });
        });

        $(document).on('click', '#create_ip_address, #update_ip_address', function() {
            var formData = new FormData($('#data_form_ip_address')[0]);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('ip-whitelist.submit') }}",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(res) {
                    $('.text-danger').text('');
                    closeIpAddressModal();
                    table.ajax.reload();
                    let successMessage = res.meta && res.meta.message
                        ? res.meta.message
                        : 'IP address berhasil disimpan.';
                    toastr.success(successMessage, 'Success');
                },
                error: function(xhr) {
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
                            // alert(value)
                            $('#message_' + i).text(value)
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
            });
        });

        // Add event listener for opening and closing details
        $('#ip_address_table tbody').on('click', 'td.details-control', function() {
            var tr = $(this).closest('tr');
            var row = table.row(tr);
            $('.text-danger').text('');
            if (row.child.isShown()) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
            } else {
                // Open this row
                row.child(format(row.data())).show();
                tr.addClass('shown');
            }
        });
    });
</script>
