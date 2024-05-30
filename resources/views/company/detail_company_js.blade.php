<script>
    $(document).ready(function() {
        fetchDetailPartner()

        function fetchDetailPartner() {
            $.ajax({
                url: '{{ route('fetch-partner') }}',
                type: 'GET',
                dataType: 'json',
                async: true,
                success: function(res) {
                    $('#field_form_detail_business_other').empty()
                    $('#form_detail_company_partner')[0].reset()
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
                        checked_business = res.data.business_classification == business ?
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

                    $('#detail_system_management').val(res.data.system_management)
                    $('#detail_contact_person').val(res.data.contact_person)

                    // if($('input[name="detail_communication_language"]'))
                    $('.detail_option_languange').empty()
                    let data_language = ['Bahasa', 'English'];
                    let checked_language = '';
                    $.each(data_language, function(i, language) {
                        // alert(language)
                        checked_language = res.data.communication_language == language ?
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
                    // $('#detail_communication_language').val(res.data.contact_person)
                    $('#detail_email_address').val(res.data.email_address)

                    let list_addres = res.data.address
                    $('#detail_company_address_additional').empty()
                    if (list_addres.length <= 1) {
                        $.each(list_addres, function(i, address) {
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
                                            <div class="col mb-4 align-items-end mr-4">
                                                <button type="button" class="btn btn-primary float-right"
                                                    id="add_detail_ynamic_address">+ Address</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="detail_dynamic_company_address">
    
                                </div>
                            `)
                        })
                        $('#detail_company_address_additional').append(`
                        <div class="detail_dynamic_company_address"></div>
                        `)
                    } else {
                        $.each(list_addres, function(i, address) {
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
                                            <div class="col mb-4 align-items-end mr-4">
                                                <button type="button" class="btn btn-primary float-right"
                                                    id="add_detail_ynamic_address">+ Address</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="detail_dynamic_company_address">
    
                                </div>
                            `)
                        })
                        $('#detail_company_address_additional').append(`
                        <div class="detail_dynamic_company_address"></div>
                        `)
                    }

                    // alert(list_addres.length)
                }
            })
        }

        $(document).on('change', '.detail_business_classification', function() {
            let value = $(this).val()
            let business_other = 'Other'
            if (value == business_other) {
                $('#field_form_detail_business_other').append(`
                    <input type="text" name="detail_business_classification_other_detail" id="detail_business_classification_other_detail" placeholder="Other" class="form-control" value="">
                        `)
            } else {
                $('#field_form_detail_business_other').empty()
            }
        })
    })
</script>
