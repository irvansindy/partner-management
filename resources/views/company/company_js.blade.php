<script>
    fetchDocTypeCategories()

    function fetchDocTypeCategories() {
        $.ajax({
            url: '{{ route("fetch-doctype") }}',
            type: 'GET',
            dataType: 'json',
            async: true,
            success: function(res) {
                // alert('success')
                $('#data_doc_type_pt').empty()
                $.each(res.data, function(i, data) {
                    $('#data_doc_type_pt').append(`
                    <tr>
                        <td>${data.name}</td>
                        <td>
                            <input type="file" name="${data.name_id_class+'_pt'}" id="${data.name_id_class+'_pt'}" class="form-control ${data.name_id_class+'_pt'}" />
                        </td>
                    </tr>`
                    )
                })
                
                $('#data_doc_type_cv').empty()
                $.each(res.data, function(i, data) {
                    $('#data_doc_type_cv').append(`
                    <tr>
                        <td>${data.name}</td>
                        <td>
                            <input type="file" name="${data.name_id_class+'_cv'}" id="${data.name_id_class+'_cv'}" class="form-control ${data.name_id_class+'_cv'}" />
                        </td>
                    </tr>`
                    )
                })
                
                $('#document_type_ud_or_pd').empty()
                $.each(res.data, function(i, data) {
                    $('#document_type_ud_or_pd').append(`
                    <tr>
                        <td>${data.name}</td>
                        <td>
                            <input type="file" name="${data.name_id_class+'_ud_or_pd'}" id="${data.name_id_class+'_ud_or_pd'}" class="form-control ${data.name_id_class+'_ud_or_pd'}" />
                        </td>
                    </tr>`
                    )
                })
                
                $('#data_doc_type_perorangan').empty()
                $.each(res.data, function(i, data) {
                    $('#data_doc_type_perorangan').append(`
                    <tr>
                        <td>${data.name}</td>
                        <td>
                            <input type="file" name="${data.name_id_class+'_perorangan'}" id="${data.name_id_class+'_perorangan'}" class="form-control ${data.name_id_class+'_perorangan'}" />
                        </td>
                    </tr>`
                    )
                })
            }
        })
    }
    $(document).ready(function() {
        // alert('success load')

        $(document).on('click', '#add_dynamic_list_document', function(e) {
            e.preventDefault()
            $('.dynamic_list_document').append(`
                <div class="array_dynamic_list_document">
                    <div class="input-group mt-2">
                        <div class="col-md mb-4 px-4">
                            <div class="row">
                                <div class="col-md-auto">
                                    <label for="company_doc_type">Document for.... *</label>
                                    <br>
                                    <p class="fs-6" style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                        Lampiran dokumen untuk</p>
                                </div>
                                <div class="col-md-auto">
                                    <select class="form-control" name="company_doc_type" id="company_doc_type">
                                        <option value="PT">PT</option>
                                        <option value="CV">CV</option>
                                        <option value="UD/PD">UD/PD</option>
                                        <option value="Perorangan">Perorangan</option>
                                    </select>
                                </div>
                            </div>
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Doc Type</th>
                                        <th>File</th>
                                    </tr>
                                </thead>
                                <tbody id="data_doc_type">
                                    <tr>
                                        <td>KTP</td>
                                        <td>ktp saya</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="input-group d-flex justify-content-end mr-4 my-4">
                        <button type="button" class="btn btn-danger" id="pop_dynamic_list_document">
                            - Document
                        </button>
                    </div>
                </div>
            `)
        })

        $(document).on('click', '#pop_dynamic_list_document', function(e) {
            e.preventDefault()
            $(this).closest('.array_dynamic_list_document').remove()
        })

        $(document).on('click', '#add_dynamic_address',function(e) {
            e.preventDefault()
            $('.dynamic_company_address').append(`
                <div class="input-group mb-4 array_company_address">
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
                                <input type="text" name="address[]" id="address" placeholder="" class="form-control">
                            </div>
                            <div class="col-md-auto">
                                <div class="row">
                                    <div class="col-md-auto">
                                        <label for="city">City *</label>
                                        <br>
                                        <p class="fs-6" style="margin-bottom: 0.5rem !important; font-size: 10px !important;">Kota</p>
                                    </div>
                                    <div class="col-md-auto">
                                        <input type="text" name="city[]" id="city" placeholder="" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-auto mb-4">
                                <div class="row">
                                    <div class="col-md-auto">
                                        <label for="country">Country *</label>
                                        <br>
                                        <p class="fs-6" style="margin-bottom: 0.5rem !important; font-size: 10px !important;">Negara</p>
                                    </div>
                                    <div class="col-md-auto">
                                        <input type="text" name="country[]" id="country" placeholder="" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-auto mb-4">
                                <div class="row">
                                    <div class="col-md-auto">
                                        <label for="province">Province *</label>
                                        <br>
                                        <p class="fs-6" style="margin-bottom: 0.5rem !important; font-size: 10px !important;">Provinsi</p>
                                    </div>
                                    <div class="col-md-auto">
                                        <input type="text" name="province[]" id="province" placeholder="" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-auto mb-4">
                                <div class="row">
                                    <div class="col-md-auto">
                                        <label for="zip_code">Zip Code *</label>
                                        <br>
                                        <p class="fs-6" style="margin-bottom: 0.5rem !important; font-size: 10px !important;">Kode Pos</p>
                                    </div>
                                    <div class="col-md-auto">
                                        <input type="text" name="zip_code[]" id="zip_code" placeholder="" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-auto mb-4">
                                <div class="row">
                                    <div class="col-md-auto">
                                        <label for="telephone">Telephone *</label>
                                        <p class="fs-6" style="margin-bottom: 0.5rem !important; font-size: 10px !important;">+ [Country-Area Code] [No.]
                                            Telepon +[Negara-Area] [No.]</p>
                                    </div>
                                    <div class="col-md-auto">
                                        <input type="text" name="telephone[]" id="telephone" placeholder="" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-auto mb-4">
                                <div class="row">
                                    <div class="col-md-auto">
                                        <label for="fax">Fax *</label>
                                        <p class="fs-6" style="margin-bottom: 0.5rem !important; font-size: 10px !important;">+ [Country-Area Code] [No.]
                                            Fax +[Negara-Area] [No.]</p>
                                    </div>
                                    <div class="col-md-auto">
                                        <input type="text" name="fax[]" id="fax" placeholder="" class="form-control">
                                    </div>
                                    <div class="col-md-auto">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" name="main_address[]" id="main_address">
                                            <label class="form-check-label" for="main_address" style="margin-top: 2px !important;">
                                                Main Address
                                            </label>
                                            <p class="fs-6" style="margin-bottom: 0.5rem !important; font-size: 10px !important;">Alamat Utama</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col mb-4 align-items-end mr-4">
                                <button type="button" class="btn btn-danger float-right" id="delete_dynamic_address">- Address</button>
                            </div>
                        </div>
                    </div>
                </div>
            `)
        })

        $(document).on('click', '#add_bank', function(e) {
            e.preventDefault()
            $('.dynamic_bank').append(`
                <div class="array_dymanic_bank">
                    <div class="input-group mt-4">
                        <div class="col-md-6 mb-4">
                            <div class="row">
                                <div class="col-md-auto">
                                    <label for="bank_name">Bank Name *</label>
                                    <br>
                                    <p class="fs-6" style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                        Nama Bank</p>
                                </div>
                                <div class="col-md-auto">
                                    <input type="text" name="bank_name[]" id="bank_name"
                                        placeholder="" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-auto">
                                    <label for="branch">Branch *</label>
                                    <br>
                                    <p class="fs-6" style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                        Cabang</p>
                                </div>
                                <div class="col-md-auto">
                                    <input type="text" name="branch[]" id="branch" placeholder="" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="input-group">
                        <div class="col-md-6 mb-4">
                            <div class="row">
                                <div class="col-md-auto">
                                    <label for="account_name">Account Name *</label>
                                    <br>
                                    <p class="fs-6" style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                        Rekening Atas Nama</p>
                                </div>
                                <div class="col-md-auto">
                                    <input type="text" name="account_name[]" id="account_name" placeholder="" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="row">
                                <div class="col-md-auto">
                                    <label for="city_or_country">City/Country *</label>
                                    <br>
                                    <p class="fs-6" style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                        Kota/Negara</p>
                                </div>
                                <div class="col-md-auto">
                                    <input type="text" name="city_or_country[]" id="city_or_country" placeholder="" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="input-group">
                        <div class="col-md-auto mb-4">
                            <div class="row">
                                <div class="col-md-auto">
                                    <label for="account_number">Account No. *</label>
                                    <br>
                                    <p class="fs-6" style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                        No Rekening</p>
                                </div>
                                <div class="col-md-auto">
                                    <input type="text" name="account_number[]" id="account_number" placeholder="" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-auto mb-4">
                            <div class="row">
                                <div class="col-md-auto">
                                    <label for="currency">Currency *</label>
                                    <br>
                                    <p class="fs-6" style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                        Mata Uang</p>
                                </div>
                                <div class="col-md-auto">
                                    <input type="text" name="currency[]" id="currency" placeholder="" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-auto">
                            <div class="row">
                                <div class="col-md-auto">
                                    <label for="swift_code">Swift Code *</label>
                                    <br>
                                    <p class="fs-6" style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                        Optional</p>
                                </div>
                                <div class="col-md-auto">
                                    <input type="text" name="swift_code[]" id="swift_code" placeholder="" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="input-group d-flex justify-content-end mb-4 mt-4">
                        <button type="button" class="btn btn-danger" id="delete_bank">- Bank</button>
                    </div>
                </div>
            `)
        })

        $(document).on('click', '#delete_bank', function(e) {
            e.preventDefault()
            $(this).closest('.array_dymanic_bank').remove()
        })
    
        $(document).on('click', '#delete_dynamic_address', function(e) {
            $(this).closest('.array_company_address').remove()
        })
    
        $(document).on('click', '#add_info', function(e) {
            $('.dynamic_add_info').append(`
                <div class="input-group mb-4 array_add_info">
                    <div class="col-md-2">
                        <label for="address_add_info">Another Company Address *</label>
                        <br>
                        <p class="fs-6" style="margin-bottom: 0.5rem !important; font-size: 10px !important;">Alamat Perusahaan lainnya:</p>
                    </div>
                    <div class="col-md-10">
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <input type="text" name="address_add_info[]" id="" placeholder="" class="form-control">
                            </div>
                            <div class="col-md-auto">
                                <div class="row">
                                    <div class="col-md-auto">
                                        <label for="city_add_info">City *</label>
                                        <br>
                                        <p class="fs-6" style="margin-bottom: 0.5rem !important; font-size: 10px !important;">Kota</p>
                                    </div>
                                    <div class="col-md-auto">
                                        <input type="text" name="city_add_info[]" id="city_add_info" placeholder="" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-auto mb-4">
                                <div class="row">
                                    <div class="col-md-auto">
                                        <label for="country_add_info">Country *</label>
                                        <br>
                                        <p class="fs-6" style="margin-bottom: 0.5rem !important; font-size: 10px !important;">Negara</p>
                                    </div>
                                    <div class="col-md-auto">
                                        <input type="text" name="country_add_info[]" id="country_add_info" placeholder="" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-auto mb-4">
                                <div class="row">
                                    <div class="col-md-auto">
                                        <label for="province_add_info">Province *</label>
                                        <br>
                                        <p class="fs-6" style="margin-bottom: 0.5rem !important; font-size: 10px !important;">Provinsi</p>
                                    </div>
                                    <div class="col-md-auto">
                                        <input type="text" name="province_add_info[]" id="province_add_info" placeholder="" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-auto mb-4">
                                <div class="row">
                                    <div class="col-md-auto">
                                        <label for="zip_code_add_info">Zip Code *</label>
                                        <br>
                                        <p class="fs-6" style="margin-bottom: 0.5rem !important; font-size: 10px !important;">Kode Pos</p>
                                    </div>
                                    <div class="col-md-auto">
                                        <input type="text" name="zip_code_add_info[]" id="zip_code_add_info" placeholder="" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-auto mb-4">
                                <div class="row">
                                    <div class="col-md-auto">
                                        <label for="telephone_add_info">Telephone *</label>
                                        <p class="fs-6" style="margin-bottom: 0.5rem !important; font-size: 10px !important;">+ [Country-Area Code] [No.]
                                            Telepon <br>+ [Negara-Area] [No.]</p>
                                    </div>
                                    <div class="col-md-auto">
                                        <input type="text" name="telephone_add_info[]" id="telephone_add_info" placeholder="" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-auto mb-4">
                                <div class="row">
                                    <div class="col-md-auto">
                                        <label for="fax_add_info">Fax *</label>
                                        <p class="fs-6" style="margin-bottom: 0.5rem !important; font-size: 10px !important;">+ [Country-Area Code] [No.]
                                            Fax <br>+ [Negara-Area] [No.]</p>
                                    </div>
                                    <div class="col-md-auto">
                                        <input type="text" name="fax_add_info[]" id="fax_add_info" placeholder="" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="col align-items-end mr-4">
                                <button type="button" class="btn btn-danger float-right" id="delete_add_info">- Address</button>
                            </div>
                        </div>
                    </div>
                </div>
            `)
        })
    
        $(document).on('click', '#delete_add_info', function(e) {
            e.preventDefault()
            $(this).closest('.array_add_info').remove()
        })
    
        $(document).on('click', '#btn_submit_data_company', function(e) {
            e.preventDefault()
            // let company_name = $('#company_name').val()
            // let company_group_name = $('#company_group_name').val()
            // let established_year = $('#established_year').val()
            // let total_employee = $('#total_employee').val()
            // let liable_person_and_position = $('#liable_person_and_position').val()
            // let owner_name = $('#owner_name').val()
            // let board_of_directors = $('#board_of_directors').val()
            // let major_shareholders = $('#major_shareholders').val()
            // let business_classification = $('#business_classification').val()
            // let website_address = $('#website_address').val()
            // let system_management = $('#system_management').val()
            // let contact_person = $('#contact_person').val()
            // let communication_language = $('#communication_language').val()
            // let email_address = $('#email_address').val()
            // // format array
            // let address = $('input[name="address[]"]').map(function(){
            //     return $(this).val()
            // }).get()
            // // let address = $('#address').val()
            // let city = $('input[name="city[]"]').map(function(){
            //     return $(this).val()
            // }).get()
            // let country = $('input[name="country[]"]').map(function(){
            //     return $(this).val()
            // }).get()
            // let province = $('input[name="province[]"]').map(function(){
            //     return $(this).val()
            // }).get()
            // let zip_code = $('input[name="zip_code[]"]').map(function(){
            //     return $(this).val()
            // }).get()
            // let telephone = $('input[name="telephone[]"]').map(function(){
            //     return $(this).val()
            // }).get()
            // let fax = $('input[name="fax[]"]').map(function(){
            //     return $(this).val()
            // }).get()
    
            // // data 2
            // let bank_name = $('input[name="bank_name[]"]').map(function(){
            //     return $(this).val()
            // }).get()
            // let branch = $('input[name="branch[]"]').map(function(){
            //     return $(this).val()
            // }).get()
            // let account_name = $('input[name="account_name[]"]').map(function(){
            //     return $(this).val()
            // }).get()
            // let city_or_country = $('input[name="city_or_country[]"]').map(function(){
            //     return $(this).val()
            // }).get()
            // let account_number = $('input[name="account_number[]"]').map(function(){
            //     return $(this).val()
            // }).get()
            // let currency = $('input[name="currency[]"]').map(function(){
            //     return $(this).val()
            // }).get()
            // let swift_code = $('input[name="swift_code[]"]').map(function(){
            //     return $(this).val()
            // }).get()
            
            // // data 3
            // let register_number_as_in_tax_invoice = $('#register_number_as_in_tax_invoice').val()
            // let trc_number = $('#trc_number').val()
            // let register_number_related_branch = $('#register_number_related_branch').val()
            // let valid_until = $('#valid_until').val()
            // let taxable_entrepreneur_number = $('#taxable_entrepreneur_number').val()
            // let tax_invoice_serial_number = $('#tax_invoice_serial_number').val()
            
            // // data 4
            // let address_add_info = $('input[name="address_add_info[]"]').map(function(){
            //     return $(this).val()
            // }).get()
            // let city_add_info = $('input[name="city_add_info[]"]').map(function(){
            //     return $(this).val()
            // }).get()
            // let country_add_info = $('input[name="country_add_info[]"]').map(function(){
            //     return $(this).val()
            // }).get()
            // let province_add_info = $('input[name="province_add_info[]"]').map(function(){
            //     return $(this).val()
            // }).get()
            // let zip_code_add_info = $('input[name="zip_code_add_info[]"]').map(function(){
            //     return $(this).val()
            // }).get()
            // let telephone_add_info = $('input[name="telephone_add_info[]"]').map(function(){
            //     return $(this).val()
            // }).get()
            // let fax_add_info = $('input[name="fax_add_info[]"]').map(function(){
            //     return $(this).val()
            // }).get()

            let data_form_company = new FormData($('#form_company')[0])
            $.ajax({
                url: '{{ route("submit-partner") }}',
                // type: 'POST',
                method: 'POST',
                processData: false,
                contentType: false,
                cache: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: data_form_company,
                // dataType: 'json',
                // async: true,
                enctype: 'multipart/form-data',
            })
        })
    })



</script>