<script>
    $(document).ready(() => {
        // moment.locale('id');
        // Example: format a date in Indonesian
        // const formattedDate = moment('2024-10-24').format('LL');
        // console.log(formattedDate); // Output: "24 Oktober 2024"
        fetchTenderVendor()
        // get all data tender for first page
        function fetchTenderVendor() {
            $('#list_tender_vendor').empty()
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('fetch-tender-vendor') }}",
                method: 'GET',
                async: true,
                success: function(res) {
                    if (res.data == null) {
                        $(document).Toasts('create', {
                            title: 'Error',
                            class: 'bg-danger',
                            body: 'data kosong',
                            delay: 3000,
                            autohide: true,
                            fade: true,
                            close: true,
                            autoremove: true,
                        });
                        $('#list_tender_vendor').append(`
                            <div class="container d-flex flex-column justify-content-center align-items-center" style="height: 50vh;">
                                <div class="text-center">
                                    <i class="bi bi-exclamation-circle" style="font-size: 4rem; color: #6c757d;"></i>
                                    <h1 class="display-6 mt-3">Tidak Ada Data Tersedia</h1>
                                    <p class="text-muted">Data yang Anda cari saat ini kosong atau belum tersedia.</p>
                                    <x-adminlte-button label="Refresh" theme="info" icon="fas fa-fw fa-sync-alt" onclick="location.reload()"/>
                                </div>
                            </div>
                        `)
                    }
                    // looping data tender vendor from api
                    const list_tender_vendor = res.data
                    $.each(list_tender_vendor, (i, tender_vendor) => {
                        $('#list_tender_vendor').append(`
                            <div class="col-md-4">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <div class="row">
                                            <div class="col-md-8 text-truncate">
                                                <span class="number-tender-vendor-style">${list_tender_vendor[i].number}</span>
                                            </div>
                                            <div class="col-md-4 text-right">
                                                <button type="button" class="btn btn-sm bg-gradient-primary text-right for_edit_tender_vendor" data-toggle="modal" data-target="#edit_tender_vendor" data-tender_id="${list_tender_vendor[i].id}"><i class="fas fa-edit"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body px-4 py-2" id="data_eula">
                                        <span>${list_tender_vendor[i].name}</span>
                                        <br/>
                                        <span>${list_tender_vendor[i].tender_type_id}</span>
                                        <div class="text-sm-right">
                                            <span class="text-end text-muted">Start - End</span>
                                            <br/>
                                            <span class="text-end text-muted">${moment(list_tender_vendor[i].ordering_date).format('ll')} - ${moment(list_tender_vendor[i].expired_date).format('ll')}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `)
                    })
                },
                error: function(xhr) {
                    let response_error = JSON.parse(xhr.responseText)
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
                }
            })
        }
        // get additional data for create tender
        $(document).on('click', '#for_create_tender_vendor', (e) => {
            e.preventDefault()
            // alert('GAEUL')
            $('#dynamic_form_tender_product').empty()
            $('#form_create_tender_vendor')[0].reset()

            // set tender type
            $('#create_tender_type').empty(``)
            $('#create_tender_type').append(`<option value="" hidden>Select Type</option>`)
            const list_tender_type = [
                'Type 1',
                'Type 2',
                'Type 3',
            ]

            $.each(list_tender_type, (i, type) => {
                $('#create_tender_type').append(`<option value="${type}">${type}</option>`)
            })

            // Get today's date
            const today = new Date();

            // Calculate the date 30 days from today
            const dueDate = new Date();
            dueDate.setDate(today.getDate() + 30);

            // Format the date as YYYY-MM-DD
            const formattedToday = today.toISOString().split('T')[0];
            const formattedDate = dueDate.toISOString().split('T')[0];

            // Set the calculated date to the date input field
            $('#create_tender_created_date').val(formattedToday);
            $('#create_tender_effective_date').val(formattedToday);
            $('#create_tender_expired_date').val(formattedDate);

            $('#list_view_tender_vendor').DataTable().clear().destroy();
            $('#list_view_tender_vendor tbody').empty();

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route("fetch-vendor-for-tender") }}',
                method: 'GET',
                success: function(res) {
                    let data_vendor = res.data
                    // console.log(data_vendor);
                    
                    data_vendor.forEach((vendor, i) => {
                        $('#list_view_tender_vendor tbody').append(`
                        <tr>
                            <td>
                                <input type="checkbox" class="rowCheckbox" id="rowCheckbox_${i+1}" value="${vendor.id != null ? vendor.id : 'not set'}" name="create_tender_product_list_vendor[]">
                            </td>
                            <td>
                                <label for="rowCheckbox_${i+1}">
                                    ${vendor.name != null ? vendor.name : 'not set'}
                                </label>
                            </td>
                        </tr>
                        `)
                    })

                    var table = $('#list_view_tender_vendor').DataTable({
                        scrollX: true
                    })
                }
            })
        })

        $(document).on('change', '#create_tender_effective_date', function() {
            generateExpiredDate()
        })

        function generateExpiredDate() {
            // Get the selected effective date
            const effectiveDate = new Date($('#create_tender_effective_date').val());

            if (isNaN(effectiveDate.getTime())) {
                // If the date is invalid, clear the expiration date
                $('#create_tender_expired_date').val('');
                return;
            }

            // Add 30 days to the effective date
            effectiveDate.setDate(effectiveDate.getDate() + 30);
            console.log(effectiveDate);
            

            // Format the date to YYYY-MM-DD for the expiration date input
            const year = effectiveDate.getFullYear();
            const month = String(effectiveDate.getMonth() + 1).padStart(2, '0'); // Months are zero-based
            const day = String(effectiveDate.getDate()).padStart(2, '0');

            // Set the expiration date
            $('#create_tender_expired_date').val(`${year}-${month}-${day}`);
        }

        $(document).on('click', '#submit_create_tender_vendor', function(e) {
            e.preventDefault()
            let data_form_tender_vendor = new FormData($('#form_create_tender_vendor')[0])

            // Append each checked value to FormData
            $('input[name="create_tender_product_list_vendor[]"]:checked').length == 0 ? data_form_tender_vendor.append('create_tender_product_list_vendor', []) : false;
            // $('input[name="create_tender_product_list_vendor[]"]:checked').each(function() {
            //     data_form_tender_vendor.append('create_tender_product_list_vendor[]', $(this).val());
            // });

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route("store-tender-vendor") }}',
                type: 'POST',
                data: data_form_tender_vendor,
                processData: false,   // Prevent jQuery from processing the data
                contentType: false,   // Prevent jQuery from setting content type
                cache: false,
                success: function(res) {
                    fetchTenderVendor()
                }
            })
            
        })

        $('#create_tender_type').select2({
            dropdownParent: $("#modal_create_tender_vendor")
        })

        $(document).on('click', '#add_dynamic_form_tender_product', () => {
            $('#dynamic_form_tender_product').append(`
                <div class="row array_dynamic_form_tender_product">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="create_tender_product_name">Product Name</label>
                            <input type="text" class="form-control" id=""
                                name="create_tender_product_name[]" aria-describedby="">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="create_tender_product_requirement">Product Requirement</label>
                            <textarea class="form-control" name="create_tender_product_requirement[]" id="" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="mr-4">
                        <button type="button" class="btn btn-danger float-sm-right pop_dynamic_form_tender_product">
                            <i class="fas fa-ws fa-minus"></i>
                        </button>
                    </div>
                </div>
            `)
            updateTenderProductId()
        })

        $(document).on('click', '.pop_dynamic_form_tender_product', function(e) {
            e.preventDefault()
            $(this).closest('.array_dynamic_form_tender_product').remove()
            updateTenderProductId()
        })

        function updateTenderProductId() {
            $('.array_dynamic_form_tender_product').each(function(index) {
                // Generate unique IDs
                const productNameId = 'create_tender_product_name_' + (index + 1);
                const productRequirementId = 'create_tender_product_requirement_' + (index + 1);

                // Update IDs for input and textarea
                $(this).find('input[name="create_tender_product_name[]"]').attr('id', productNameId);
                $(this).find('textarea[name="create_tender_product_requirement[]"]').attr('id', productRequirementId);

                // Set the 'for' attribute for the corresponding labels
                $(this).find('label[for="create_tender_product_name"]').attr('for', productNameId);
                $(this).find('label[for="create_tender_product_requirement"]').attr('for', productRequirementId);
            });
        }
        // Check all feature
        $(document).on('click', '#checkAll', function() {
            var isChecked = $(this).is(':checked');
            // Iterate over each page and check/uncheck checkboxes
            $('#list_view_tender_vendor').DataTable().rows().every(function(rowIdx) {
                $(this.node()).find('.rowCheckbox').prop('checked', isChecked);
            });
        });
        // When individual checkbox is unchecked, uncheck "check all" checkbox
        $('#list_view_tender_vendor tbody').on('change', '.rowCheckbox', function() {
            if (!$(this).prop('checked')) {
                $('#checkAll').prop('checked', false);
            }
        });

        // detail edit tender vendor
        $(document).on('click', '.for_edit_tender_vendor', function() {
            let tender_id = $(this).data('tender_id');
            // alert('Tender ID: ' + tender_id);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('fetch-tender-vendor-by-id') }}",
                method: 'GET',
                data: {
                    id: tender_id
                },
                async: true,
                success: function(res) {
                    $(document).Toasts('create', {
                        title: 'Success',
                        class: 'bg-success',
                        body: res.meta.message,
                        delay: 2000,
                        autohide: true,
                        fade: true,
                        close: true,
                        autoremove: true,
                    });
                },
                error: function(xhr) {
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
        });

    })
</script>
