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
                    "data": "master_offices.name",
                    "defaultContent": "<i>Not set</i>"
                },
                {
                    "data": "master_departments.name",
                    "defaultContent": "<i>Not set</i>"
                },
                {
                    'data': null,
                    title: 'Action',
                    wrap: true,
                    "render": function(item) {
                        return '<button type="button" data-master_approval_id="' + item.id +'" data-master_approval_name="' + item.name +'" class="btn btn-outline-info btn-sm mt-2 add_view_approval_detail" data-toggle="modal" data-target="#addViewApprovalDetail">Stagging</button>'
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
                    $('#stagging_approval_office').append(`<option value="">Select Office</option>`)
                    $('#stagging_approval_department').append(`<option value="">Select Department</option>`)
                    // looping & append data office
                    for (let i = 0; i < res.data.offices.length; i++) {
                        $('#stagging_approval_office').append(`<option value="${res.data.offices[i].id}">${res.data.offices[i].name}</option>`)
                    }
                    // looping & append data departments
                    for (let i = 0; i < res.data.departments.length; i++) {
                        $('#stagging_approval_department').append(`<option value="${res.data.departments[i].id}">${res.data.departments[i].name}</option>`)
                    }
                }
            })
        })
        
        $(document).on('click', '.add_view_approval_detail', function(e) {
            e.preventDefault()
            data_approval_count = 0
            let master_approval_id = $(this).data('master_approval_id')
            let master_approval_name = $(this).data('master_approval_name')

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
                async: true,
                success: function(res) {
                    $('#stagging_approval_name_0').empty()
                    $('#stagging_approval_name_0').append(`<option value="">Select User Approval</option>`)
                    for (let i = 0; i < res.data.length; i++) {
                        $('#stagging_approval_name_0').append(`<option value="${res.data[i].id}">${res.data[i].name}</option>`)
                    }
                }
            })
            
        })

        $(document).on('click', '#add_stagging', function(e) {
            e.preventDefault()
            data_approval_count++
            $('.dynamic_approval').append(`
                <div class="row array_dynamic_approval">
                    <div class="col-md-10">
                        <div class="form-group">
                            <select class="form-control option_approval_detail" name="stagging_approval_name[]" id="" style="width: 100%"></select>
                        </div>
                    </div>
                    <div class="col-md-auto">
                        <button type="button" class="btn btn-outline-danger float-right delete_stagging">
                            <i class="fas fa-fw fa-minus"></i>
                        </button>
                    </div>
                </div>
            `)
            updateFormIdApprovalDetail()
            // get data user approval
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('fetch-user-approval') }}",
                method: 'GET',
                async: true,
                success: function(res) {
                    var id_for_user_approval = 'stagging_approval_name_' + data_approval_count
                    $('#'+id_for_user_approval).empty()
                    $('#'+id_for_user_approval).append(`<option value="">Select User Approval</option>`)
                    for (let i = 0; i < res.data.length; i++) {
                        $('#'+id_for_user_approval).append(`<option value="${res.data[i].id}">${res.data[i].name}</option>`)
                    }
                }
            })
            $(".option_approval_detail").select2({
                dropdownParent: $('#addViewApprovalDetail')
            })
            
        })

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
                url: '{{ route('store-approval-detail') }}',
                method: 'POST',
                processData: false,
                contentType: false,
                cache: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: data_approval_detail_stagging,
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
