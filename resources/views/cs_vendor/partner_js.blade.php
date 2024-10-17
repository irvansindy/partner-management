<script>
    $('#company_type').select2({
        // dropdownParent: $('#modalCreatePartner'),
        width: '100%'
    })
    $(document).ready(function() {
        // fetchDataPartner()
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
                    // console.log(res);
                    if (res.data != null) {
                        // window.location.href
                        // alert('anda sudah terdaftar')
                    }
                }
            })
        }

        fetchDocSupport()

        function fetchDocSupport() {
            $.ajax({
                url: '{{ route('fetch-doctype') }}',
                type: 'GET',
                dataType: 'json',
                async: true,
                success: function(res) {
                    // alert('success')
                    $('#field_form_create_business_other').empty()
                    $('#data_doc_type_pt').empty()
                    $('#data_doc_type_cv').empty()
                    $('#data_doc_type_ud_or_pd').empty()
                    $('#data_doc_type_perorangan').empty()

                    $.each(res.data, function(i, data) {
                        $('#data_doc_type_pt').append(`
                        <tr>
                            <td>${data.name}</td>
                            <td>
                                <input type="hidden" name="${'doc_name_'+data.name_id_class+'_pt'}" id="${'doc_name_'+data.name_id_class+'_pt'}" class="form-control ${'doc_name_'+data.name_id_class+'_pt'}" value="${data.name}" />
                                <input type="file" name="${data.name_id_class+'_pt'}" id="${data.name_id_class+'_pt'}" class="form-control ${data.name_id_class+'_pt'}" />
                            </td>
                        </tr>`)

                        $('#data_doc_type_cv').append(`
                        <tr>
                            <td>${data.name}</td>
                            <td>
                                <input type="hidden" name="${'doc_name_'+data.name_id_class+'_cv'}" id="${'doc_name_'+data.name_id_class+'_cv'}" class="form-control ${'doc_name_'+data.name_id_class+'_cv'}" value="${data.name}" />
                                <input type="file" name="${data.name_id_class+'_cv'}" id="${data.name_id_class+'_cv'}" class="form-control ${data.name_id_class+'_cv'}" />
                            </td>
                        </tr>`)

                        $('#data_doc_type_ud_or_pd').append(`
                        <tr>
                            <td>${data.name}</td>
                            <td>
                                <input type="hidden" name="${'doc_name_'+data.name_id_class+'_ud_or_pd'}" id="${'doc_name_'+data.name_id_class+'_ud_or_pd'}" class="form-control ${'doc_name_'+data.name_id_class+'_ud_or_pd'}" value="${data.name}" />
                                <input type="file" name="${data.name_id_class+'_ud_or_pd'}" id="${data.name_id_class+'_ud_or_pd'}" class="form-control ${data.name_id_class+'_ud_or_pd'}" />
                            </td>
                        </tr>`)

                        $('#data_doc_type_perorangan').append(`
                        <tr>
                            <td>${data.name}</td>
                            <td>
                                <input type="hidden" name="${'doc_name_'+data.name_id_class+'_perorangan'}" id="${'doc_name_'+data.name_id_class+'_perorangan'}" class="form-control ${'doc_name_'+data.name_id_class+'_perorangan'}" value="${data.name}" />
                                <input type="file" name="${data.name_id_class+'_perorangan'}" id="${data.name_id_class+'_perorangan'}" class="form-control ${data.name_id_class+'_perorangan'}" />
                            </td>
                        </tr>`)
                    })
                }
            })
        }

        $(document).on('click', '#create_partner', function(e) {
            e.preventDefault()
            $.ajax({
                url: '{{ route('fetch-doctype') }}',
                type: 'GET',
                dataType: 'json',
                async: true,
                success: function(res) {
                    // alert('success')
                    $('#field_form_create_business_other').empty()
                    $('#data_doc_type_pt').empty()
                    $.each(res.data, function(i, data) {
                        $('#data_doc_type_pt').append(`
                        <tr>
                            <td>${data.name}</td>
                            <td>
                                <input type="hidden" name="${'doc_name_'+data.name_id_class+'_pt'}" id="${'doc_name_'+data.name_id_class+'_pt'}" class="form-control ${'doc_name_'+data.name_id_class+'_pt'}" value="${data.name}" />
                                <input type="file" name="${data.name_id_class+'_pt'}" id="${data.name_id_class+'_pt'}" class="form-control ${data.name_id_class+'_pt'}" />
                            </td>
                        </tr>`)
                    })

                    $('#data_doc_type_cv').empty()
                    $.each(res.data, function(i, data) {
                        $('#data_doc_type_cv').append(`
                        <tr>
                            <td>${data.name}</td>
                            <td>
                                <input type="hidden" name="${'doc_name_'+data.name_id_class+'_cv'}" id="${'doc_name_'+data.name_id_class+'_cv'}" class="form-control ${'doc_name_'+data.name_id_class+'_cv'}" value="${data.name}" />
                                <input type="file" name="${data.name_id_class+'_cv'}" id="${data.name_id_class+'_cv'}" class="form-control ${data.name_id_class+'_cv'}" />
                            </td>
                        </tr>`)
                    })

                    $('#document_type_ud_or_pd').empty()
                    $.each(res.data, function(i, data) {
                        $('#document_type_ud_or_pd').append(`
                        <tr>
                            <td>${data.name}</td>
                            <td>
                                <input type="hidden" name="${'doc_name_'+data.name_id_class+'_ud_or_pd'}" id="${'doc_name_'+data.name_id_class+'_ud_or_pd'}" class="form-control ${'doc_name_'+data.name_id_class+'_ud_or_pd'}" value="${data.name}" />
                                <input type="file" name="${data.name_id_class+'_ud_or_pd'}" id="${data.name_id_class+'_ud_or_pd'}" class="form-control ${data.name_id_class+'_ud_or_pd'}" />
                            </td>
                        </tr>`)
                    })

                    $('#data_doc_type_perorangan').empty()
                    $.each(res.data, function(i, data) {
                        $('#data_doc_type_perorangan').append(`
                        <tr>
                            <td>${data.name}</td>
                            <td>
                                <input type="hidden" name="${'doc_name_'+data.name_id_class+'_perorangan'}" id="${'doc_name_'+data.name_id_class+'_perorangan'}" class="form-control ${'doc_name_'+data.name_id_class+'_perorangan'}" value="${data.name}" />
                                <input type="file" name="${data.name_id_class+'_perorangan'}" id="${data.name_id_class+'_perorangan'}" class="form-control ${data.name_id_class+'_perorangan'}" />
                            </td>
                        </tr>`)
                    })
                }
            })
        })

        $(document).on('change', 'input[name="business_classification"]', function() {
            let value = $(this).val()
            // alert(value)
            if (value == 'Other') {
                $('#field_form_create_business_other').append(`
                    <input type="text" name="business_classification_other_detail" id="business_classification_other_detail" placeholder="Other" class="form-control">
                `)
            } else {
                $('#field_form_create_business_other').empty()
            }
        })

        $(document).on('click', '#add_dynamic_address', function(e) {
            e.preventDefault()
            let message_address = document.querySelectorAll('.message_address');
            let message_city = document.querySelectorAll('.message_city');
            let message_country = document.querySelectorAll('.message_country');
            let message_province = document.querySelectorAll('.message_province');
            let message_zip_code = document.querySelectorAll('.message_zip_code');
            let message_telephone = document.querySelectorAll('.message_telephone');
            let message_fax = document.querySelectorAll('.message_fax');

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
                                <span class="text-danger mt-2 message_address" id="message_address_${message_address.length -1 + 1}" role="alert"> alert    </span>
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
                                        <span class="text-danger mt-2 message_city" id="message_city_${message_city.length -1 + 1}" role="alert"></span>
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
                                        <span class="text-danger mt-2 message_country" id="message_country_${message_country.length -1 + 1}"
                                            role="alert"></span>
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
                                        <span class="text-danger mt-2 message_province" id="message_province_${message_province.length -1 + 1}"
                                            role="alert"></span>
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
                                        <span class="text-danger mt-2 message_zip_code" id="message_zip_code_${message_zip_code.length -1 + 1}"
                                            role="alert"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-auto mb-4">
                                <div class="row">
                                    <div class="col-md-auto">
                                        <label for="telephone">Telephone *</label>
                                        <p class="fs-6" style="margin-bottom: 0.5rem !important; font-size: 10px !important;">+ [Country-Area
                                            Code] [No.]
                                            Telepon +[Negara-Area] [No.]</p>
                                    </div>
                                    <div class="col-md-auto">
                                        <input type="text" name="telephone[]" id="telephone" placeholder="" class="form-control">
                                        <span class="text-danger mt-2 message_telephone" id="message_telephone_${message_telephone.length -1 + 1}"
                                            role="alert"></span>

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-auto mb-4">
                                <div class="row">
                                    <div class="col-md-auto">
                                        <label for="fax">Fax *</label>
                                        <p class="fs-6" style="margin-bottom: 0.5rem !important; font-size: 10px !important;">+ [Country-Area
                                            Code] [No.]
                                            Fax +[Negara-Area] [No.]</p>
                                    </div>
                                    <div class="col-md-auto">
                                        <input type="text" name="fax[]" id="fax" placeholder="" class="form-control">
                                        <span class="text-danger mt-2 message_fax" id="message_fax_${message_fax.length -1 + 1}" role="alert"></span>
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
            updateFieldIds()
            
        })

        $(document).on('click', '#delete_dynamic_address', function(e) {
            if ($('.array_company_address').length != 0) {
                $(this).closest('.array_company_address').remove();
                updateFieldIds();
            } else {
                alert("You must have at least one address!");
            }
        })

        function updateFieldIds() {
            $('.array_company_address').each(function (index) {
                // Update IDs for input elements
                $(this).find('input[name="address[]"]').attr('id', 'address_' + (index + 1));
                $(this).find('input[name="city[]"]').attr('id', 'city_' + (index + 1));
                $(this).find('input[name="country[]"]').attr('id', 'country_' + (index + 1));
                $(this).find('input[name="province[]"]').attr('id', 'province_' + (index + 1));
                $(this).find('input[name="zip_code[]"]').attr('id', 'zip_code_' + (index + 1));
                $(this).find('input[name="telephone[]"]').attr('id', 'telephone_' + (index + 1));
                $(this).find('input[name="fax[]"]').attr('id', 'fax_' + (index + 1));
                
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

        $(document).on('click', '#add_bank', function(e) {
            e.preventDefault()
            alert('di partner js')
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
            // alert('save')
            let data_form_company = new FormData($('#form_company')[0])
            $.ajax({
                url: '{{ route('submit-partner') }}',
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
                beforeSend: function() {
                    setTimeout(function() {
                        $('#modalLoading').modal({
                            show: false
                        });
                    }, 5000)
                },

                success: function(res) {
                    // $('#role_table').DataTable().ajax.reload();
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

                    if (response_error.meta.code == 500 || 400) {
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
                    $('.text-danger').text('')
                    $.each(response_error.meta.message.errors, function(i, value) {
                        $('#message_' + i.replace('.', '_')).text(value)

                        // $(document).Toasts('create', {
                        //     title: 'Error',
                        //     class: 'bg-danger',
                        //     body: value,
                        //     delay: 10000,
                        //     autohide: true,
                        //     fade: true,
                        //     close: true,
                        //     autoremove: true,
                        // });
                    })
                },
            })
        })
    })
</script>
