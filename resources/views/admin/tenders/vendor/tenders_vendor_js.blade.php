<script>
    $(document).ready(() => {
        fetchTenderVendor()

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
                        $('#list_tender_vendor').append(`<p class="text-center">Belum ada data</p>`)
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
                                                <span class="number-tender-vendor-style">Number Tender for Vendor</span>
                                            </div>
                                            <div class="col-md-4 text-right">
                                                <button type="button" class="btn btn-sm bg-gradient-primary text-right for_edit_tender_vendor" data-toggle="modal" data-target="#edit_tender_vendor"><i class="fas fa-edit"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body px-4 py-2" id="data_eula">
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

        $(document).on('click', '#for_create_tender_vendor', (e) => {
            e.preventDefault()
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
        })

        // $('#createDataBtn').on('click', function() {

        // });
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
            let data_form_tender_vendor = new FormData($('form_create_tender_vendor')[0])
            console.log(data_form_tender_vendor  );
            
        })

        $('#create_tender_type').select2({
            dropdownParent: $("#modal_create_tender_vendor")
        })
    })
</script>
