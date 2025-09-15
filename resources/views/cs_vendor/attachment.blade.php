@extends('adminlte::page')

@section('title', 'Partner Management')

@section('content_header')
    <h1>Form Attachment</h1>
@stop

@section('content')
    <div class="container-fluid py-4">
            <div class="container-fluid">
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">
                            Form Attachment
                        </h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <form id="form_submit_supporting_document" enctype="multipart/form-data">
                            <div class="row row_list_supporting_document">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <select class="form-control select2_supporting_document"
                                            name="supporting_document_type[]" id="supporting_document_type_0">
        
                                        </select>
                                        <p class="fs-6 text-info"
                                            style="margin-bottom: 0.5rem !important; font-size: 12px !important;">Tipe Dokumen
                                        </p>
                                        <span class="text-danger mt-2 message_supporting_document_type"
                                            id="message_supporting_document_type_0" role="alert"></span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <select class="form-control select2_supporting_document"
                                            name="supporting_document_business_type[]" id="supporting_document_business_type_0">
                                            <option value="">tipe bisnis dokumen</option>
                                        </select>
                                        <p class="fs-6 text-info"
                                            style="margin-bottom: 0.5rem !important; font-size: 12px !important;">Tipe Usaha</p>
                                        <span class="text-danger mt-2 message_supporting_document_business_type"
                                            id="message_supporting_document_business_type_0" role="alert"></span>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <input type="file" name="file_supporting_document[]" id="file_supporting_document_0"
                                            placeholder="" class="form-control" required />
                                        <p class="fs-6 text-info"
                                            style="margin-bottom: 0.5rem !important; font-size: 12px !important;">File Dokumen
                                        </p>
                                        <span class="text-danger mt-2 message_file_supporting_document"
                                            id="message_file_supporting_document_0" role="alert"></span>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="d-flex justify-content-end">
                                        <button type="button" class="btn btn-primary btn_add_supporting_document"
                                            id="btn_add_supporting_document">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div id="dynamic_row_list_supporting_document">
        
                            </div>
                            <div class="d-flex justify-content-end">
                                <input type="hidden" name="supporting_document_partner_id" id="supporting_document_partner_id" value="{{ count(auth()->user()->companyInformation) > 0 ? auth()->user()->companyInformation[0]['id'] : null}}">
                                <button type="button" class="btn btn-primary" id="btn_submit_supporting_document">
                                    submit
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        {{-- @include('cs_vendor.detail_partner') --}}
    </div>
@stop

@section('css')
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
    <!-- data table -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <!-- select 2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container--default .select2-selection--single {
            background-color: #f8fafc !important;
        }

        .select2-container .select2-selection--single {
            height: 38px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px !important;
        }
    </style>
@stop

@section('js')
    <!-- datatables -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
    <!-- select 2 -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
    $(document).ready(function() {
        // load type Documents
        loadDocs()
        function loadDocs() {
            $('#form_submit_supporting_document')[0].reset()
            $.ajax({
                url: '{{ route('fetch-doctype') }}',
                type: 'GET',
                dataType: 'json',
                async: true,
                success: function(res) {
                    $('#dynamic_row_list_supporting_document').empty()
                    $('.message_supporting_document_type').text('')
                    $('.message_supporting_document_business_type').text('')
                    $('.message_file_supporting_document').text('')

                    // add all data tipe dokumen
                    // $('#supporting_document_partner_id').val(data_id)
                    $('#supporting_document_type_0').empty()
                    $('#supporting_document_type_0').append(
                        `<option value="">tipe dokumen</option>`)
                    $.each(res.data, function(i, data) {
                        $('#supporting_document_type_0').append(
                            `<option value="${data.id+`|`+data.name}">${data.name}</option>`
                        )
                    })

                    // add all data class business
                    let list_class_business = [
                        'pt',
                        'cv',
                        'ud_or_pd',
                        'perorangan'
                    ]
                    $('#supporting_document_business_type_0').empty()
                    $('#supporting_document_business_type_0').append(
                        `<option value="">tipe usaha</option>`)
                    $.each(list_class_business, function(i, data) {
                        $('#supporting_document_business_type_0').append(
                            `<option value="${data}">${data}</option>`)
                    })
                    $('.select2_supporting_document').select2({
                        width: '100%'
                    })
                }
            })
        }
        // menambahkan form field data file supporting document
        $(document).on('click', '#btn_add_supporting_document', function(e) {
            e.preventDefault()
            $('#dynamic_row_list_supporting_document').append(`
                <div class="row array_data_supporting_document mt-2">
                    <div class="col-md-3">
                        <div class="form-group">
                            <select class="form-control select2_supporting_document" name="supporting_document_type[]" id="supporting_document_type">
                                <option value="">tipe dokumen</option>
                            </select>
                            <p class="fs-6 text-info" style="margin-bottom: 0.5rem !important; font-size: 12px !important;">Tipe Dokumen</p>
                            <span class="text-danger mt-2 message_supporting_document_type" id="" role="alert"></span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <select class="form-control select2_supporting_document" name="supporting_document_business_type[]" id="supporting_document_business_type">
                                <option value="">tipe bisnis dokumen</option>
                            </select>
                            <p class="fs-6 text-info" style="margin-bottom: 0.5rem !important; font-size: 12px !important;">Tipe Usaha</p>
                            <span class="text-danger mt-2 message_supporting_document_business_type" id="" role="alert"></span>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <input type="file" name="file_supporting_document[]" id="file_supporting_document" placeholder="" class="form-control">
                            <p class="fs-6 text-info" style="margin-bottom: 0.5rem !important; font-size: 12px !important;">File Dokumen</p>
                            <span class="text-danger mt-2 message_file_supporting_document" id="" role="alert"></span>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-danger btn_delete_supporting_document">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `)

            updateIdCreateAttachment()
            let total_field_document = $('.array_data_supporting_document').length
            $.ajax({
                url: '{{ route('fetch-doctype') }}',
                type: 'GET',
                dataType: 'json',
                async: true,
                success: function(res) {
                    // add all data tipe dokumen
                    $('#supporting_document_type_' + total_field_document).empty()
                    $('#supporting_document_type_' + total_field_document).append(
                        `<option value="">tipe dokumen</option>`)
                    $.each(res.data, function(i, data) {
                        $('#supporting_document_type_' + total_field_document)
                            .append(
                                `<option value="${data.id+`|`+data.name}">${data.name}</option>`
                            )
                    })
                    // add all data class business
                    let list_class_business = [
                        'pt',
                        'cv',
                        'ud_or_pd',
                        'perorangan'
                    ]
                    $('#supporting_document_business_type_' + total_field_document).empty()
                    $('#supporting_document_business_type_' + total_field_document).append(
                        `<option value="">tipe usaha</option>`)
                    $.each(list_class_business, function(i, data) {
                        $('#supporting_document_business_type_' +
                            total_field_document).append(
                            `<option value="${data}">${data}</option>`)
                    })
                    $('.select2_supporting_document').select2({
                        width: '100%'
                    })
                }
            })
        })
        // menghapus form field data file supporting document
        $(document).on('click', '.btn_delete_supporting_document', function(e) {
            e.preventDefault()
            $(this).closest('.array_data_supporting_document').remove()
            updateIdCreateAttachment()
        })
        // submit data supporting document
        $(document).on('click', '#btn_submit_supporting_document', function(e) {
            // // Panggil validasi form
            // const result = secureValidator.validateForm('form_submit_supporting_document');

            // Reset pesan error sebelum validasi
            $('.text-danger').text('');
            
            // Create a FormData object to handle files and form data
            var formData = new FormData($('#form_submit_supporting_document')[0]);
            $.ajax({
                url: '{{ route('store-attachment') }}',
                type: 'POST',
                data: formData,
                processData: false, // Prevent jQuery from processing the data
                contentType: false, // Prevent jQuery from setting content type
                cache: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                enctype: 'multipart/form-data',
                success: function(res) {
                    // alert('Documents submitted successfully!');
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
                    fetchDataPartner()
                    $('#modal_support_document').modal('hide'); // Close modal on success
                },
                error: function(xhr) {
                    fetchDataPartner()
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
        function updateIdCreateAttachment() {
            $('.array_data_supporting_document').each(function(i) {
                $(this).find('select[name="supporting_document_type[]"]').attr('id',
                    'supporting_document_type_' + (i + 1));
                $(this).find('select[name="supporting_document_business_type[]"]').attr('id',
                    'supporting_document_business_type_' + (i + 1));
                $(this).find('input[name="file_supporting_document[]"]').attr('id',
                    'file_supporting_document_' + (i + 1));

                $(this).find('.message_supporting_document_type').attr('id',
                    'message_supporting_document_type_' + (i + 1));
                $(this).find('.message_supporting_document_business_type').attr('id',
                    'message_supporting_document_business_type_' + (i + 1));
                $(this).find('.message_file_supporting_document').attr('id',
                    'message_file_supporting_document_' + (i + 1));
            })
        }
    });
</script>
    @include('cs_vendor.partner_js')
@stop
