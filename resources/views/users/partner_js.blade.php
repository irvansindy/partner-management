<script>
    $('#company_type').select2({
        dropdownParent: $('#modalCreatePartner'),
        width: '100%'
    })
    $('.select2_supporting_document').select2({
        dropdownParent: $('#modal_support_document'),
        width: '100%'
    })

    $(document).ready(function() {
        fetchDataPartner()

        function fetchDataPartner() {
            $('.data-partner').empty()
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('fetch-partner') }}",
                method: 'GET',
                async: true,
                success: function(res) {
                    if (res.data[0] == null) {
                        $('#alert_modal_data_null').modal('show')
                        $('#data-company-name').empty()
                        $('#data-company-name').html('<small>Not Set</small>')
                        $('#data-company-group-name').empty()
                        $('#data-company-group-name').html('<small>Not Set</small>')
                        $('#data-company-established-year').empty()
                        $('#data-company-established-year').html('<small>Not Set</small>')
                        $('#data-company-type').empty()
                        $('#data-company-type').html('<small>Not Set</small>')
                        
                    }

                    if (res.data[0] != null) {
                        $('#alert_modal_data_null').modal('hide')
                        $('#data-company-name').empty()
                        $('#data-company-name').html('<small>' + res.data[0].name + '</small>')
                        $('#data-company-group-name').empty()
                        $('#data-company-group-name').html('<small>' + res.data[0].group_name +
                            '</small>')
                        $('#data-company-established-year').empty()
                        $('#data-company-established-year').html('<small>' + res.data[0]
                            .established_year + '</small>')
                        $('#data-company-type').empty()
                        $('#data-company-type').html('<small>' + res.data[0].type + '</small>')

                        $('#add_data_support_document').attr('data-data_id', res.data[0].id)

                        const supporting_document = res.data.document
                        
                        $('#company_support_document').DataTable().clear().destroy();
                        $('#company_support_document tbody').empty();

                        supporting_document.forEach((docx, index) => {
                            let id = docx.id != null ? docx.id : 'not set'
                            let data_link_docx = "{{ asset('') }}" + docx.document
                            $('#company_support_document tbody').append(`
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${docx.document != null ? docx.document : 'not set'}</td>
                                    <td>${docx.document_type_name != null ? docx.document_type_name : 'not set'}</td>
                                    <td>${docx.document != null ? `<a href="" id="link_docx_${index}" target="blank_">Link</a>` : 'not set'}</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-info edit_supporting_document" data-toggle="modal" data-target="#modal_support_document_edit"><i class="fas fa-edit"></i></button>
                                    </td>
                                </tr>
                            `);
                            $("#link_docx_"+index).attr("href", data_link_docx);
                        });

                        $('#company_support_document').DataTable({
                            pagingType: 'simple_numbers',
                        })
                    }
                }
            })
        }
        // menampilkan hover title jika kursor berada pada card partner name
        $('#card-info-company-name').hover(
            function(event) {
                // On mouse enter
                var titleText = $(this).data('title'); // Get the title from the data attribute
                $('#tooltip').text(titleText) // Set the text in the tooltip
                    .css({
                        top: event.pageY + 10 + 'px', // Position tooltip near the mouse
                        left: event.pageX + 10 + 'px'
                    })
                    .show(); // Show the tooltip
            },
            function() {
                // On mouse leave
                $('#tooltip').hide(); // Hide the tooltip when mouse leaves the div
            }
        );
        // Move the tooltip with the mouse
        $('#card-info-company-name').mousemove(function(event) {
            $('#tooltip').css({
                top: event.pageY + 10 + 'px',
                left: event.pageX + 10 + 'px'
            });
        });
        // menampilkan keseluruhan detail partner/company data
        $(document).on('dblclick', '#card-info-company-name', function(e) {
            e.preventDefault()
            $('#dataDetailPartner').modal('hide')
            $('#dataDetailPartner').modal('show')
            $('#form_detail_partner_data_by_user')[0].reset()
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('fetch-partner-byuser') }}",
                method: 'GET',
                async: true,
                success: function(res) {
                    if (res.data.status != 'checking') {
                        document.getElementById('btn_update_data_company').style.display =
                            'none';
                        document.getElementById('add_detail_dynamic_address').style.display =
                            'none';
                    }
                    $('#field_form_detail_business_other').empty()

                    $('#detail_company_type').empty()
                    let data_company_type = [
                        'customer',
                        'vendor',
                        'customer dan vendor'
                    ]
                    let selected_type = ''

                    $.each(data_company_type, function(i, data_type) {
                        selected_type = res.data.type == data_type ? 'selected' :
                            ''
                        $('#detail_company_type').append(`
                            <option value="${data_type}" ${selected_type}>${data_type}</option>
                        `)
                    })

                    $('#detail_id').val(res.data.id)
                    $('#detail_company_name').val(res.data.name)
                    $('#detail_company_group_name').val(res.data.group_name)
                    $('#detail_established_year').val(res.data.established_year)
                    $('#detail_total_employee').val(res.data.total_employee)
                    $('#detail_liable_person_and_position').val(res.data.liable_person_and_position)
                    $('#detail_owner_name').val(res.data.owner_name)
                    $('#detail_board_of_directors').val(res.data.board_of_directors)
                    $('#detail_major_shareholders').val(res.data.major_shareholders)

                    $('#list_business_classification').empty()
                    let data_business_classification = [
                        'Manufacturer',
                        'Trading',
                        'Agent',
                        'Distributor',
                        'Services',
                        'Contractor',
                        'Other'
                    ];
                    
                    let checked_business = '';
                    $.each(data_business_classification, function(i, business) {
                        checked_business = res.data.business_classification ==
                            business ?
                            'checked' : ''
                        $('#list_business_classification').append(`
                            <div class="form-check form-check-inline mb-1">
                                <input class="form-check-input detail_business_classification" style="margin-bottom: 1rem;" type="radio"
                                    name="detail_business_classification" id="detail_business_classification_${business.toLowerCase()}"
                                    value="${business}" ${checked_business}>
                                <label class="form-check-label" for="detail_business_classification_${business.toLowerCase()}">
                                    ${business}
                                    <p class="fs-6"
                                        style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                        ${business}</p>
                                </label>
                            </div>
                        `)
                    })
                    
                    let checked_business_value = $(
                        'input[name="detail_business_classification"]:checked').val()
                    // if (checked_business_value = 'Other') {
                    if (res.data.business_classification == 'Other') {
                        $('#field_form_detail_business_other').append(`
                        <input type="text" name="detail_business_classification_other_detail" id="detail_business_classification_other_detail" placeholder="Other" class="form-control" value="${res.data.other_business}">
                        `)
                    } else {
                        $('#field_form_detail_business_other').empty()
                    }

                    $('#detail_website_address').val(res.data.website_address)
                    $('#detail_website_address').val(res.data.website_address)

                    $('.detail_system_management').empty()
                    let data_system_management = [
                        'ISO',
                        'SMK3',
                        'Others Certificate'
                    ]
                    
                    let checked_system_management = ''
                    $.each(data_system_management, function(i, system) {
                        checked_system_management = res.data.system_management ==
                            system ? 'checked' : ''
                        $('.detail_system_management').append(`
                            <div class="form-check form-check-inline mb-1">
                                <input class="form-check-input" type="radio" name="detail_system_management"
                                    id="detail_system_management_${system.toLowerCase()}" value="${system}" ${checked_system_management}>
                                <label class="form-check-label" for="detail_system_management_${system.toLowerCase()}">
                                    ${system}
                                    ${system == 'Others Certificate' ? `<p class="fs-6" style="margin-bottom: 0rem !important; font-size: 10px !important;">
                                                        Sertifikat lainnya</p>` : ``}
                                </label>
                            </div>
                        `)
                    })

                    $('#detail_contact_person').val(res.data.contact_person)

                    $('.detail_option_languange').empty()
                    let data_language = ['Bahasa', 'English'];
                    let checked_language = '';
                    $.each(data_language, function(i, language) {
                        // alert(language)
                        checked_language = res.data.communication_language ==
                            language ?
                            'checked' : ''
                        $('.detail_option_languange').append(`
                            <div class="form-check form-check-inline mb-1">
                                <input class="form-check-input" style="margin-bottom: 1rem !important" ${checked_language}
                                    type="radio" name="detail_communication_language"
                                    id="detail_communication_language_${data_language[i].toLowerCase()}" value="Bahasa">
                                <label class="form-check-label" for="detail_communication_language_${data_language[i].toLowerCase()}">
                                    Bahasa
                                    <p class="fs-6"
                                        style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                        Indonesia</p>
                                </label>
                            </div>
                        `)
                    })

                    $('#detail_email_address').val(res.data.email_address)
                    
                    let data_link_stamp = "{{ asset('storage/uploads/stamp/') }}" + "/" + res.data.stamp
                    $("#link_stamp").attr("href", data_link_stamp);
                    
                    let data_link_signature = "{{ asset('storage/uploads/signature/') }}" + "/" + res.data.signature
                    $("#link_signature").attr("href", data_link_signature);

                    if (res.data.stamp != '' || res.data.stamp != null) {
                        // $("#detail_stamp_file").attr("hidden",true);
                        $('.form_field_detail_stamp').empty()
                        $('.link_detail_stamp').empty()
                        $('.button-change-stamp').empty()
                        $('.button-change-stamp').append(`
                            <i class="fas fa-edit title_change_stamp" title="Ubah data stempel" for="title_change_stamp"></i>
                            <span class="info-box-text title_change_stamp" id="title_change_stamp" title="Ubah data stempel"><small>Ubah data stempel</small></span>
                        `)
                    }
                    if (res.data.signature != '' || res.data.signature != null) {
                        // $("#detail_signature_file").attr("hidden",true);
                        $('.form_field_detail_signature').empty()
                        $('.link_detail_signature').empty()
                        $('.button-change-signature').empty()
                        $('.button-change-signature').append(`
                            <i class="fas fa-edit title_change_signature" title="Ubah data tanda tangan" for="title_change_signature"></i>
                            <span class="info-box-text title_change_signature" id="title_change_signature" title="Ubah data tanda tangan"><small>Ubah data tanda tangan</small></span>
                        `)
                    }

                    let list_address = res.data.address
                    
                    if (list_address.length == 1) {
                        $('#detail_company_address_additional').empty()
                        $.each(list_address, function(i, address) {
                            $('#detail_company_address_additional').append(`
                                <div class="input-group mb-4 array_detail_company_address">
                                    <div class="col-md-2">
                                        <label for="detail_address">Company Address *<br>
                                            (according to Company Address stated in the Tax Register Number):
                                            *</label>
                                        <br>
                                        <p class="fs-6"
                                            style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                            Alamat Perusahaan
                                            (sesuai dengan NPWP)</p>
                                    </div>
                                    <div class="col-md-10">
                                        <div class="row">
                                            <div class="col-md-6 mb-4">
                                                <input type="text" name="detail_address[]" id="detail_address_${i+1}" placeholder="" value="${address.address}"
                                                    class="form-control">
                                            </div>
                                            <div class="col-md-auto">
                                                <div class="row">
                                                    <div class="col-md-auto">
                                                        <label for="detail_city">City *</label>
                                                        <br>
                                                        <p class="fs-6"
                                                            style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                            Kota</p>
                                                    </div>
                                                    <div class="col-md-auto">
                                                        <input type="text" name="detail_city[]" id="detail_city_${i+1}" placeholder="" value="${address.city}"
                                                            class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-auto mb-4">
                                                <div class="row">
                                                    <div class="col-md-auto">
                                                        <label for="detail_country">Country *</label>
                                                        <br>
                                                        <p class="fs-6"
                                                            style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                            Negara</p>
                                                    </div>
                                                    <div class="col-md-auto">
                                                        <input type="text" name="detail_country[]" id="detail_country_${i+1}" placeholder="" value="${address.country}"
                                                            class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-auto mb-4">
                                                <div class="row">
                                                    <div class="col-md-auto">
                                                        <label for="detail_province">Province *</label>
                                                        <br>
                                                        <p class="fs-6"
                                                            style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                            Provinsi</p>
                                                    </div>
                                                    <div class="col-md-auto">
                                                        <input type="text" name="detail_province[]" id="detail_province_${i+1}" value="${address.province}"
                                                            placeholder="" class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-auto mb-4">
                                                <div class="row">
                                                    <div class="col-md-auto">
                                                        <label for="detail_zip_code">Zip Code *</label>
                                                        <br>
                                                        <p class="fs-6"
                                                            style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                            Kode Pos</p>
                                                    </div>
                                                    <div class="col-md-auto">
                                                        <input type="text" name="detail_zip_code[]" id="detail_zip_code_${i+1}" value="${address.zip_code}"
                                                            placeholder="" class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-auto mb-4">
                                                <div class="row">
                                                    <div class="col-md-auto">
                                                        <label for="detail_telephone">Telephone *</label>
                                                        <p class="fs-6"
                                                            style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                            + [Country-Area Code] [No.]
                                                            Telepon +[Negara-Area] [No.]</p>
                                                    </div>
                                                    <div class="col-md-auto">
                                                        <input type="text" name="detail_telephone[]" id="detail_telephone_${i+1}" value="${address.telephone}"
                                                            placeholder="" class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-auto mb-4">
                                                <div class="row">
                                                    <div class="col-md-auto">
                                                        <label for="detail_fax">Fax *</label>
                                                        <p class="fs-6"
                                                            style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                            + [Country-Area Code] [No.]
                                                            Fax +[Negara-Area] [No.]</p>
                                                    </div>
                                                    <div class="col-md-auto">
                                                        <input type="text" name="detail_fax[]" id="detail_fax_${i+1}" placeholder="" value="${address.fax}"
                                                            class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col mb-4 align-items-end" id="button-for-add-address_${i}">
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `)
                            $('#button-for-add-address_'+i).append(`
                                <button type="button" class="btn btn-primary float-right" id="add_detail_dynamic_address">
                                    + Address
                                </button>
                            `)
                        })
                        $('#detail_company_address_additional').append(`
                            <div class="detail_dynamic_company_address"></div>
                        `)
                    } else {
                        $('#detail_company_address_additional').empty()
                        $.each(list_address, function(i, address) {
                            $('#detail_company_address_additional').append(`
                                <div class="input-group mb-4 array_detail_company_address">
                                    <div class="col-md-2">
                                        <label for="detail_address">Company Address *<br>
                                            (according to Company Address stated in the Tax Register Number):
                                            *</label>
                                        <br>
                                        <p class="fs-6"
                                            style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                            Alamat Perusahaan
                                            (sesuai dengan NPWP)</p>
                                    </div>
                                    <div class="col-md-10">
                                        <div class="row">
                                            <div class="col-md-6 mb-4">
                                                <input type="text" name="detail_address[]" id="detail_address_${i+1}" placeholder="" value="${address.address}"
                                                    class="form-control">
                                            </div>
                                            <div class="col-md-auto">
                                                <div class="row">
                                                    <div class="col-md-auto">
                                                        <label for="detail_city">City *</label>
                                                        <br>
                                                        <p class="fs-6"
                                                            style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                            Kota</p>
                                                    </div>
                                                    <div class="col-md-auto">
                                                        <input type="text" name="detail_city[]" id="detail_city_${i+1}" placeholder="" value="${address.city}"
                                                            class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-auto mb-4">
                                                <div class="row">
                                                    <div class="col-md-auto">
                                                        <label for="detail_country">Country *</label>
                                                        <br>
                                                        <p class="fs-6"
                                                            style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                            Negara</p>
                                                    </div>
                                                    <div class="col-md-auto">
                                                        <input type="text" name="detail_country[]" id="detail_country_${i+1}" placeholder="" value="${address.country}"
                                                            class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-auto mb-4">
                                                <div class="row">
                                                    <div class="col-md-auto">
                                                        <label for="detail_province">Province *</label>
                                                        <br>
                                                        <p class="fs-6"
                                                            style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                            Provinsi</p>
                                                    </div>
                                                    <div class="col-md-auto">
                                                        <input type="text" name="detail_province[]" id="detail_province_${i+1}" value="${address.province}"
                                                            placeholder="" class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-auto mb-4">
                                                <div class="row">
                                                    <div class="col-md-auto">
                                                        <label for="detail_zip_code">Zip Code *</label>
                                                        <br>
                                                        <p class="fs-6"
                                                            style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                            Kode Pos</p>
                                                    </div>
                                                    <div class="col-md-auto">
                                                        <input type="text" name="detail_zip_code[]" id="detail_zip_code_${i+1}" value="${address.zip_code}"
                                                            placeholder="" class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-auto mb-4">
                                                <div class="row">
                                                    <div class="col-md-auto">
                                                        <label for="detail_telephone">Telephone *</label>
                                                        <p class="fs-6"
                                                            style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                            + [Country-Area Code] [No.]
                                                            Telepon +[Negara-Area] [No.]</p>
                                                    </div>
                                                    <div class="col-md-auto">
                                                        <input type="text" name="detail_telephone[]" id="detail_telephone_${i+1}" value="${address.telephone}"
                                                            placeholder="" class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-auto mb-4">
                                                <div class="row">
                                                    <div class="col-md-auto">
                                                        <label for="detail_fax">Fax *</label>
                                                        <p class="fs-6"
                                                            style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                            + [Country-Area Code] [No.]
                                                            Fax +[Negara-Area] [No.]</p>
                                                    </div>
                                                    <div class="col-md-auto">
                                                        <input type="text" name="detail_fax[]" id="detail_fax_${i+1}" placeholder="" value="${address.fax}"
                                                            class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col mb-4 align-items-end" id="button-for-add-address_${i}">
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `)
                            if (i === 0) {
                                $('#button-for-add-address_'+i).append(`
                                    <button type="button" class="btn btn-primary float-right" id="add_detail_dynamic_address">
                                        + Address
                                    </button>
                                `)
                            } else {
                                $('#button-for-add-address_'+i).append(`
                                    <button type="button" class="btn btn-danger float-right" id="delete_dynamic_address">- Address</button>
                                `)

                            }
                        })
                        $('#detail_company_address_additional').append(`
                            <div class="detail_dynamic_company_address"></div>
                        `)
                    }

                    if (res.data.status == 'checking') {
                        $('#button_update_partner').empty()
                        $('#button_update_partner').append(`<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>`)
                        $('#button_update_partner').append(`
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-primary" id="btn_update_data_company" data-id="" data-status="">
                                Update
                            </button>
                        </div>
                        `)
                        $('#btn_update_data_company').attr('data-id', res.data.id)
                        $('#btn_update_data_company').attr('data-status', res.data.status)
                    } else {
                        $('#button_update_partner').empty()
                    }

                    let list_bank = res.data.bank
                    if (list_bank.length != 0) {
                        $('#list_data_bank').empty()
                        $.each(list_bank, function(i, bank) {
                            $('#list_data_bank').append(`
                                <div class="input-group mt-4">
                                    <div class="col-md-6 mb-4">
                                        <div class="row">
                                            <div class="col-md-auto">
                                                <label for="detail_bank_name">Bank Name *</label>
                                                <br>
                                                <p class="fs-6"
                                                    style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                    Nama Bank</p>
                                            </div>
                                            <div class="col-md-auto">
                                                <input type="text" name="detail_bank_name[]" id="detail_bank_name" placeholder="" value="${bank.name}" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-auto">
                                                <label for="detail_branch">Branch *</label>
                                                <br>
                                                <p class="fs-6"
                                                    style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                    Cabang</p>
                                            </div>
                                            <div class="col-md-auto">
                                                <input type="text" name="detail_branch[]" id="detail_branch" placeholder="" value="${bank.branch}"
                                                    class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="input-group">
                                    <div class="col-md-6 mb-4">
                                        <div class="row">
                                            <div class="col-md-auto">
                                                <label for="detail_account_name">Account Name *</label>
                                                <br>
                                                <p class="fs-6"
                                                    style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                    Rekening Atas Nama</p>
                                            </div>
                                            <div class="col-md-auto">
                                                <input type="text" name="detail_account_name[]" id="detail_account_name" placeholder="" value="${bank.account_name}"
                                                    class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <div class="row">
                                            <div class="col-md-auto">
                                                <label for="detail_city_or_country">City/Country *</label>
                                                <br>
                                                <p class="fs-6"
                                                    style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                    Kota/Negara</p>
                                            </div>
                                            <div class="col-md-auto">
                                                <input type="text" name="detail_city_or_country[]" id="detail_city_or_country" value="${bank.city_or_country}"
                                                    placeholder="" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="input-group">
                                    <div class="col-md-auto mb-4">
                                        <div class="row">
                                            <div class="col-md-auto">
                                                <label for="detail_account_number">Account No. *</label>
                                                <br>
                                                <p class="fs-6"
                                                    style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                    No Rekening</p>
                                            </div>
                                            <div class="col-md-auto">
                                                <input type="text" name="detail_account_number[]" id="detail_account_number" placeholder="" value="${bank.account_number}"
                                                    class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-auto mb-4">
                                        <div class="row">
                                            <div class="col-md-auto">
                                                <label for="detail_currency">Currency *</label>
                                                <br>
                                                <p class="fs-6"
                                                    style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                    Mata Uang</p>
                                            </div>
                                            <div class="col-md-auto">
                                                <input type="text" name="detail_currency[]" id="detail_currency" placeholder="" value="${bank.currency}"
                                                    class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-auto">
                                        <div class="row">
                                            <div class="col-md-auto">
                                                <label for="detail_swift_code">Swift Code *</label>
                                                <br>
                                                <p class="fs-6"
                                                    style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                    Optional</p>
                                            </div>
                                            <div class="col-md-auto">
                                                <input type="text" name="detail_swift_code[]" id="detail_swift_code" placeholder="" value="${bank.swift_code}"
                                                    class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="input-group d-flex justify-content-end mr-4 mb-4 mt-4">
                                    <button type="button" class="btn btn-primary" id="add_bank"> +
                                        Bank</button>
                                </div>
                            `)
                        })
                        $('#list_data_bank').append(`
                            <div class="dynamic_bank"></div>
                        `)
                    } else {
                        $('#list_data_bank').empty()
                        $('#list_data_bank').append(`
                            <div class="input-group mt-4">
                                <div class="col-md-6 mb-4">
                                    <div class="row">
                                        <div class="col-md-auto">
                                            <label for="detail_bank_name">Bank Name *</label>
                                            <br>
                                            <p class="fs-6"
                                                style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                Nama Bank</p>
                                        </div>
                                        <div class="col-md-auto">
                                            <input type="text" name="detail_bank_name[]" id="detail_bank_name" placeholder="" value="" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-auto">
                                            <label for="detail_branch">Branch *</label>
                                            <br>
                                            <p class="fs-6"
                                                style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                Cabang</p>
                                        </div>
                                        <div class="col-md-auto">
                                            <input type="text" name="detail_branch[]" id="detail_branch" placeholder="" value=""
                                                class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="input-group">
                                <div class="col-md-6 mb-4">
                                    <div class="row">
                                        <div class="col-md-auto">
                                            <label for="detail_account_name">Account Name *</label>
                                            <br>
                                            <p class="fs-6"
                                                style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                Rekening Atas Nama</p>
                                        </div>
                                        <div class="col-md-auto">
                                            <input type="text" name="detail_account_name[]" id="detail_account_name" placeholder="" value=""
                                                class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <div class="row">
                                        <div class="col-md-auto">
                                            <label for="detail_city_or_country">City/Country *</label>
                                            <br>
                                            <p class="fs-6"
                                                style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                Kota/Negara</p>
                                        </div>
                                        <div class="col-md-auto">
                                            <input type="text" name="detail_city_or_country[]" id="detail_city_or_country" value=""
                                                placeholder="" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="input-group">
                                <div class="col-md-auto mb-4">
                                    <div class="row">
                                        <div class="col-md-auto">
                                            <label for="detail_account_number">Account No. *</label>
                                            <br>
                                            <p class="fs-6"
                                                style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                No Rekening</p>
                                        </div>
                                        <div class="col-md-auto">
                                            <input type="text" name="detail_account_number[]" id="detail_account_number" placeholder="" value=""
                                                class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-auto mb-4">
                                    <div class="row">
                                        <div class="col-md-auto">
                                            <label for="detail_currency">Currency *</label>
                                            <br>
                                            <p class="fs-6"
                                                style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                Mata Uang</p>
                                        </div>
                                        <div class="col-md-auto">
                                            <input type="text" name="detail_currency[]" id="detail_currency" placeholder="" value=""
                                                class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-auto">
                                    <div class="row">
                                        <div class="col-md-auto">
                                            <label for="detail_swift_code">Swift Code *</label>
                                            <br>
                                            <p class="fs-6"
                                                style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                Optional</p>
                                        </div>
                                        <div class="col-md-auto">
                                            <input type="text" name="detail_swift_code[]" id="detail_swift_code" placeholder="" value=""
                                                class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="input-group d-flex justify-content-end mr-4 mb-4 mt-4">
                                <button type="button" class="btn btn-primary" id="add_bank"> +
                                    Bank</button>
                            </div>
                        `)
                        $('#list_data_bank').append(`
                            <div class="dynamic_bank"></div>
                        `)
                    }

                    let list_tax = res.data.tax
                    if (list_tax != 0) {
                        $('#list_detail_tax').empty()
                        $.each(list_tax, function(i, tax) {
                            $('#list_detail_tax').append(`
                                <div class="input-group mt-4">
                                    <div class="col-md-auto mb-4">
                                        <div class="row">
                                            <div class="col-md-auto">
                                                <label for="detail_register_number_as_in_tax_invoice">Tax Register Number
                                                    (As in Tax Invoice) *</label>
                                                <br>
                                                <p class="fs-6"
                                                    style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                    Nomor NPWP (Sesuai dengan Faktur Pajak)</p>
                                            </div>
                                            <div class="col-md-auto">
                                                <input type="text" name="detail_register_number_as_in_tax_invoice" value="${tax.register_number_as_in_tax_invoice}"
                                                    id="detail_register_number_as_in_tax_invoice" placeholder="" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-auto mb-4">
                                        <div class="row">
                                            <div class="col-md-auto">
                                                <label for="detail_trc_number">TRC No. *</label>
                                                <br>
                                                <p class="fs-6"
                                                    style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                    Nomor TRC</p>
                                            </div>
                                            <div class="col-md-auto">
                                                <input type="text" name="detail_trc_number" id="detail_trc_number" placeholder=""
                                                value="${tax.trc_number}" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="input-group">
                                    <div class="col-md-auto mb-4">
                                        <div class="row">
                                            <div class="col-md-auto">
                                                <label for="detail_register_number_related_branch">Tax Register Number
                                                    (Related Branch) *</label>
                                                <br>
                                                <p class="fs-6"
                                                    style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                    Nomor NPWP (Cabang Terkait)</p>
                                            </div>
                                            <div class="col-md-auto">
                                                <input type="text" name="detail_register_number_related_branch" value="${tax.register_number_related_branch}"
                                                    id="detail_register_number_related_branch" placeholder="" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-auto mb-4">
                                        <div class="row">
                                            <div class="col-md-auto">
                                                <label for="detail_valid_until">Valid Until *</label>
                                                <br>
                                                <p class="fs-6"
                                                    style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                    Berlaku Sampai</p>
                                            </div>
                                            <div class="col-md-auto">
                                                <input type="date" name="detail_valid_until" id="detail_valid_until" placeholder=""
                                                value="${tax.valid_until}" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="input-group mb-4">
                                    <div class="col-md-3">
                                        <label for="detail_taxable_entrepreneur_number">Taxable Entrepreneur Number
                                            *</label>
                                        <p class="fs-6" style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                            Nomor Surat Pengukuhan PKP</p>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" name="detail_taxable_entrepreneur_number" id="detail_taxable_entrepreneur_number"
                                            placeholder="" class="form-control" value="${tax.taxable_entrepreneur_number}">
                                    </div>
                                </div>
                                <div class="input-group mb-4">
                                    <div class="col-md-3">
                                        <label for="detail_tax_invoice_serial_number">Tax Invoice Serial No. *</label>
                                        <p class="fs-6" style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                            Nomor Serial Faktur Pajak (pada SK-PKP)</p>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" name="detail_tax_invoice_serial_number" id="detail_tax_invoice_serial_number"
                                            placeholder="" class="form-control" value="${tax.tax_invoice_serial_number}">
                                    </div>
                                </div>
                            `)
                        })
                    } else {
                        $('#list_detail_tax').empty()
                        $('#list_detail_tax').append(`
                            <div class="input-group mt-4">
                                <div class="col-md-auto mb-4">
                                    <div class="row">
                                        <div class="col-md-auto">
                                            <label for="detail_register_number_as_in_tax_invoice">Tax Register Number
                                                (As in Tax Invoice) *</label>
                                            <br>
                                            <p class="fs-6"
                                                style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                Nomor NPWP (Sesuai dengan Faktur Pajak)</p>
                                        </div>
                                        <div class="col-md-auto">
                                            <input type="text" name="detail_register_number_as_in_tax_invoice"
                                                id="detail_register_number_as_in_tax_invoice" placeholder="" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-auto mb-4">
                                    <div class="row">
                                        <div class="col-md-auto">
                                            <label for="detail_trc_number">TRC No. *</label>
                                            <br>
                                            <p class="fs-6"
                                                style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                Nomor TRC</p>
                                        </div>
                                        <div class="col-md-auto">
                                            <input type="text" name="detail_trc_number" id="detail_trc_number" placeholder=""
                                                class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="input-group">
                                <div class="col-md-auto mb-4">
                                    <div class="row">
                                        <div class="col-md-auto">
                                            <label for="detail_register_number_related_branch">Tax Register Number
                                                (Related Branch) *</label>
                                            <br>
                                            <p class="fs-6"
                                                style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                Nomor NPWP (Cabang Terkait)</p>
                                        </div>
                                        <div class="col-md-auto">
                                            <input type="text" name="detail_register_number_related_branch"
                                                id="detail_register_number_related_branch" placeholder="" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-auto mb-4">
                                    <div class="row">
                                        <div class="col-md-auto">
                                            <label for="detail_valid_until">Valid Until *</label>
                                            <br>
                                            <p class="fs-6"
                                                style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                Berlaku Sampai</p>
                                        </div>
                                        <div class="col-md-auto">
                                            <input type="date" name="detail_valid_until" id="detail_valid_until" placeholder=""
                                                class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="input-group mb-4">
                                <div class="col-md-3">
                                    <label for="detail_taxable_entrepreneur_number">Taxable Entrepreneur Number
                                        *</label>
                                    <p class="fs-6" style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                        Nomor Surat Pengukuhan PKP</p>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" name="detail_taxable_entrepreneur_number" id="detail_taxable_entrepreneur_number"
                                        placeholder="" class="form-control">
                                </div>
                            </div>
                            <div class="input-group mb-4">
                                <div class="col-md-3">
                                    <label for="detail_tax_invoice_serial_number">Tax Invoice Serial No. *</label>
                                    <p class="fs-6" style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                        Nomor Serial Faktur Pajak (pada SK-PKP)</p>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" name="detail_tax_invoice_serial_number" id="detail_tax_invoice_serial_number"
                                        placeholder="" class="form-control">
                                </div>
                            </div>
                        `)
                    }
                }
            })
        })
        // untuk update urutan id setiap element pada form dynamic alamat data
        function updateIdAddress() {
            $('.array_detail_company_address').each(function (index) {
                // Update IDs for input elements
                $(this).find('input[name="detail_address[]"]').attr('id', 'address_' + (index + 1));
                $(this).find('input[name="detail_city[]"]').attr('id', 'city_' + (index + 1));
                $(this).find('input[name="detail_country[]"]').attr('id', 'country_' + (index + 1));
                $(this).find('input[name="detail_province[]"]').attr('id', 'province_' + (index + 1));
                $(this).find('input[name="detail_zip_code[]"]').attr('id', 'zip_code_' + (index + 1));
                $(this).find('input[name="detail_telephone[]"]').attr('id', 'telephone_' + (index + 1));
                $(this).find('input[name="detail_fax[]"]').attr('id', 'fax_' + (index + 1));
                
                // Update IDs for span elements
                $(this).find('.message_address').attr('id', 'message_address_' + (index + 1));
                $(this).find('.message_city').attr('id', 'message_city_' + (index + 1));
                $(this).find('.message_country').attr('id', 'message_country_' + (index + 1));
                $(this).find('.message_province').attr('id', 'message_province_' + (index + 1));
                $(this).find('.message_zip_code').attr('id', 'message_zip_code_' + (index + 1));
                $(this).find('.message_telephone').attr('id', 'message_telephone_' + (index + 1));
                $(this).find('.message_fax').attr('id', 'message_fax_' + (index + 1));
            });
        }
        // menambah atau menghapus elemet input jika class/type bisnis adalah other
        $(document).on('change', 'input[name="detail_business_classification"]', function() {
            let value = $(this).val()
            if (value == 'Other') {
                $('#field_form_detail_business_other').append(`
                    <input type="text" name="detail_business_classification_other_detail" id="detail_business_classification_other_detail" placeholder="Other" class="form-control">
                `)
            } else {
                $('#field_form_detail_business_other').empty()
            }
        })
        // menambah form dynamic alamat data
        $(document).on('click', '#add_detail_dynamic_address', function(e) {
            e.preventDefault()
            $('.detail_dynamic_company_address').append(`
                <div class="input-group mb-4 array_detail_company_address">
                    <div class="col-md-2">
                        <label for="address">Company Address *<br>
                            (according to Company Address stated in the Tax Register Number): *</label>
                        <br>
                        <p class="fs-6" style="margin-bottom: 0.5rem !important; font-size: 10px !important;">Alamat Perusahaan
                            (sesuai dengan NPWP)</p>
                    </div>
                    <div class="col-md-10">
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <input type="text" name="detail_address[]" id="detail_address" placeholder="" class="form-control">
                                <span class="text-danger mt-2 detail_message_address" id="detail_message_address_0" role="alert"></span>

                            </div>
                            <div class="col-md-auto">
                                <div class="row">
                                    <div class="col-md-auto">
                                        <label for="detail_city">City *</label>
                                        <br>
                                        <p class="fs-6" style="margin-bottom: 0.5rem !important; font-size: 10px !important;">Kota</p>
                                    </div>
                                    <div class="col-md-auto">
                                        <input type="text" name="detail_city[]" id="detail_city" placeholder="" class="form-control">
                                        <span class="text-danger mt-2 detail_message_city" id="detail_message_city_0" role="alert"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-auto mb-4">
                                <div class="row">
                                    <div class="col-md-auto">
                                        <label for="detail_country">Country *</label>
                                        <br>
                                        <p class="fs-6" style="margin-bottom: 0.5rem !important; font-size: 10px !important;">Negara</p>
                                    </div>
                                    <div class="col-md-auto">
                                        <input type="text" name="detail_country[]" id="detail_country" placeholder="" class="form-control">
                                        <span class="text-danger mt-2 detail_message_country" id="detail_message_country_0" role="alert"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-auto mb-4">
                                <div class="row">
                                    <div class="col-md-auto">
                                        <label for="detail_province">Province *</label>
                                        <br>
                                        <p class="fs-6" style="margin-bottom: 0.5rem !important; font-size: 10px !important;">Provinsi</p>
                                    </div>
                                    <div class="col-md-auto">
                                        <input type="text" name="detail_province[]" id="detail_province" placeholder="" class="form-control">
                                        <span class="text-danger mt-2 detail_message_province" id="detail_message_province_0" role="alert"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-auto mb-4">
                                <div class="row">
                                    <div class="col-md-auto">
                                        <label for="detail_zip_code">Zip Code *</label>
                                        <br>
                                        <p class="fs-6" style="margin-bottom: 0.5rem !important; font-size: 10px !important;">Kode Pos</p>
                                    </div>
                                    <div class="col-md-auto">
                                        <input type="text" name="detail_zip_code[]" id="detail_zip_code" placeholder="" class="form-control">
                                        <span class="text-danger mt-2 detail_message_zip_code" id="detail_message_zip_code_0" role="alert"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-auto mb-4">
                                <div class="row">
                                    <div class="col-md-auto">
                                        <label for="detail_telephone">Telephone *</label>
                                        <p class="fs-6" style="margin-bottom: 0.5rem !important; font-size: 10px !important;">+ [Country-Area Code] [No.]
                                            Telepon +[Negara-Area] [No.]</p>
                                    </div>
                                    <div class="col-md-auto">
                                        <input type="text" name="detail_telephone[]" id="detail_telephone" placeholder="" class="form-control">
                                        <span class="text-danger mt-2 detail_message_telephone" id="detail_message_telephone_0" role="alert"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-auto mb-4">
                                <div class="row">
                                    <div class="col-md-auto">
                                        <label for="detail_fax">Fax *</label>
                                        <p class="fs-6" style="margin-bottom: 0.5rem !important; font-size: 10px !important;">+ [Country-Area Code] [No.]
                                            Fax +[Negara-Area] [No.]</p>
                                    </div>
                                    <div class="col-md-auto">
                                        <input type="text" name="detail_fax[]" id="detail_fax" placeholder="" class="form-control">
                                        <span class="text-danger mt-2 detail_message_fax" id="detail_message_fax_0" role="alert"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col mb-4 align-items-end">
                                <button type="button" class="btn btn-danger float-right delete_dynamic_address" id="delete_dynamic_address">- Address</button>
                            </div>
                        </div>
                    </div>
                </div>
            `)
            updateIdAddress()
        })
        // menghapus form dynamic alamat data
        $(document).on('click', '#delete_dynamic_address', function(e) {
            $(this).closest('.array_detail_company_address').remove();
            updateIdAddress();
        })
        // untuk update urutan id setiap element pada form dynamic bank data
        function updateIdBank() {
            $('.array_dymanic_bank').each(function(index) {
                $(this).find('input[name="detail_bank_name[]"]').attr('id', 'detail_bank_name_' + (index + 1));
                $(this).find('input[name="detail_branch[]"]').attr('id', 'detail_branch_' + (index + 1));
                $(this).find('input[name="detail_account_name[]"]').attr('id', 'detail_account_name_' + (index + 1));
                $(this).find('input[name="detail_city_or_country[]"]').attr('id', 'detail_city_or_country_' + (index + 1));
                $(this).find('input[name="detail_account_number[]"]').attr('id', 'detail_account_number_' + (index + 1));
                $(this).find('input[name="detail_currency[]"]').attr('id', 'detail_currency_' + (index + 1));
                $(this).find('input[name="detail_swift_code[]"]').attr('id', 'detail_swift_code_' + (index + 1));
            })
        }
        // menambah form dynamic bank data
        $(document).on('click', '#add_bank', function(e) {
            e.preventDefault()
            $('.dynamic_bank').append(`
                <div class="array_dymanic_bank">
                    <div class="input-group mt-4">
                        <div class="col-md-6 mb-4">
                            <div class="row">
                                <div class="col-md-auto">
                                    <label for="detail_bank_name">Bank Name *</label>
                                    <br>
                                    <p class="fs-6" style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                        Nama Bank</p>
                                </div>
                                <div class="col-md-auto">
                                    <input type="text" name="detail_bank_name[]" id="detail_bank_name"
                                        placeholder="" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-auto">
                                    <label for="detail_branch">Branch *</label>
                                    <br>
                                    <p class="fs-6" style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                        Cabang</p>
                                </div>
                                <div class="col-md-auto">
                                    <input type="text" name="detail_branch[]" id="detail_branch" placeholder="" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="input-group">
                        <div class="col-md-6 mb-4">
                            <div class="row">
                                <div class="col-md-auto">
                                    <label for="detail_account_name">Account Name *</label>
                                    <br>
                                    <p class="fs-6" style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                        Rekening Atas Nama</p>
                                </div>
                                <div class="col-md-auto">
                                    <input type="text" name="detail_account_name[]" id="detail_account_name" placeholder="" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="row">
                                <div class="col-md-auto">
                                    <label for="detail_city_or_country">City/Country *</label>
                                    <br>
                                    <p class="fs-6" style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                        Kota/Negara</p>
                                </div>
                                <div class="col-md-auto">
                                    <input type="text" name="detail_city_or_country[]" id="detail_city_or_country" placeholder="" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="input-group">
                        <div class="col-md-auto mb-4">
                            <div class="row">
                                <div class="col-md-auto">
                                    <label for="detail_account_number">Account No. *</label>
                                    <br>
                                    <p class="fs-6" style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                        No Rekening</p>
                                </div>
                                <div class="col-md-auto">
                                    <input type="text" name="detail_account_number[]" id="detail_account_number" placeholder="" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-auto mb-4">
                            <div class="row">
                                <div class="col-md-auto">
                                    <label for="detail_currency">Currency *</label>
                                    <br>
                                    <p class="fs-6" style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                        Mata Uang</p>
                                </div>
                                <div class="col-md-auto">
                                    <input type="text" name="detail_currency[]" id="detail_currency" placeholder="" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-auto">
                            <div class="row">
                                <div class="col-md-auto">
                                    <label for="detail_swift_code">Swift Code *</label>
                                    <br>
                                    <p class="fs-6" style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                        Optional</p>
                                </div>
                                <div class="col-md-auto">
                                    <input type="text" name="detail_swift_code[]" id="detail_swift_code" placeholder="" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="input-group d-flex justify-content-end mb-4 mt-4">
                        <button type="button" class="btn btn-danger" id="delete_bank">- Bank</button>
                    </div>
                </div>
            `)
            updateIdBank()
        })
        // menghapus form dynamic bank data
        $(document).on('click', '#delete_bank', function(e) {
            e.preventDefault()
            $(this).closest('.array_dymanic_bank').remove()
            updateIdBank()
        })
        // opsi update data foto/scan stempel
        $(document).on('click', '.title_change_stamp', function() {
            // $('#detail_stamp_file').attr('hidden', false)
            $('.form_field_detail_stamp').empty()
            $('.form_field_detail_stamp').append(`<input type="file" name="detail_stamp_file" id="detail_stamp_file" class="form-control mb-2" />`)
            $('.button-change-stamp').empty()
            $('.button-change-stamp').append(`
                <i class="fas fa-times-circle text-danger cancel_change_stamp" title="Cancel perubahan stempel" for="cancel_change_stamp"></i>
                <span class="info-box-text text-danger cancel_change_stamp" id="cancel_change_stamp" title="Cancel perubahan stempel"><small>Cancel perubahan stempel</small></span>
            `)
        })
        // opsi update data foto/scan tanda tangan
        $(document).on('click', '.title_change_signature', function() {
            // $('#detail_signature_file').attr('hidden', false)
            $('.form_field_detail_signature').empty()
            $('.form_field_detail_signature').append(`<input type="file" name="detail_signature_file" id="detail_signature_file" class="form-control mb-2" />`)
            $('.button-change-signature').empty()
            $('.button-change-signature').append(`
                <i class="fas fa-times-circle text-danger cancel_change_signature" title="Cancel perubahan tanda tangan" for="cancel_change_signature"></i>
                <span class="info-box-text text-danger cancel_change_signature" id="cancel_change_signature" title="Cancel perubahan tanda tangan"><small>Cancel perubahan tanda tangan</small></span>
            `)
        })
        // cancel update data foto/scan stempel
        $(document).on('click', '#cancel_change_stamp', function() {
            // $("#detail_stamp_file").attr("hidden",true);
            $('.form_field_detail_stamp').empty()
            $('.button-change-stamp').empty()
            $('.button-change-stamp').append(`
                <i class="fas fa-edit title_change_stamp" title="Ubah data stempel" for="title_change_stamp"></i>
                <span class="info-box-text title_change_stamp" id="title_change_stamp" title="Ubah data stempel"><small>Ubah data stempel</small></span>
            `)
        })
        // cancel update data foto/scan tanda tangan
        $(document).on('click', '#cancel_change_signature', function() {
            // $("#detail_signature_file").attr("hidden",true);
            $('.form_field_detail_signature').empty()
            $('.button-change-signature').empty()
            $('.button-change-signature').append(`
                <i class="fas fa-edit title_change_signature" title="Ubah data tanda tangan" for="title_change_signature"></i>
                <span class="info-box-text title_change_signature" id="title_change_signature" title="Ubah data tanda tangan"><small>Ubah data tanda tangan</small></span>
            `)
        })
        // update data ketika kondisi masih checking 1
        $(document).on('click', '#btn_update_data_company', function(e) {
            e.preventDefault()
            let data_form_company = new FormData($('#form_detail_partner_data_by_user')[0])
            
            $.ajax({
                url: '{{ route("update-partner") }}',
                method: 'POST',
                processData: false,
                contentType: false,
                cache: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: data_form_company,
                enctype: 'multipart/form-data',
                beforeSend: function() {
                    setTimeout(function() {
                        $('#modalLoading').modal({
                            show: false
                        });
                    }, 5000)
                },

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
                    $('#modalCreatePartner').modal('hide');
                    $('#modalLoading').modal('hide')

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
        // menambahkan data attachment partner
        $(document).on('click', '#add_data_support_document', function(e) {
            e.preventDefault()
            let data_id = $(this).data('data_id')
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
                    $('#supporting_document_partner_id').val(data_id)
                    $('#supporting_document_type_0').empty()
                    $('#supporting_document_type_0').append(`<option value="">tipe dokumen</option>`)
                    $.each(res.data, function(i, data) {
                        $('#supporting_document_type_0').append(`<option value="${data.id+`|`+data.name}">${data.name}</option>`)
                    })
                    
                    // add all data class business
                    let list_class_business = [
                        'pt',
                        'cv',
                        'ud_or_pd',
                        'perorangan'
                    ]
                    $('#supporting_document_business_type_0').empty()
                    $('#supporting_document_business_type_0').append(`<option value="">tipe usaha</option>`)
                    $.each(list_class_business, function(i, data) {
                        $('#supporting_document_business_type_0').append(`<option value="${data}">${data}</option>`)
                    })
                }
            })
        })
        // update each id supporting document
        function updateIdCreateAttachment() {
            $('.array_data_supporting_document').each(function(i) {
                $(this).find('select[name="supporting_document_type[]"]').attr('id', 'supporting_document_type_' + (i + 1));
                $(this).find('select[name="supporting_document_business_type[]"]').attr('id', 'supporting_document_business_type_' + (i + 1));
                $(this).find('input[name="file_supporting_document[]"]').attr('id', 'file_supporting_document_' + (i + 1));

                $(this).find('.message_supporting_document_type').attr('id', 'message_supporting_document_type_' + (i + 1));
                $(this).find('.message_supporting_document_business_type').attr('id', 'message_supporting_document_business_type_' + (i + 1));
                $(this).find('.message_file_supporting_document').attr('id', 'message_file_supporting_document_' + (i + 1));
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
            
            $('.select2_supporting_document').select2({
                dropdownParent: $('#modal_support_document'),
                width: '100%'
            })
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
                    $('#supporting_document_type_' + total_field_document).append(`<option value="">tipe dokumen</option>`)
                    $.each(res.data, function(i, data) {
                        $('#supporting_document_type_0').append(`<option value="${data.id+`|`+data.name}">${data.name}</option>`)
                    })
                    // add all data class business
                    let list_class_business = [
                        'pt',
                        'cv',
                        'ud_or_pd',
                        'perorangan'
                    ]
                    $('#supporting_document_business_type_' + total_field_document).empty()
                    $('#supporting_document_business_type_' + total_field_document).append(`<option value="">tipe usaha</option>`)
                    $.each(list_class_business, function(i, data) {
                        $('#supporting_document_business_type_' + total_field_document).append(`<option value="${data}">${data}</option>`)
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
            // Create a FormData object to handle files and form data
            var formData = new FormData($('#form_submit_supporting_document')[0]);
            $.ajax({
                url: '{{ route("store-attachment") }}',
                type: 'POST',
                data: formData,
                processData: false,   // Prevent jQuery from processing the data
                contentType: false,   // Prevent jQuery from setting content type
                cache: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                enctype: 'multipart/form-data',
                success: function (res) {
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
                    $('#modal_support_document').modal('hide');  // Close modal on success
                },
                error: function(xhr) {
                    fetchDataPartner()
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
