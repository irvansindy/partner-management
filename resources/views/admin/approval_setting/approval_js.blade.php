<script>
    $(document).ready(function() {
        var data_approval_count = 0
        $('#approval_table').DataTable({
            processing: true,
            // serverside: true,
            ajax: {
                url: '{{ route('fetch-approval') }}',
                type: 'GET',
            },
            columns: [{
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
                    "data": "office",
                    "defaultContent": "<i>Not set</i>"
                },
                {
                    "data": "department",
                    "defaultContent": "<i>Not set</i>"
                },
                {
                    'data': null,
                    title: 'Action',
                    wrap: true,
                    "render": function(item) {
                        return '<button type="button" data-master_approval_id="' + item.id +
                            '" data-master_approval_name="' + item.name +
                            '" data-office_id="' + item.office_id +
                            '" data-department_id="' + item.department_id +
                            '" class="btn btn-outline-info btn-sm mt-2 detail_approval_detail" data-toggle="modal" data-target="#addViewApprovalDetail">Stagging</button>'
                    }
                },
            ]
        })

        $(document).on('click', '#for_create_approval', function(e) {
            e.preventDefault()
            $('#data_approval_master')[0].reset()
            // get data office and department
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('fetch-office-department') }}",
                method: 'GET',
                async: true,
                success: function(res) {
                    $('#stagging_approval_office').empty()
                    $('#stagging_approval_department').empty()
                    $('#stagging_approval_office').append(
                        `<option value="">Select Office</option>`)
                    $('#stagging_approval_department').append(
                        `<option value="">Select Department</option>`)
                    // looping & append data office
                    for (let i = 0; i < res.data.offices.length; i++) {
                        $('#stagging_approval_office').append(
                            `<option value="${res.data.offices[i].id}">${res.data.offices[i].name}</option>`
                        )
                    }
                    // looping & append data departments
                    for (let i = 0; i < res.data.departments.length; i++) {
                        $('#stagging_approval_department').append(
                            `<option value="${res.data.departments[i].id}">${res.data.departments[i].name}</option>`
                        )
                    }
                }
            })
        })

        $(document).on('click', '.detail_approval_detail', function(e) {
            e.preventDefault()
            data_approval_count = 0
            let master_approval_id = $(this).data('master_approval_id')
            let master_approval_name = $(this).data('master_approval_name')
            let office_id = $(this).data('office_id')
            let department_id = $(this).data('department_id')

            $('#data_approval_detail')[0].reset()
            $('.dynamic_approval').empty()
            $('#master_approval_id').val(master_approval_id)
            $('#master_approval_name').val(master_approval_name)
            $('#create_approval_detail_data').attr('data-master_approval_stagging', master_approval_id)

            // get data user approval
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('fetch-user-approval') }}",
                method: 'GET',
                data: {
                    master_approval_id: master_approval_id,
                    office_id: office_id,
                    department_id: department_id
                },
                dataType: 'json',
                async: true,
                success: function(res) {
                    const users = res.data.users;
                    const existingApprovals = res.data.existing_approvers;
                    // console.log(existingApprovals.length);

                    $('#add_stagging').attr('data-approval_id', master_approval_id);
                    $('#add_stagging').attr('data-department_id', department_id);
                    $('#add_stagging').attr('data-office_id', office_id);
                    // --- Handle first (index 0) ---
                    $('#stagging_approval_name_0').empty().append(
                        '<option value="">Select One</option>');
                    if (existingApprovals.length != 0) {
                        users.forEach(user => {
                            const selected = user.id === existingApprovals[0]
                                .user_id ? 'selected' : '';
                            $('#stagging_approval_name_0').append(
                                `<option value="${user.id}" ${selected}>${user.name}</option>`
                                );
                        });
                    } else {
                        // jika tidak ada existing_approval, isi tetap dengan list user
                        users.forEach(user => {
                            $('#stagging_approval_name_0').append(
                                `<option value="${user.id}">${user.name}</option>`
                                );
                        });
                    }

                    // --- Append sisanya (index 1 dan seterusnya) ---
                    for (let i = 1; i < existingApprovals.length; i++) {
                        const approval = existingApprovals[i];
                        let selectOptions = `<option value="">Select One</option>`;

                        users.forEach(user => {
                            const selected = user.id === approval.user_id ?
                                'selected' : '';
                            selectOptions +=
                                `<option value="${user.id}" ${selected}>${user.name}</option>`;
                        });

                        $('.dynamic_approval').append(`
                            <div class="row array_dynamic_approval mb-2">
                                <div class="col-md-10">
                                    <div class="form-group">
                                        <select class="form-control option_approval_detail" name="stagging_approval_name[]" style="width: 100%">
                                            ${selectOptions}
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-auto">
                                    <button type="button" class="btn btn-outline-danger float-right delete_stagging">
                                        <i class="fas fa-fw fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                        `);
                    }
                    $('.option_approval_detail').select2({
                        dropdownParent: $('#addViewApprovalDetail')
                    })
                }
            })
        })

        $(document).on('click', '#add_stagging', function(e) {
            e.preventDefault();
            data_approval_count++;

            let id_select = `stagging_approval_name_${data_approval_count}`;
            let approval_id = $(this).data('approval_id');
            let department_id = $(this).data('department_id');
            let office_id = $(this).data('office_id');
            // Append elemen baru dengan ID yang sudah disiapkan
            $('.dynamic_approval').append(`
                <div class="row array_dynamic_approval mb-2">
                    <div class="col-md-10">
                        <div class="form-group">
                            <select class="form-control option_approval_detail" name="stagging_approval_name[]" id="${id_select}" style="width: 100%"></select>
                        </div>
                    </div>
                    <div class="col-md-auto">
                        <button type="button" class="btn btn-outline-danger float-right delete_stagging">
                            <i class="fas fa-fw fa-minus"></i>
                        </button>
                    </div>
                </div>
            `);
            updateFormIdApprovalDetail()
            // Baru setelah elemen ada, isi datanya
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('fetch-user-approval') }}",
                method: 'GET',
                data: {
                    master_approval_id: approval_id,
                    office_id: office_id,
                    department_id: department_id
                },
                async: true,
                success: function(res) {
                    const $select = $(`#${id_select}`);
                    $select.empty().append(
                    `<option value="">Select User Approval</option>`);
                    res.data.users.forEach(user => {
                        $select.append(
                            `<option value="${user.id}">${user.name}</option>`);
                    });

                    // Baru jalankan select2 SETELAH isi option
                    $select.select2({
                        dropdownParent: $('#addViewApprovalDetail')
                    });
                }
            });
        });

        $(document).on('click', '.delete_stagging', function(e) {
            e.preventDefault()
            $(this).closest('.array_dynamic_approval').remove();
            data_approval_count--
            updateFormIdApprovalDetail()
        })

        function updateFormIdApprovalDetail() {
            $('.array_dynamic_approval').each(function(i) {
                $(this).find('.option_approval_detail').attr('id', 'stagging_approval_name_' + (i + 1));
            })
        }

        $(document).on('click', '#create_approval_master_data', function(e) {
            e.preventDefault()
            let data_approval_master = new FormData($('#data_approval_master')[0])

            $.ajax({
                url: '{{ route('store-approval-master') }}',
                method: 'POST',
                processData: false,
                contentType: false,
                cache: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: data_approval_master,
                success: function(res) {
                    $('#approval_table').DataTable().ajax.reload();
                    $('#formCreateApproval').modal('hide')
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
                }
            })
        })

        $(document).on('click', '#create_approval_detail_data', function(e) {
            e.preventDefault()
            let data_approval_detail_stagging = new FormData($('#data_approval_detail')[0])

            $.ajax({
                url: '{{ route('submit-approval-detail') }}',
                method: 'POST',
                processData: false,
                contentType: false,
                cache: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: data_approval_detail_stagging,
                success: function(res) {
                    $('#approval_table').DataTable().ajax.reload();
                    $('#addViewApprovalDetail').modal('hide')
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
                }
            })
        })
    })

    $('.option_approval_master').select2({
        dropdownParent: $('#formCreateApproval')
    })
    $('.option_approval_detail').select2({
        dropdownParent: $('#addViewApprovalDetail')
    })
</script>
