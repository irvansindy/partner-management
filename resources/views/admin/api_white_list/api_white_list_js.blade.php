<script>
    $(document).ready(function() {
        var table = $('#ip_address_table').DataTable({
            processing: true,
            // serverSide: true,
            ajax: {
                url: "{{ route('api-whitelist.fetch') }}",
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
                    "defaultContent": "<i>Not set</i>"
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
            $('#button-ip_address').empty();
            $('.text-danger').text('');
            $('#button-ip_address').append(`
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="create_ip_address">Submit</button>
            `);
        });

        $(document).on('click', '#create_ip_address', function() {
            var formData = new FormData($('#data_form_ip_address')[0]);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('api-whitelist.submit') }}",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(res) {
                    table.ajax.reload();
                    $('.text-danger').text('');
                    $('#formIpAddress').modal('hide');
                    toastr.success(res.message);
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
