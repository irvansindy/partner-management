<script>
    $('#company_type').select2({
        // dropdownParent: $('#modalCreatePartner'),
        width: '100%'
    })
    
    $(document).ready(function() {
        var currentYear = new Date().getFullYear();
        $("#year-current").text(currentYear);
        $("#year-prev-1, #bs_year_minus_1, #fr_year_minus_1").text(currentYear - 1);
        $("#year-prev-2, #bs_year_minus_2, #fr_year_minus_2").text(currentYear - 2);

        let aYearAgo = currentYear - 1;
        let twoYearAgo = currentYear - 2;

        function updateFieldIdsFinance(prefix) {
            $('input[id^="' + prefix +'"]').each(function(index) {
                $(this).attr('id', prefix + [aYearAgo, twoYearAgo][index]);
                $(this).attr('name', prefix + [aYearAgo, twoYearAgo][index]);
            });
        }
    
        $('#company_type').change(function() {
            let value = $(this).val()
            switch (value) {
                case 'vendor':
                    $('.dynamic-form-income-statement').empty()
                    break;
                case 'customer':
                    $('.dynamic-form-income-statement').empty()
                    $('.dynamic-form-income-statement').html(`
                        <!-- FORM INCOME STATEMENT -->
                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title">
                                    INCOME STATEMENT, BALANCE SHEET, FINANCIAL RATIO
                                </h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row px-2 py-2" style="line-height: 1">
                                    <div class="card py-2">
                                        <strong><h4>INCOME STATEMENT</h4></strong>
                                        <table class="table table-bordered my-2">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>(in IDR)</th>
                                                    <th id="year-prev-1"></th>
                                                    <th id="year-prev-2"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td><strong>Revenue</strong></td>
                                                    <td><input type="text" class="form-control currency_data"
                                                            name="revenue_" id="revenue_" value=""></td>
                                                    <td><input type="text" class="form-control currency_data"
                                                            name="revenue_" id="revenue_" value=""></td>
                                                </tr>
                                                <tr>
                                                    <td>Cost Of Revenue</td>
                                                    <td><input type="text" class="form-control currency_data"
                                                            name="cost_of_revenue_" id="cost_of_revenue_" value="">
                                                    </td>
                                                    <td><input type="text" class="form-control currency_data"
                                                            name="cost_of_revenue_" id="cost_of_revenue_" value="">
                                                    </td>
                                                </tr>
                                                <tr class="table-warning">
                                                    <td><strong>Gross Profit</strong></td>
                                                    <td><input type="text" class="form-control currency_data"
                                                            name="gross_profit_" id="gross_profit_" value=""></td>
                                                    <td><input type="text" class="form-control currency_data"
                                                            name="gross_profit_" id="gross_profit_" value=""></td>
                                                </tr>
                                                <tr>
                                                    <td>Operating Expense</td>
                                                    <td><input type="text" class="form-control currency_data"
                                                            name="opex_" id="opex_" value=""></td>
                                                    <td><input type="text" class="form-control currency_data"
                                                            name="opex_" id="opex_" value=""></td>
                                                </tr>
                                                <tr class="table-success">
                                                    <td><strong>EBIT</strong></td>
                                                    <td><input type="text" class="form-control currency_data"
                                                            name="ebit_" id="ebit_" value=""></td>
                                                    <td><input type="text" class="form-control currency_data"
                                                            name="ebit_" id="ebit_" value=""></td>
                                                </tr>
                                                <tr>
                                                    <td>Interest Expense</td>
                                                    <td><input type="text" class="form-control currency_data"
                                                            name="interest_expense_" id="interest_expense_"
                                                            value=""></td>
                                                    <td><input type="text" class="form-control currency_data"
                                                            name="interest_expense_" id="interest_expense_"
                                                            value=""></td>
                                                </tr>
                                                <tr>
                                                    <td>Others Expense</td>
                                                    <td><input type="text" class="form-control currency_data"
                                                            name="others_expense_" id="others_expense_" value="">
                                                    </td>
                                                    <td><input type="text" class="form-control currency_data"
                                                            name="others_expense_" id="others_expense_" value="">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Others Income</td>
                                                    <td><input type="text" class="form-control currency_data"
                                                            name="others_income_" id="others_income_" value="">
                                                    </td>
                                                    <td><input type="text" class="form-control currency_data"
                                                            name="others_income_" id="others_income_" value="">
                                                    </td>
                                                </tr>
                                                <tr class="table-primary">
                                                    <td><strong>Earnings Before Tax</strong></td>
                                                    <td><input type="text" class="form-control currency_data"
                                                            name="ebt_" id="ebt_" value="">
                                                    </td>
                                                    <td><input type="text" class="form-control currency_data"
                                                            name="ebt_" id="ebt_" value="">
                                                    </td>
                                                </tr>
                                                <tr class="table-danger">
                                                    <td><strong>Net Profit</strong></td>
                                                    <td><input type="text" class="form-control currency_data"
                                                            name="net_profit_" id="net_profit_" value=""></td>
                                                    <td><input type="text" class="form-control currency_data"
                                                            name="net_profit_" id="net_profit_" value=""></td>
                                                </tr>
                                                <tr>
                                                    <td>Depreciation Expense</td>
                                                    <td><input type="text" class="form-control currency_data"
                                                            name="depreciation_expense_" id="depreciation_expense_"
                                                            value=""></td>
                                                    <td><input type="text" class="form-control currency_data"
                                                            name="depreciation_expense_" id="depreciation_expense_"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="card py-2">
                                        <strong><h4>BALANCE SHEET</h4></strong>
                                        <table class="table table-bordered my-2">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>(in IDR)</th>
                                                    <th id="bs_year_minus_1"></th>
                                                    <th id="bs_year_minus_2"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Cash</td>
                                                    <td><input type="text" class="form-control currency_data"
                                                            id="cash_" name="cash_"></td>
                                                    <td><input type="text" class="form-control currency_data"
                                                            id="cash_" name="cash_"></td>
                                                </tr>
                                                <tr>
                                                    <td>Acc Receivables</td>
                                                    <td><input type="text" class="form-control currency_data"
                                                            id="acc_receivables_" name="acc_receivables_"></td>
                                                    <td><input type="text" class="form-control currency_data"
                                                            id="acc_receivables_" name="acc_receivables_"></td>
                                                </tr>
                                                <tr>
                                                    <td>Other Current Asset</td>
                                                    <td><input type="text" class="form-control currency_data"
                                                            id="other_current_asset_" name="other_current_asset_"></td>
                                                    <td><input type="text" class="form-control currency_data"
                                                            id="other_current_asset_" name="other_current_asset_"></td>
                                                </tr>
                                                <tr>
                                                    <td>Current Asset</td>
                                                    <td><input type="text" class="form-control currency_data"
                                                            id="current_asset_" name="current_asset_"></td>
                                                    <td><input type="text" class="form-control currency_data"
                                                            id="current_asset_" name="current_asset_"></td>
                                                </tr>
                                                <tr>
                                                    <td>Fixed Asset</td>
                                                    <td><input type="text" class="form-control currency_data"
                                                            id="fixed_asset_" name="fixed_asset_"></td>
                                                    <td><input type="text" class="form-control currency_data"
                                                            id="fixed_asset_" name="fixed_asset_"></td>
                                                </tr>
                                                <tr>
                                                    <td>Total Asset</td>
                                                    <td><input type="text" class="form-control currency_data"
                                                            id="total_asset_" name="total_asset_"></td>
                                                    <td><input type="text" class="form-control currency_data"
                                                            id="total_asset_" name="total_asset_"></td>
                                                </tr>
                                                <tr>
                                                    <td>Current Liabilities</td>
                                                    <td><input type="text" class="form-control currency_data"
                                                            id="current_liabilities_" name="current_liabilities_"></td>
                                                    <td><input type="text" class="form-control currency_data"
                                                            id="current_liabilities_" name="current_liabilities_"></td>
                                                </tr>
                                                <tr>
                                                    <td>Non Current Liabilities</td>
                                                    <td><input type="text" class="form-control currency_data"
                                                            id="non_current_liabilities_" name="non_current_liabilities_">
                                                    </td>
                                                    <td><input type="text" class="form-control currency_data"
                                                            id="non_current_liabilities_" name="non_current_liabilities_">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Total Current Liabilities</td>
                                                    <td><input type="text" class="form-control currency_data"
                                                            id="total_current_liabilities_" name="total_current_liabilities_">
                                                    </td>
                                                    <td><input type="text" class="form-control currency_data"
                                                            id="total_current_liabilities_" name="total_current_liabilities_">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Equity</td>
                                                    <td><input type="text" class="form-control currency_data"
                                                            id="equity_" name="equity_"></td>
                                                    <td><input type="text" class="form-control currency_data"
                                                            id="equity_" name="equity_"></td>
                                                </tr>
                                                <tr>
                                                    <td>Land and/or Building</td>
                                                    <td><input type="text" class="form-control currency_data"
                                                            id="land_and_or_building_" name="land_and_or_building_"></td>
                                                    <td><input type="text" class="form-control currency_data"
                                                            id="land_and_or_building_" name="land_and_or_building_"></td>
                                                </tr>
                                                <tr>
                                                    <td>Net Equity</td>
                                                    <td><input type="text" class="form-control currency_data"
                                                            id="net_equity_" name="net_equity_"></td>
                                                    <td><input type="text" class="form-control currency_data"
                                                            id="net_equity_" name="net_equity_"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div id="total-financial-ratio"></div>
                                    
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="d-flex justify-content-end" id="action_button_confirm_currency">
                                    <button type="button" class="btn btn-success" id="confirm_currency">Confirm</button>
                                </div>
                            </div>
                        </div>
                        <!-- END FORM INCOME STATEMENT -->
                    `)
                    $("#year-current").text(currentYear);
                    $("#year-prev-1, #bs_year_minus_1, #fr_year_minus_1").text(currentYear - 1);
                    $("#year-prev-2, #bs_year_minus_2, #fr_year_minus_2").text(currentYear - 2);
                    updateFieldIdsFinance('revenue_')
                    updateFieldIdsFinance('cost_of_revenue_')
                    updateFieldIdsFinance('gross_profit_')
                    updateFieldIdsFinance('opex_')
                    updateFieldIdsFinance('ebit_')
                    updateFieldIdsFinance('interest_expense_')
                    updateFieldIdsFinance('others_expense_')
                    updateFieldIdsFinance('others_income_')
                    updateFieldIdsFinance('ebt_')
                    updateFieldIdsFinance('net_profit_')
                    updateFieldIdsFinance('depreciation_expense_')
                    updateFieldIdsFinance('cash_')
                    updateFieldIdsFinance('acc_receivables_')
                    updateFieldIdsFinance('other_current_asset_')
                    updateFieldIdsFinance('current_asset_')
                    updateFieldIdsFinance('fixed_asset_')
                    updateFieldIdsFinance('total_asset_')
                    updateFieldIdsFinance('current_liabilities_')
                    updateFieldIdsFinance('non_current_liabilities_')
                    updateFieldIdsFinance('total_current_liabilities_')
                    updateFieldIdsFinance('equity_')
                    updateFieldIdsFinance('land_and_or_building_')
                    updateFieldIdsFinance('net_equity_')

                    $('.currency_data').on('input', function() {
                        let value = $(this).val().replace(/\D/g, '');
                        $(this).val(value.replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                    })
                    break;
            
                default:
                    $('.dynamic-form-income-statement').empty()
                    break;
            }
        })
        
        // $(document).on('focus', '.currency_data', function() {
        //     $(this).val($(this).val().replace(/,/g, ''));
        // });
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
                    <input type="text" name="business_classification_other_detail" id="business_classification_other_detail" placeholder="Other" class="form-control" style="width: 50% !important;">
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
                    <fieldset class="border px-2 mb-4">
                    <legend class="float-none w-auto text-bold">Data Alamat</legend>
                    <div class="row">
                        <div class="input-group mb-4">
                            <div class="col-md-3">
                                <label>Alamat Perusahaan (sesuai dengan NPWP) *</label>
                            </div>
                            <div class="col-md-9">
                                <input type="text" name="address[]" id="address" class="form-control">
                                <span class="text-danger mt-2 message_address" id="message_address_0" role="alert"></span>
                            </div>
                        </div>
                    
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="city">Kota *</label>
                                <input type="text" name="city[]" id="city" class="form-control">
                                <span class="text-danger mt-2 message_city" id="message_city_0" role="alert"></span>
                            </div>
                    
                            <div class="col-md-6">
                                <label for="country">Negara *</label>
                                <input type="text" name="country[]" id="country" class="form-control">
                                <span class="text-danger mt-2 message_country" id="message_country_0" role="alert"></span>
                            </div>
                        </div>
                    
                        <div class="row mb-4">
                            <div class="col-md-6">>
                                <label for="province">Provinsi *</label>
                                <input type="text" name="province[]" id="province" class="form-control">
                                <span class="text-danger mt-2 message_province" id="message_province_0" role="alert"></span>
                            </div>
                    
                            <div class="col-md-6">
                                <label for="zip_code">Kode Pos *</label>
                                <input type="text" name="zip_code[]" id="zip_code" class="form-control">
                                <span class="text-danger mt-2 message_zip_code" id="message_zip_code_0" role="alert"></span>
                            </div>
                        </div>
                    
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="telephone">Telephone *</label>
                                <p class="fs-6 text-muted mb-2">+ [Country-Area Code] [No.]</p>
                                <input type="number" name="telephone[]" id="telephone" class="form-control">
                                <span class="text-danger mt-2 message_telephone" id="message_telephone_0" role="alert"></span>
                            </div>
                    
                            <div class="col-md-6">
                                <label for="fax">Fax *</label>
                                <p class="fs-6 text-muted mb-2">+ [Country-Area Code] [No.]</p>
                                <input type="number" name="fax[]" id="fax" class="form-control">
                                <span class="text-danger mt-2 message_fax" id="message_fax_0" role="alert"></span>
                            </div>
                        </div>
                    </div>
                </fieldset>
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
            $('.dynamic_bank').append(`
                <div class="array_dymanic_bank">
                    <fieldset class="border px-2 mb-4">
                        <legend class="float-none w-auto text-bold">Data Bank</legend>
                        <div class="row mt-4">
                            <div class="col-md-6 mb-3">
                                <label for="bank_name">Nama Bank *</label>
                                <input type="text" name="bank_name[]" id="bank_name" class="form-control">
                                <span class="text-danger mt-2" id="message_bank_name" role="alert"></span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="branch">Cabang *</label>
                                <input type="text" name="branch[]" id="branch" class="form-control">
                                <span class="text-danger mt-2" id="message_branch" role="alert"></span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="account_name">Nama Akun *</label>
                                <input type="text" name="account_name[]" class="form-control">
                                <span class="text-danger mt-2" id="message_account_name" role="alert"></span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="city_or_country">Kota/Negara *</label>
                                <input type="text" name="city_or_country[]" class="form-control">
                                <span class="text-danger mt-2" id="message_city_or_country" role="alert"></span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="account_number">Nomor Akun *</label>
                                <input type="number" name="account_number[]" class="form-control">
                                <span class="text-danger mt-2" id="message_account_number" role="alert"></span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="currency">Mata Uang *</label>
                                <input type="text" name="currency[]" class="form-control">
                                <span class="text-danger mt-2" id="message_currency" role="alert"></span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="swift_code">Swift Code (Optional)</label>
                                <input type="text" name="swift_code[]" class="form-control">
                                <span class="text-danger mt-2" id="message_swift_code" role="alert"></span>
                            </div>
                        </div>
                    </fieldset>
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

        $(document).on('click', '#confirm_currency', function(e) {
            e.preventDefault()
            let currency_data = document.querySelectorAll(".currency_data");
            let all_filled = true
            
            // Cek apakah ada input yang kosong
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
                $('#total-financial-ratio').empty()
                $('#action_button_confirm_currency').empty()
                $('#action_button_confirm_currency').html(`
                    <button type="button" class="btn btn-success" id="confirm_currency">Confirm</button>
                `)
            } else {
                // Jika semua terisi, buat readonly
                currency_data.forEach(input => {
                    input.readOnly = all_filled;
                });

                // Disable tombol agar tidak bisa diklik lagi
                // this.disabled = true;
                $('#total-financial-ratio').empty()
                $('#total-financial-ratio').append(`
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
            $('#action_button_confirm_currency').empty()
            $('#action_button_confirm_currency').html(`
                <button type="button" class="btn btn-success" id="confirm_currency">Confirm</button>
            `)
        })

        $(document).on('click', '#btn_submit_data_company', function(e) {
            e.preventDefault()
            let data_form_company = new FormData($('#form_company')[0])
            $.ajax({
                url: '{{ route('submit-partner') }}',
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

                    if (response_error.meta.code == 500 || response_error.meta.code == 400) {
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
                    })
                    $(document).Toasts('create', {
                        title: 'Error',
                        class: 'bg-danger',
                        body: 'Harap isi data yang masih kosong',
                        delay: 10000,
                        autohide: true,
                        fade: true,
                        close: true,
                        autoremove: true,
                    });
                },
            })
        })
    })
</script>
