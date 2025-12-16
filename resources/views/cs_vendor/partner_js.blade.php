<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $('#company_type').select2({
        width: '100%'
    })
    $('#term_of_payment').select2({
        width: '100%'
    })

    $('#established_year, #total_employee').on('input', function() {
        // hapus semua karakter non-digit
        this.value = this.value.replace(/\D/g, '');
    });
    fetchProvinces()

    function fetchProvinces() {
        $.ajax({
            url: "{{ route('fetch-provinces') }}",
            dataType: 'json',
            success: function(data) {
                var $provinceSelect = $('#select_option_province_0');
                $provinceSelect.empty(); // Clear previous options
                $provinceSelect.append('<option></option>'); // Add placeholder option
                data.forEach(function(province) {
                    $provinceSelect.append(new Option(province.name, province.id));
                });
                $provinceSelect.prop('disabled', false); // Enable the province select
                $provinceSelect.select2({
                    placeholder: 'Select Province',
                    width: '100%'
                });
                var $provinceSelect2 = $('#select_option_province_1');
                $provinceSelect2.empty(); // Clear previous options
                $provinceSelect2.append('<option></option>'); // Add placeholder option
                data.forEach(function(province) {
                    $provinceSelect2.append(new Option(province.name, province.id));
                });
                $provinceSelect2.prop('disabled', false); // Enable the province select
                $provinceSelect2.select2({
                    placeholder: 'Select Province',
                    width: '100%'
                });
            }
        });
        $('#select_option_regency_0').prop('disabled', true); // Enable the regency select
        $('#select_option_regency_1').prop('disabled', true); // Enable the regency select
    }

    $('#select_option_province_0').on('change', function() {
        var provinceId = $(this).val();
        // Fetch regencies based on selected province
        $.ajax({
            url: "{{ route('fetch-regencies') }}", // Replace with your API endpoint to fetch regencies
            data: {
                province_id: provinceId
            },
            dataType: 'json',
            success: function(data) {
                var $regencySelect = $('#select_option_regency_0');
                $regencySelect.empty(); // Clear previous options
                $regencySelect.append('<option></option>'); // Add placeholder option
                data.forEach(function(regency) {
                    $regencySelect.append(new Option(regency.name, regency.id));
                });
                $regencySelect.prop('disabled', false); // Enable the regency select
                $regencySelect.select2({
                    placeholder: 'Select Regency',
                    width: '100%'
                });
            }
        });
    });

    $('#select_option_province_1').on('change', function() {
        var provinceId = $(this).val();
        // Fetch regencies based on selected province
        $.ajax({
            url: "{{ route('fetch-regencies') }}", // Replace with your API endpoint to fetch regencies
            data: {
                province_id: provinceId
            },
            dataType: 'json',
            success: function(data) {
                var $regencySelect = $('#select_option_regency_1');
                $regencySelect.empty(); // Clear previous options
                $regencySelect.append('<option></option>'); // Add placeholder option
                data.forEach(function(regency) {
                    $regencySelect.append(new Option(regency.name, regency.id));
                });
                $regencySelect.prop('disabled', false); // Enable the regency select
                $regencySelect.select2({
                    placeholder: 'Select Regency',
                    width: '100%'
                });
            }
        });
    });

    $(document).ready(function() {
        var currentYear = new Date().getFullYear();
        $("#year-current").text(currentYear);
        $("#year_prev_1, #bs_year_minus_1, #fr_year_minus_1").text(currentYear - 1);
        $("#year_prev_2, #bs_year_minus_2, #fr_year_minus_2").text(currentYear - 2);

        $("#input-multiple-file").fileinput({
            uploadUrl: false,
            showUpload: false,
            showRemove: true,
            showCancel: false,
            showClose: false,
            required: false,
            validateInitialCount: true,
            overwriteInitial: false,
            initialPreviewAsData: true,
            dropZoneEnabled: true,
            allowedFileExtensions: ["jpg", "png", "jpeg", "webp", "pdf"],
            ajaxSettings: {
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                processData: false,
                contentType: false,
                method: 'POST',
                enctype: 'multipart/form-data',
            },
            fileActionSettings: {
                showDrag: true, // Display the drag handle
                // showZoom: true,
                showUpload: false, // Tambahkan ini juga di file action settings
                showRemove: true
            },
            // Jika masih muncul, paksa hide dengan CSS
            layoutTemplates: {
                actionUpload: '' // Kosongkan template upload button
            }
        });

        // Paksa hide upload button
        $('.fileinput-upload, .fileinput-upload-button, .kv-file-upload').hide();

        let aYearAgo = currentYear - 1;
        let twoYearAgo = currentYear - 2;

        function updateFieldIdsFinance(prefix) {
            $('input[id^="' + prefix + '"]').each(function(index) {
                $(this).attr('id', prefix + [aYearAgo, twoYearAgo][index]);
                $(this).attr('name', prefix + [aYearAgo, twoYearAgo][index]);
            });
        }

        // Handler untuk select yang sudah ada (index 0)
        $('#liable_position_0').on('change', function() {
            toggleOtherPosition(0);
        });
        // Fungsi untuk show/hide input "Other Position"
        function toggleOtherPosition(index) {
            let selectedValue = $('#liable_position_' + index).val();
            if (selectedValue === 'Other') {
                $('#other_position_container_' + index).show();
                $('#other_position_' + index).prop('required', true);
            } else {
                $('#other_position_container_' + index).hide();
                $('#other_position_' + index).prop('required', false);
                $('#other_position_' + index).val('');
            }
        }


        $(document).on('click', '#add_liable_person', function() {
            let index = $('input[name="liable_person[]"]').length;
            let newField = `
                <div class="array_dynamic_liable_person">
                    <div class="row mb-4" id="liable_person_group_${index}">
                        <div class="col-md-4 col-lg-4 col-sm-12 mb-2">
                            <label for="liable_person_${index}">@lang('messages.Liable Person')</label>
                            <input type="text" name="liable_person[]" id="liable_person_${index}" class="form-control" placeholder="@lang('messages.Placeholder Liable Person')">
                            <span class="text-danger message-danger" id="message_liable_person_${index}" role="alert"></span>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-12 mb-2">
                            <label for="liable_position_${index}">@lang('messages.Liable Position')</label>
                            <select name="liable_position[]" id="liable_position_${index}" class="form-control liable-position-select">
                                <option value="">-- @lang('messages.Placeholder Position') --</option>
                                <option value="Owner">@lang('messages.Owner')</option>
                                <option value="Board of Directors">@lang('messages.Board of Directors')</option>
                                <option value="Shareholders">@lang('messages.Shareholders')</option>
                                <option value="Finance Department">@lang('messages.Finance Department')</option>
                                <option value="Purchase/Procure Department">@lang('messages.Purchase/Procure Department')</option>
                                <option value="Sales Department">@lang('messages.Sales Department')</option>
                                <option value="Other">@lang('messages.Other')</option>
                            </select>
                            <span class="text-danger message-danger" id="message_liable_position_${index}" role="alert"></span>
                            <div class="mt-2" id="other_position_container_${index}" style="display: none;">
                                <input type="text" name="other_position[]" id="other_position_${index}" class="form-control" placeholder="@lang('messages.Specify Position')">
                                <span class="text-danger message-danger" id="message_other_position_${index}" role="alert"></span>
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-12 mb-2">
                            <label for="nik_${index}">NIK</label>
                            <input type="text" name="nik[]" id="nik_${index}" class="form-control" placeholder="@lang('messages.Placeholder NIK')">
                            <span class="text-danger message-danger" id="message_nik_${index}" role="alert"></span>
                        </div>
                    </div>
                    <div class="input-group d-flex justify-content-end mr-4 mb-4 mt-4">
                        <button type="button" class="btn btn-danger remove_liable_person" id="remove_liable_person_${index}">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
            `;
            $('.dynamic_liable_person').append(newField);
            $('#liable_position_' + index).select2({
                width: '100%'
            });
        });

        // Remove liable person
        $(document).on('click', '.remove_liable_person', function() {
            $(this).closest('.array_dynamic_liable_person').remove();
        });

        // Handler untuk semua select (termasuk yang dinamis)
        $(document).on('change', '.liable-position-select', function() {
            let id = $(this).attr('id');
            let index = id.replace('liable_position_', '');
            toggleOtherPosition(index);
        });

        $(document).on('click', '#add_survey_data', function() {
            let index = $('input[name="product_survey[]"]').length;
            let newField = `
                <div class="array_dynamic_survey">
                    <div class="row mt-4">
                        <div class="col-md-3 col-lg-3 col-sm-12 mb-3">
                            <label for="product_survey_${index}">@lang('messages.Product')</label>
                            <input type="text" name="product_survey[]" id="product_survey_${index}" class="form-control" placeholder="@lang('messages.Placeholder Survey Product')">
                            <span class="text-danger mt-2" id="message_product_survey_${index}" role="alert"></span>
                        </div>
                        <div class="col-md-3 col-lg-3 col-sm-12 mb-3">
                            <label for="merk_survey_${index}">@lang('messages.Merk')</label>
                            <input type="text" name="merk_survey[]" id="merk_survey_${index}" class="form-control" placeholder="@lang('messages.Placeholder Survey Merk')">
                            <span class="text-danger mt-2" id="message_merk_survey_${index}" role="alert"></span>
                        </div>
                        <div class="col-md-3 col-lg-3 col-sm-12 mb-3">
                            <label for="distributor_survey_${index}">@lang('messages.Distributor')</label>
                            <input type="text" name="distributor_survey[]" id="distributor_survey_${index}" class="form-control" placeholder="@lang('messages.Placeholder Survey Distributor')">
                            <span class="text-danger mt-2" id="message_distributor_survey_${index}" role="alert"></span>
                        </div>

                        <div class="col-md-3 col-lg-3 col-sm-12 mb-3">
                            <div class="input-group d-flex justify-content-end mb-4 mt-4">
                                <button type="button" class="btn btn-danger remove_survey_data" id="remove_survey_data_${index}">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                `;
            $('.dynamic_product_survey').append(newField);
        });

        $(document).on('click', '.remove_survey_data', function() {
            $(this).closest('.array_dynamic_survey').remove();
        });

        $(document).on('change', '#term_of_payment', function() {
            let selectedValue = $('#term_of_payment').val();
            if (selectedValue == 'Other') {
                $('#other_term_of_payment_container').empty();
                $('#other_term_of_payment_container').append(`
                    <input type="text" name="other_term_of_payment" id="other_term_of_payment" class="form-control" placeholder="@lang('messages.Placeholder TOP Other')">
                    <span class="text-danger message-danger" id="message_other_term_of_payment" role="alert"></span>
                `);
                $('#other_term_of_payment').prop('required', true);
            } else {
                $('#other_term_of_payment_container').empty();
                $('#other_term_of_payment').prop('required', false);
                $('#other_term_of_payment').val('');
            }
        });

        $(document).on('click', '#create_partner', function(e) {
            e.preventDefault()
            $.ajax({
                url: '{{ route('fetch-doctype') }}',
                type: 'GET',
                dataType: 'json',
                async: true,
                success: function(res) {
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

        $(document).on('change', 'select[name="business_classification"]', function() {
            let value = $(this).val()
            if (value == 'Other') {
                $('#field_form_create_business_other').append(`
                    <input type="text" name="business_classification_other_detail" id="business_classification_other_detail" placeholder="Other" class="form-control" style="width: 50% !important;">
                `)
            } else {
                $('#field_form_create_business_other').empty()
            }
        })

        $(document).on('click', '#add_contact', function(e) {
            e.preventDefault()
            $('.dynamic_contact').append(`
                <div class="array_dynamic_contact">
                    <div class="row mt-4">
                        <div class="col-md-auto col-lg-auto col-sm-12 mb-3">
                            <label for="contact_department_0">@lang('messages.Department')</label>
                            <input type="text" name="contact_department[]" id="contact_department_0" class="form-control" placeholder="@lang('messages.Placeholder Contact Department')">
                            <span class="text-danger mt-2" id="message_contact_department" role="alert"></span>
                        </div>
                        <div class="col-md-auto col-lg-auto col-sm-12 mb-3">
                            <label for="contact_position_0">@lang('messages.Position')</label>
                            <input type="text" name="contact_position[]" id="contact_position_0" class="form-control" placeholder="@lang('messages.Placeholder Contact Position')">
                            <span class="text-danger mt-2" id="message_contact_position" role="alert"></span>
                        </div>
                        <div class="col-md-auto col-lg-auto col-sm-12 mb-3">
                            <label for="contact_name_0">@lang('messages.Name')</label>
                            <input type="text" name="contact_name[]" id="contact_name_0" class="form-control" placeholder="@lang('messages.Placeholder Contact Name')">
                            <span class="text-danger mt-2" id="message_contact_name" role="alert"></span>
                        </div>
                        <div class="col-md-auto col-lg-auto col-sm-12 mb-3">
                            <label for="contact_email_0">@lang('messages.Email')</label>
                            <input type="text" name="contact_email[]" id="contact_email_0" class="form-control" placeholder="@lang('messages.Placeholder Contact Email')">
                            <span class="text-danger mt-2" id="message_contact_email" role="alert"></span>
                        </div>
                        <div class="col-md-auto col-lg-auto col-sm-12 mb-3">
                            <label for="contact_telephone_0">@lang('messages.Telephone')</label>
                            <input type="text" name="contact_telephone[]" id="contact_telephone_0" class="form-control" placeholder="@lang('messages.Placeholder Contact Phone')">
                            <span class="text-danger mt-2" id="message_contact_telephone" role="alert"></span>
                        </div>
                        <div class="col-md-auto col-lg-auto col-sm-12 mb-3">
                            <div class="input-group d-flex justify-content-end mb-4 mt-4">
                                <button class="btn btn-danger delete_contact"><i class="fa fa-minus"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                `)
        })

        $(document).on('click', '.delete_contact', function(e) {
            e.preventDefault()
            $(this).closest('.array_dynamic_contact').remove()
        })

        // ============================================
        // PROVINCE & REGENCY FUNCTIONS
        // ============================================

        // Fungsi untuk load provinces ke select tertentu
        function loadProvincesToSelect(selectId) {
            $.ajax({
                url: "{{ route('fetch-provinces') }}",
                dataType: 'json',
                success: function(data) {
                    var $provinceSelect = $('#' + selectId);
                    $provinceSelect.empty();
                    $provinceSelect.append('<option></option>');
                    data.forEach(function(province) {
                        $provinceSelect.append(new Option(province.name, province.id));
                    });
                    $provinceSelect.prop('disabled', false);
                    $provinceSelect.select2({
                        placeholder: 'Select Province',
                        width: '100%'
                    });
                }
            });
        }

        // Fungsi untuk load regencies berdasarkan province
        function loadRegenciesToSelect(provinceId, regencySelectId) {
            $.ajax({
                url: "{{ route('fetch-regencies') }}",
                data: {
                    province_id: provinceId
                },
                dataType: 'json',
                success: function(data) {
                    var $regencySelect = $('#' + regencySelectId);
                    $regencySelect.empty();
                    $regencySelect.append('<option></option>');
                    data.forEach(function(regency) {
                        $regencySelect.append(new Option(regency.name, regency.id));
                    });
                    $regencySelect.prop('disabled', false);
                    $regencySelect.select2({
                        placeholder: 'Select Regency',
                        width: '100%'
                    });
                }
            });
        }

        // ============================================
        // INITIALIZE EXISTING ADDRESS FORMS
        // ============================================

        $(document).ready(function() {
            // Load provinces untuk address 0 dan 1
            loadProvincesToSelect('select_option_province_0');
            loadProvincesToSelect('select_option_province_1');

            // Disable regency selects initially
            $('#select_option_regency_0').prop('disabled', true);
            $('#select_option_regency_1').prop('disabled', true);
        });

        // ============================================
        // EVENT HANDLER UNTUK PROVINCE CHANGE (DELEGATED)
        // ============================================

        // Gunakan delegated event untuk handle semua province select (existing & dynamic)
        $(document).on('change', '[id^="select_option_province_"]', function() {
            var provinceId = $(this).val();
            var index = $(this).attr('id').replace('select_option_province_', '');
            var regencySelectId = 'select_option_regency_' + index;

            if (provinceId) {
                loadRegenciesToSelect(provinceId, regencySelectId);
            } else {
                $('#' + regencySelectId).empty().append('<option></option>').prop('disabled', true);
            }
        });

        // ============================================
        // ADD DYNAMIC ADDRESS
        // ============================================

        $(document).on('click', '#add_dynamic_address', function(e) {
            e.preventDefault();

            // Hitung index berdasarkan jumlah fieldset yang ada
            let index = $('.company_address_additional fieldset').length;

            $('.dynamic_company_address').append(`
        <div class="array_company_address">
            <fieldset class="border px-2 mb-4">
                <legend class="float-none w-auto text-bold">@lang('messages.Address Data')</legend>
                <div class="row">
                    <div class="input-group mb-4">
                        <div class="col-md-3">
                            <label>@lang('messages.Company Address (Other)')</label>
                        </div>
                        <div class="col-md-9">
                            <input type="text" name="address[]" id="address_${index}" class="form-control">
                            <span class="text-danger mt-2 message_address" id="message_address_${index}" role="alert"></span>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="country_${index}">@lang('messages.Country')</label>
                            <input type="text" name="country[]" id="country_${index}" class="form-control" value="Indonesia" readonly>
                            <span class="text-danger mt-2 message_country" id="message_country_${index}" role="alert"></span>
                        </div>
                        <div class="col-md-6">
                            <label for="province_${index}">@lang('messages.Province')</label>
                            <select name="province[]" id="select_option_province_${index}" class="form-control"></select>
                            <span class="text-danger mt-2 message_province" id="message_province_${index}" role="alert"></span>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="city_${index}">@lang('messages.City')</label>
                            <select name="city[]" id="select_option_regency_${index}" class="form-control"></select>
                            <span class="text-danger mt-2 message_city" id="message_city_${index}" role="alert"></span>
                        </div>
                        <div class="col-md-6">
                            <label for="zip_code_${index}">@lang('messages.Postal Code')</label>
                            <input type="text" name="zip_code[]" id="zip_code_${index}" class="form-control" placeholder="@lang('messages.Placeholder Address Postal Code')">
                            <span class="text-danger mt-2 message_zip_code" id="message_zip_code_${index}" role="alert"></span>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="telephone_${index}">@lang('messages.Telephone')</label>
                            <p class="fs-6 text-muted mb-2">@lang('messages.Telephone Info')</p>
                            <input type="number" name="telephone[]" id="telephone_${index}" class="form-control" placeholder="@lang('messages.Placeholder Address Telephone')">
                            <span class="text-danger mt-2 message_telephone" id="message_telephone_${index}" role="alert"></span>
                        </div>
                        <div class="col-md-6">
                            <label for="fax_${index}">@lang('messages.Fax')</label>
                            <p class="fs-6 text-muted mb-2">@lang('messages.Fax Info')</p>
                            <input type="number" name="fax[]" id="fax_${index}" class="form-control" placeholder="@lang('messages.Placeholder Address Fax')">
                            <span class="text-danger mt-2 message_fax" id="message_fax_${index}" role="alert"></span>
                        </div>
                    </div>
                </div>
            </fieldset>

            <div class="input-group d-flex justify-content-end mr-4 mb-4">
                <button type="button" class="btn btn-danger delete_dynamic_address">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
    `);

            // Load provinces untuk select yang baru ditambahkan
            loadProvincesToSelect('select_option_province_' + index);

            // Disable regency select initially
            $('#select_option_regency_' + index).prop('disabled', true).select2({
                placeholder: 'Select Regency',
                width: '100%'
            });
        });

        // ============================================
        // DELETE DYNAMIC ADDRESS
        // ============================================

        $(document).on('click', '.delete_dynamic_address', function(e) {
            e.preventDefault();
            $(this).closest('.array_company_address').remove();
        });

        function updateFieldIds() {
            $('.array_company_address').each(function(index) {
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
            $('.dynamic_bank').append(`
                <div class="array_dymanic_bank">
                    <fieldset class="border px-2 mb-4">
                        <legend class="float-none w-auto text-bold">Data Bank</legend>
                        <div class="row mt-4">
                            <div class="col-md-4 col-lg-4 col-sm-12 mb-3">
                                <label for="bank_name_0">@lang('messages.Bank Name')</label>
                                <input type="text" name="bank_name[]" id="bank_name_0" class="form-control">
                                <span class="text-danger mt-2" id="message_bank_name" role="alert"></span>
                            </div>
                            <div class="col-md-4 col-lg-4 col-sm-12 mb-3">
                                <label for="account_name_0">@lang('messages.Account Name')</label>
                                <input type="text" name="account_name[]" id="account_name_0" class="form-control">
                                <span class="text-danger mt-2" id="message_account_name" role="alert"></span>
                            </div>
                            <div class="col-md-4 col-lg-4 col-sm-12 mb-3">
                                <label for="account_number_0">@lang('messages.Account Number')</label>
                                <input type="number" name="account_number[]" id="account_number_0" class="form-control">
                                <span class="text-danger mt-2" id="message_account_number" role="alert"></span>
                            </div>
                        </div>
                    </fieldset>
                    <div class="input-group d-flex justify-content-end mb-4 mt-4">
                        <button type="button" class="btn btn-danger" id="delete_bank">
                            <i class="fas fa-minus"></i>
                        </button>
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
        $(document).on('click', '#confirm_currency', function(e) {
            e.preventDefault()
            let currency_data = document.querySelectorAll(".currency_data");
            let all_filled = true

            // Cek apakah ada input yang kosong.
            currency_data.forEach(input => {
                if (input.value.trim() == "") {
                    all_filled = false;
                }
            });

            if (!all_filled) {
                $(document).Toasts('create', {
                    title: 'Error',
                    class: 'bg-danger',
                    body: 'Harap isi data INCOME STATEMENT dan BALANCE SHEET!.',
                    delay: 10000,
                    autohide: true,
                    fade: true,
                    close: true,
                    autoremove: true,
                });
                currency_data.forEach(input => {
                    input.readOnly = false;
                });
                $('#total_financial_ratio').empty()

                $('#action_button_confirm_currency').empty()
                $('#action_button_confirm_currency').html(`
                    <button type="button" class="btn btn-success" id="confirm_currency">Confirm</button>
                `)
            } else {
                // Jika semua terisi, buat readonly
                currency_data.forEach(input => {
                    input.readOnly = all_filled;
                });

                // $.ajax({
                //     header: {
                //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                //     },
                //     url: "{{ route('result-financial-ratio') }}",
                //     type: 'POST',
                //     dataType: 'json',
                //     data: {
                //         revenue: $('#revenue_').val(),
                //         net_profit: $('#net_profit_').val(),
                //         current_asset: $('#current_asset_').val(),
                //         total_asset: $('#total_asset_').val(),
                //         current_liabilities: $('#current_liabilities_').val(),
                //         ebit: $('#ebit_').val(),
                //         depreciation_expense: $('#depreciation_expense_').val(),
                //         total_current_liabilities: $('#total_current_liabilities_').val(),
                //         interest_expense: $('#interest_expense_').val(),
                //         acc_receivables: $('#acc_receivables_').val()
                //     },

                // })

                $('#total_financial_ratio').empty()
                $('#total_financial_ratio').append(`
                    <div class="card py-2">
                        <strong><h4>FINANCIAL RATIO</h4></strong>
                        <table class="table table-bordered mt-3">
                            <thead class="table-light">
                                <tr>
                                    <th>Ratio <strong>(%)</strong></th>
                                    <th id="fr_year_minus_1"></th>
                                    <th id="fr_year_minus_2"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Working Capital Ratio</td>
                                    <td><input type="number" step="0.01" class="form-control percent"
                                            id="wc_ratio_" name="wc_ratio_"></td>
                                    <td><input type="number" step="0.01" class="form-control percent"
                                            id="wc_ratio_" name="wc_ratio_"></td>
                                </tr>
                                <tr>
                                    <td>Cash Flow Coverage Ratio</td>
                                    <td><input type="number" step="0.01" class="form-control percent"
                                            id="cf_coverage_" name="cf_coverage_"></td>
                                    <td><input type="number" step="0.01" class="form-control percent"
                                            id="cf_coverage_" name="cf_coverage_"></td>
                                </tr>
                                <tr>
                                    <td>Time Interest Earned Ratio</td>
                                    <td><input type="number" step="0.01" class="form-control percent"
                                            id="tie_ratio_" name="tie_ratio_"></td>
                                    <td><input type="number" step="0.01" class="form-control percent"
                                            id="tie_ratio_" name="tie_ratio_"></td>
                                </tr>
                                <tr>
                                    <td>Debt to Asset Ratio</td>
                                    <td><input type="number" step="0.01" class="form-control percent"
                                            id="debt_asset_" name="debt_asset_"></td>
                                    <td><input type="number" step="0.01" class="form-control percent"
                                            id="debt_asset_" name="debt_asset_"></td>
                                </tr>
                                <tr>
                                    <td>Debt to Asset Ratio</td>
                                    <td><input type="number" step="0.01" class="form-control percent"
                                            id="account_receivable_turn_over_" name="account_receivable_turn_over_"></td>
                                    <td><input type="number" step="0.01" class="form-control percent"
                                            id="account_receivable_turn_over_" name="account_receivable_turn_over_"></td>
                                </tr>
                                <tr>
                                    <td>Net Profit Margin</td>
                                    <td><input type="number" step="0.01" class="form-control percent"
                                            id="net_profit_margin_" name="net_profit_margin_"></td>
                                    <td><input type="number" step="0.01" class="form-control percent"
                                            id="net_profit_margin_" name="net_profit_margin_"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                `)
                $('#total_financial_ratio').show()
                $('.percent').attr('readonly', true)

                updateFieldIdsFinance('wc_ratio_')
                updateFieldIdsFinance('cf_coverage_')
                updateFieldIdsFinance('tie_ratio_')
                updateFieldIdsFinance('debt_asset_')
                updateFieldIdsFinance('account_receivable_turn_over_')
                updateFieldIdsFinance('net_profit_margin_')

                let year_data = [aYearAgo, twoYearAgo]

                for (let index = 0; index < year_data.length; index++) {
                    let year = year_data[index]
                    let revenue = parseFloat(document.getElementById("revenue_" + year).value)
                    let netProfit = parseFloat(document.getElementById("net_profit_" + year).value)
                    let currentAsset = parseFloat(document.getElementById("current_asset_" + year)
                        .value)
                    let totalAsset = parseFloat(document.getElementById("total_asset_" + year).value)
                    let currentLiabilities = parseFloat(document.getElementById("current_liabilities_" +
                        year).value)
                    let ebit = parseFloat(document.getElementById("ebit_" + year).value)
                    let depreciationExprense = parseFloat(document.getElementById(
                        "depreciation_expense_" + year).value)
                    let totalLiabilities = parseFloat(document.getElementById(
                        "total_current_liabilities_" + year).value)
                    let interestExprense = parseFloat(document.getElementById("interest_expense_" +
                        year).value)
                    let accountReceivable = parseFloat(document.getElementById("acc_receivables_" +
                        year).value)

                    let workingCapitalRatio = +(currentAsset / currentLiabilities) * 100;
                    let cashFlowCoverageRatio = +((ebit + depreciationExprense) / totalLiabilities) *
                        100;
                    let timeInterestEarnedRatio = +(ebit / interestExprense) * 100;
                    let debtToAssetRatio = +(totalLiabilities / totalAsset) * 100;
                    let accountReceivableTurnOver = +(revenue / ((accountReceivable +
                        accountReceivable) / 2)) * 100;
                    let netProfitMargin = +(revenue / netProfit) * 100;

                    // Tampilkan hasil di field wc_ratio_
                    $('#wc_ratio_' + year).val(workingCapitalRatio.toFixed(2))
                    $('#cf_coverage_' + year).val(cashFlowCoverageRatio.toFixed(2))
                    $('#tie_ratio_' + year).val(timeInterestEarnedRatio.toFixed(2))
                    $('#debt_asset_' + year).val(debtToAssetRatio.toFixed(2))
                    $('#account_receivable_turn_over_' + year).val(accountReceivableTurnOver.toFixed(2))
                    $('#net_profit_margin_' + year).val(netProfitMargin.toFixed(2))
                }

                $('#action_button_confirm_currency').empty()
                $('#action_button_confirm_currency').html(`
                    <button type="button" class="btn btn-danger" id="reset_currency">Reset</button>
                `)
            }
        })

        $(document).on('click', '#reset_currency', function(e) {
            e.preventDefault()
            let currency_data = document.querySelectorAll(".currency_data");

            // Cek apakah ada input yang kosong
            currency_data.forEach(input => {
                input.readOnly = false;
            });
            $('#total_financial_ratio').empty()
            $('#action_button_confirm_currency').empty()
            $('#action_button_confirm_currency').html(`
                <button type="button" class="btn btn-success" id="confirm_currency">Confirm</button>
            `)
        })

        // ============================================
        // AUTO REFRESH CSRF TOKEN SETIAP 30 MENIT
        // ============================================
        function refreshCsrfToken() {
            $.ajax({
                url: '/refresh-csrf', // Buat route ini di Laravel
                type: 'GET',
                success: function(response) {
                    if (response.token) {
                        // Update meta tag
                        $('meta[name="csrf-token"]').attr('content', response.token);

                        // Update form input
                        $('input[name="_token"]').val(response.token);

                        console.log('✅ CSRF token refreshed:', response.token);
                    }
                },
                error: function() {
                    console.warn('⚠️ Failed to refresh CSRF token');
                }
            });
        }

        // Refresh token setiap 30 menit (1800000 ms)
        setInterval(refreshCsrfToken, 1800000);

        // Refresh saat halaman di-focus kembali (user kembali ke tab)
        $(window).on('focus', function() {
            refreshCsrfToken();
        });

        // ============================================
        // ENHANCED SUBMIT HANDLER
        // ============================================
        $('#btn_submit_data_company').on('click', function(e) {
            e.preventDefault();

            // Clear previous errors
            $('.text-danger').text('');
            $('.is-invalid').removeClass('is-invalid');

            // Ambil CSRF token terbaru
            var csrfToken = $('meta[name="csrf-token"]').attr('content') ||
                $('input[name="_token"]').val();

            if (!csrfToken) {
                Swal.fire({
                    icon: 'error',
                    title: 'Session Error',
                    text: 'CSRF token not found. Please refresh the page.',
                    confirmButtonText: 'Refresh Page'
                }).then(() => {
                    window.location.reload();
                });
                return;
            }

            Swal.fire({
                title: 'Processing...',
                text: 'Please wait while we submit your data',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            // Get form data
            var formData = new FormData($('#form_company')[0]);

            // Force update CSRF token di FormData
            formData.set('_token', csrfToken);

            var formAction = $('#form_company').attr('action');

            console.log('📤 Submitting to:', formAction);
            console.log('🔑 CSRF Token:', csrfToken);

            $.ajax({
                url: formAction,
                type: 'POST',
                data: formData,
                headers: {
                    "X-APP-KEY": $('#public_key').val(),
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                processData: false,
                contentType: false,
                cache: false,

                success: function(response) {
                    Swal.close();

                    if (response.meta.status == 'success') {
                        if (typeof clearSavedForm === 'function') {
                            clearSavedForm();
                        }

                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: response.message || 'Data berhasil disimpan',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            if (response.redirect_url) {
                                window.location.href = response.redirect_url;
                            } else {
                                window.location.reload();
                            }
                        });
                        // Clear localStorage setelah sukses
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: response.message || 'Something went wrong'
                        });
                    }
                },

                error: function(xhr) {
                    Swal.close();

                    console.error('❌ Submit Error:', xhr.status, xhr.statusText);
                    console.error('Response:', xhr.responseJSON);

                    $('.is-invalid').removeClass('is-invalid');
                    $('[id^="message_"]').text('');

                    // Handle 419 - CSRF Token Mismatch
                    if (xhr.status === 419) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Session Expired',
                            html: 'Your session has expired.<br>The page will refresh to restore your session.',
                            confirmButtonText: 'Refresh Page',
                            allowOutsideClick: false
                        }).then(() => {
                            // Simpan current scroll position
                            sessionStorage.setItem('scrollPosition', window
                            .scrollY);
                            window.location.reload();
                        });
                        return;
                    }

                    // Handle 422 - Validation Error
                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON?.errors || {};
                        var errorCount = Object.keys(errors).length;

                        $.each(errors, function(key, value) {
                            var fieldId = key.replace(/\./g, '_');
                            var message = value[0];

                            var inputField = $('#' + fieldId);
                            var messageContainer = $('#message_' + fieldId);

                            if (inputField.length) {
                                inputField.addClass('is-invalid');
                            }
                            if (messageContainer.length) {
                                messageContainer.text(message);
                            }
                        });

                        Swal.fire({
                            icon: 'error',
                            title: 'Validation Error',
                            html: `Found ${errorCount} validation error(s). Please check your input.`,
                        });

                        // Scroll ke error pertama
                        var firstError = $('.is-invalid').first();
                        if (firstError.length) {
                            $('html, body').animate({
                                scrollTop: firstError.offset().top - 150
                            }, 500);
                        }

                        return;
                    }

                    // Handle 500 - Server Error
                    if (xhr.status === 500) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Server Error',
                            text: 'An internal server error occurred. Please contact administrator.',
                        });
                        return;
                    }

                    // Generic error
                    var errorMessage = 'An error occurred';

                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    } else if (xhr.statusText) {
                        errorMessage = xhr.statusText;
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: errorMessage
                    });
                }
            });
        });

        // ============================================
        // RESTORE SCROLL POSITION AFTER REFRESH
        // ============================================
        $(document).ready(function() {
            var scrollPosition = sessionStorage.getItem('scrollPosition');
            if (scrollPosition) {
                window.scrollTo(0, parseInt(scrollPosition));
                sessionStorage.removeItem('scrollPosition');
            }
        });

    })
</script>
