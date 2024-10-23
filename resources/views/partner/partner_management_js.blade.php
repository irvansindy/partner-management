<script>
    $(document).ready(function() {
        $('#partner_table').DataTable({
            processing: true,
            // serverside: true,
            ajax: {
                url: '{{ route("fetch-partner-list") }}',
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
                    "data": "group_name",
                    "defaultContent": "<i>Not set</i>"
                },
                {
                    "data": "status",
                    "defaultContent": "<i>Not set</i>"
                },
                {
                    'data': null,
                    title: 'Action',
                    wrap: true,
                    "render": function(item) {
                        return '<button type="button" data-partner_id="'+item.id+'" class="btn btn-outline-info btn-sm mt-2 detail_partner" data-toggle="modal" data-target="#ModalDetailPartner">View</button>'
                    }
                },
            ]
        })
        // <button type="button" data-partner_id="'+item.id+'" data-partner_name="'+item.name+'" class="btn btn-outline-danger btn-sm mt-2 delete_user" data-toggle="modal" data-target="#confirmDeleteUser">Delete</button>
        $(document).on('click', '.detail_partner', function(e) {
            e.preventDefault()
            let partner_id = $(this).data('partner_id')
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route("fetch-partner-detail") }}',
                type: 'GET',
                data: {
                    partner_id: partner_id
                },
                dataType: 'json',
                async: true,
                success: function(res) {
                    $('#field_form_detail_business_other').empty()
                    $('#form_detail_company_partner')[0].reset()
                    $('#detail_company_id').val(res.data[0].id)
                    $('#detail_company_name').val(res.data[0].name)
                    $('#detail_company_group_name').val(res.data[0].group_name)
                    $('#detail_established_year').val(res.data[0].established_year)
                    $('#detail_total_employee').val(res.data[0].total_employee)
                    $('#detail_liable_person_and_position').val(res.data[0].liable_person_and_position)
                    $('#detail_owner_name').val(res.data[0].owner_name)
                    $('#detail_board_of_directors').val(res.data[0].board_of_directors)
                    $('#detail_major_shareholders').val(res.data[0].major_shareholders)

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
                        checked_business = res.data[0].business_classification == business ?
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
                    if (res.data[0].business_classification == 'Other') {
                        $('#field_form_detail_business_other').append(`
                        <input type="text" name="detail_business_classification_other_detail" id="detail_business_classification_other_detail" placeholder="Other" class="form-control" value="${res.data[0].other_business}">
                        `)
                    } else {
                        $('#field_form_detail_business_other').empty()
                    }

                    $('#detail_website_address').val(res.data[0].website_address)
                    $('#detail_website_address').val(res.data[0].website_address)

                    $('.detail_system_management').empty()
                    $('#detail_system_management').val(res.data[0].system_management)
                    $('#detail_contact_person').val(res.data[0].contact_person)
                    $('#btn_update_data_company').attr('data-id', res.data[0].id)
                    $('#btn_update_data_company').attr('data-status', res.data[0].status)

                    // if($('input[name="detail_communication_language"]'))
                    $('.detail_option_languange').empty()
                    let data_language = ['Bahasa', 'English'];
                    let checked_language = '';
                    $.each(data_language, function(i, language) {
                        // alert(language)
                        checked_language = res.data[0].communication_language == language ?
                            'checked' : ''
                        $('.detail_option_languange').append(`
                            <div class="form-check form-check-inline mb-1">
                                <input class="form-check-input" style="margin-bottom: 1rem !important" ${checked_language}
                                    type="radio" name="detail_communication_language"
                                    id="detail_communication_language_bahasa" value="Bahasa">
                                <label class="form-check-label" for="detail_communication_language_bahasa">
                                    Bahasa
                                    <p class="fs-6"
                                        style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                        Indonesia</p>
                                </label>
                            </div>
                        `)
                    })
                    // $('#detail_communication_language').val(res.data[0].contact_person)
                    $('#detail_email_address').val(res.data[0].email_address)

                    let list_address = res.data[0].address
                    $('#detail_company_address_additional').empty()
                    if (list_address.length <= 1) {
                        $.each(list_address, function(i, address) {
                            $('#detail_company_address_additional').append(`
                                <div class="input-group mb-4">
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
                                                <input type="text" name="detail_address[]" id="detail_address" placeholder="" value="${address.address}"
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
                                                        <input type="text" name="detail_city[]" id="detail_city" placeholder="" value="${address.city}"
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
                                                        <input type="text" name="detail_country[]" id="detail_country" placeholder="" value="${address.country}"
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
                                                        <input type="text" name="detail_province[]" id="detail_province" value="${address.province}"
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
                                                        <input type="text" name="detail_zip_code[]" id="detail_zip_code" value="${address.zip_code}"
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
                                                        <input type="text" name="detail_telephone[]" id="detail_telephone" value="${address.telephone}"
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
                                                        <input type="text" name="detail_fax[]" id="detail_fax" placeholder="" value="${address.fax}"
                                                            class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `)
                        })
                        $('#detail_company_address_additional').append(`
                        <div class="detail_dynamic_company_address"></div>
                        `)
                    } else {
                        $.each(list_address, function(i, address) {
                            $('#detail_company_address_additional').append(`
                                <div class="input-group mb-4">
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
                                                <input type="text" name="detail_address[]" id="detail_address" placeholder="" value="${address.address}"
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
                                                        <input type="text" name="detail_city[]" id="detail_city" placeholder="" value="${address.city}"
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
                                                        <input type="text" name="detail_country[]" id="detail_country" placeholder="" value="${address.country}"
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
                                                        <input type="text" name="detail_province[]" id="detail_province" value="${address.province}"
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
                                                        <input type="text" name="detail_zip_code[]" id="detail_zip_code" value="${address.zip_code}"
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
                                                        <input type="text" name="detail_telephone[]" id="detail_telephone" value="${address.telephone}"
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
                                                        <input type="text" name="detail_fax[]" id="detail_fax" placeholder="" value="${address.fax}"
                                                            class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `)
                        })
                        $('#detail_company_address_additional').append(`
                        <div class="detail_dynamic_company_address"></div>
                        `)
                    }

                    // alert(res.data[0].bank.length)
                    let list_bank = res.data[0].bank
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
                            `)
                        })
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
                        `)
                    }

                    let list_tax = res.data[0].tax
                    if (list_tax.length != 0) {
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

                    let list_doc_pt = res.data['pt']
                    let list_doc_cv = res.data['cv']
                    let list_doc_ud_or_pd = res.data['ud_or_pd']
                    let list_doc_perorangan = res.data['perorangan']
                    
                    $('#data_doc_type_pt').empty()
                    $.each(list_doc_pt, function(i, data) {
                        $('#data_doc_type_pt').append(`
                        <tr>
                            <td>${data.document_type_name}</td>
                            <td>
                                <a href="{{ asset('${data.document}') }}" target="_blank">
                                    <i class="fas fa-regular fa-file"></i> ${data.document_type_name}
                                </a>
                            </td>
                        </tr>`
                        )
                    })  
                    
                    $('#data_doc_type_cv').empty()
                    $.each(list_doc_cv, function(i, data) {
                        $('#data_doc_type_cv').append(`
                        <tr>
                            <td>${data.document_type_name}</td>
                            <td>
                                <a href="{{ asset('${data.document}') }}" target="_blank">
                                    <i class="fas fa-regular fa-file"></i> ${data.document_type_name}
                                </a>
                            </td>
                        </tr>`
                        )
                    })
                    
                    $('#data_doc_type_ud_or_pd').empty()
                    $.each(list_doc_ud_or_pd, function(i, data) {
                        $('#data_doc_type_ud_or_pd').append(`
                        <tr>
                            <td>${data.document_type_name}</td>
                            <td>
                                <a href="{{ asset('${data.document}') }}" target="_blank">
                                    <i class="fas fa-regular fa-file"></i> ${data.document_type_name}
                                </a>
                            </td>
                        </tr>`
                        )
                    })
                    
                    $('#data_doc_type_perorangan').empty()
                    $.each(list_doc_perorangan, function(i, data) {
                        $('#data_doc_type_perorangan').append(`
                        <tr>
                            <td>${data.document_type_name}</td>
                            <td>
                                <a href="{{ asset('${data.document}') }}" target="_blank">
                                    <i class="fas fa-regular fa-file"></i> ${data.document_type_name}
                                </a>
                            </td>
                        </tr>`
                        )
                    })

                    $('#button-for-approval').empty()
                    $('#button-for-approval').append(`<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>`)

                    if(res.data.is_approved === true) {
                        $('#button-for-approval').append(`<button type="button" class="btn btn-danger" id="partner_reject">Reject</button><button type="button" class="btn btn-primary" id="partner_approved">Approved</button>`)
                    }
                }
            })
        })

        $(document).on('click', '#partner_approved', function(e) {
            e.preventDefault()
            let partner_id = $('#detail_company_id').val()
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route("approval-partner") }}',
                type: 'POST',
                data: {
                    partner_id: partner_id,
                    status: 'approved'
                },
                dataType: 'json',
                async: true,
                success: function(res) {
                    $('#partner_table').DataTable().ajax.reload();
                    $('#ModalDetailPartner').modal('toggle');
                    $(document).Toasts('create', {
                        title: 'Success',
                        class: 'bg-success',
                        body: 'Partner berhasil di-approve.'
                    });
                },
                error: function(xhr) {
                    let response_error = JSON.parse(xhr.responseText)
                    $(document).Toasts('create', {
                        title: 'Error',
                        class: 'bg-danger',
                        body: 'gagal update, silahkan hubungi pihak ICT.'
                    });
                }
            })
        })

        $(document).on('click', '#partner_reject', function(e) {
            e.preventDefault()
            let partner_id = $('#detail_company_id').val()
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route("approval-partner") }}',
                type: 'POST',
                data: {
                    partner_id: partner_id,
                    status: 'reject'
                },
                dataType: 'json',
                async: true,
                success: function(res) {
                    $('#partner_table').DataTable().ajax.reload();
                    $('#ModalDetailPartner').modal('toggle');
                    $(document).Toasts('create', {
                        title: 'Success',
                        class: 'bg-success',
                        body: 'Partner berhasil di-reject.'
                    });
                },
                error: function(xhr) {
                    let response_error = JSON.parse(xhr.responseText)
                    $(document).Toasts('create', {
                        title: 'Error',
                        class: 'bg-danger',
                        body: 'gagal update, silahkan hubungi pihak ICT.'
                    });
                }
            })
        })
        
        $(document).on('click', '#for_export_pdf', function(e) {
            e.preventDefault()
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route("export-pdf") }}',
                type: 'GET',
                dataType: 'json',
                async: true,
            })
        })
    })
</script>