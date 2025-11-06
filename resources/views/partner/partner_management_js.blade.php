<script>
    $(document).ready(function() {
        var currentYear = new Date().getFullYear();
        $("#year-current").text(currentYear);
        $("#year_prev_1, #bs_year_minus_1, #fr_year_minus_1").text(currentYear - 1);
        $("#year_prev_2, #bs_year_minus_2, #fr_year_minus_2").text(currentYear - 2);

        let aYearAgo = currentYear - 1;
        let twoYearAgo = currentYear - 2;

        function updateFieldIdsFinance(prefix) {
            $('input[id^="' + prefix +'"]').each(function(index) {
                $(this).attr('id', prefix + [aYearAgo, twoYearAgo][index]);
                $(this).attr('name', prefix + [aYearAgo, twoYearAgo][index]);
            });
        }

        $(document).on('click', '#btn_form_create_partner', function() {
            updateFieldIds()
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
                    <div class="input-group d-flex justify-content-end mb-4 mt-4">
                        <button type="button" class="btn btn-danger" id="delete_dynamic_address">- Address</button>
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
                let realIndex = index + 2;
                // Update IDs for input elements
                $(this).find('input[name="address[]"]').attr('id', 'address_' + realIndex);
                $(this).find('input[name="city[]"]').attr('id', 'city_' + realIndex);
                $(this).find('input[name="country[]"]').attr('id', 'country_' + realIndex);
                $(this).find('input[name="province[]"]').attr('id', 'province_' + realIndex);
                $(this).find('input[name="zip_code[]"]').attr('id', 'zip_code_' + realIndex);
                $(this).find('input[name="telephone[]"]').attr('id', 'telephone_' + realIndex);
                $(this).find('input[name="fax[]"]').attr('id', 'fax_' + realIndex);

                // Update IDs for span elements (error message)
                $(this).find('.message_address').attr('id', 'message_address_' + realIndex);
                $(this).find('.message_city').attr('id', 'message_city_' + realIndex);
                $(this).find('.message_country').attr('id', 'message_country_' + realIndex);
                $(this).find('.message_province').attr('id', 'message_province_' + realIndex);
                $(this).find('.message_zip_code').attr('id', 'message_zip_code_' + realIndex);
                $(this).find('.message_telephone').attr('id', 'message_telephone_' + realIndex);
                $(this).find('.message_fax').attr('id', 'message_fax_' + realIndex);
            });
        }

        $('#partner_table').DataTable({
            processing: true,
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

        $('#company_type').change(function() {
            let value = $(this).val()
            switch (value) {
                case 'vendor':
                    $('.dynamic-form-income-statement').empty()
                    $('#switch-customer').prop('checked', false);
                    $('.switch-customer').hide();
                    break;
                case 'customer':
                    $('.dynamic-form-income-statement').empty()
                    $('#switch-customer').prop('checked', false);
                    $('.switch-customer').show();
                    break;
                default:
                    $('.dynamic-form-income-statement').empty()
                    $('#switch-customer').prop('checked', false);
                    $('.switch-customer').hide();
                    break;
            }
            // $('.switch-customer').show();
        })

        $('#switch-customer').on('change', function () {
            $('.dynamic-form-income-statement').empty()
            if ($(this).is(':checked')) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '{{ route('fetch-income-balance') }}',
                    type: 'GET',
                    dataType: 'json',
                    async: true,
                    success: function(res) {
                        $('.dynamic-form-income-statement').empty();
                        $('.dynamic-form-income-statement').html(`
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
                                    <div class="card py-2" id="table_income_statement">
                                        <strong class="mx-2">
                                            <h4>INCOME STATEMENT</h4>
                                        </strong>
                                        <table class="table table-bordered my-2">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>(in IDR)</th>
                                                    <th class="year_prev_1"></th>
                                                    <th class="year_prev_2"></th>
                                                </tr>
                                            </thead>
                                            <tbody id="table_income_statement_body">

                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="card py-2" id="table_balance_sheet">
                                        <strong class="mx-2">
                                            <h4>BALANCE SHEET</h4>
                                        </strong>
                                        <table class="table table-bordered my-2">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>(in IDR)</th>
                                                    <th class="bs_year_minus_1"></th>
                                                    <th class="bs_year_minus_2"></th>
                                                </tr>
                                            </thead>
                                            <tbody id="table_balance_sheet_body">
                                                <tr>
                                                    <td><strong>Revenue</strong></td>
                                                    <td>
                                                        <input type="text" class="form-control currency_data" name="revenue_" id="revenue_" value="">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control currency_data" name="revenue_" id="revenue_" value="">
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div id="total_financial_ratio">

                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="d-flex justify-content-end" id="action_button_confirm_currency">
                                        <button type="button" class="btn btn-success" id="confirm_currency">Confirm</button>
                                    </div>
                                </div>
                            </div>
                        `);

                        $("#year-current").text(currentYear);
                        $(".year_prev_1, .bs_year_minus_1, .fr_year_minus_1").text(currentYear - 1);
                        $(".year_prev_2, .bs_year_minus_2, .fr_year_minus_2").text(currentYear - 2);

                        $('#table_income_statement_body').empty()
                        $('#table_balance_sheet_body').empty()
                        $('#total_financial_ratio').hide()

                        let income_statement_data = res.data.income_statement
                        let balance_sheet_data = res.data.balance_sheet

                        income_statement_data.forEach(data => {
                            let id_1 = data.id+'_'+aYearAgo
                            let id_2 = data.id+'_'+twoYearAgo
                            let name_1 = data.name+'_'+aYearAgo
                            let name_2 = data.name+'_'+twoYearAgo
                            const fieldLabel = data.name.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                            $('#table_income_statement_body').append(`
                                <tr>
                                    <td><strong>${fieldLabel}</strong></td>
                                    <td>
                                        <input type="text" class="form-control currency_data" name="${name_1}" id="${name_1}" value="">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control currency_data" name="${name_2}" id="${name_2}" value="">
                                    </td>
                                </tr>
                            `)
                        })

                        balance_sheet_data.forEach(data => {
                            let id_1 = data.id+'_'+aYearAgo
                            let id_2 = data.id+'_'+twoYearAgo
                            let name_1 = data.name+'_'+aYearAgo
                            let name_2 = data.name+'_'+twoYearAgo
                            const fieldLabel = data.name.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                            $('#table_balance_sheet_body').append(`
                                <tr>
                                    <td><strong>${fieldLabel}</strong></td>
                                    <td>
                                        <input type="text" class="form-control currency_data" name="${name_1}" id="${name_1}" value="">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control currency_data" name="${name_2}" id="${name_2}" value="">
                                    </td>
                                </tr>
                            `)
                        })
                        $('.currency_data').on('input', function() {
                            let value = $(this).val().replace(/\D/g, '');
                            $(this).val(value.replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                        })
                    }
                });
            }

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
                    let revenue = parseFloat(document.getElementById("revenue_"+year).value)
                    let netProfit = parseFloat(document.getElementById("net_profit_"+year).value)
                    let currentAsset = parseFloat(document.getElementById("current_asset_"+year).value)
                    let totalAsset = parseFloat(document.getElementById("total_asset_"+year).value)
                    let currentLiabilities = parseFloat(document.getElementById("current_liabilities_"+year).value)
                    let ebit = parseFloat(document.getElementById("ebit_"+year).value)
                    let depreciationExprense = parseFloat(document.getElementById("depreciation_expense_"+year).value)
                    let totalLiabilities = parseFloat(document.getElementById("total_current_liabilities_"+year).value)
                    let interestExprense = parseFloat(document.getElementById("interest_expense_"+year).value)
                    let accountReceivable = parseFloat(document.getElementById("acc_receivables_"+year).value)

                    let workingCapitalRatio =+ (currentAsset / currentLiabilities) * 100;
                    let cashFlowCoverageRatio =+ ((ebit + depreciationExprense) / totalLiabilities) * 100;
                    let timeInterestEarnedRatio =+ (ebit / interestExprense) * 100;
                    let debtToAssetRatio =+ (totalLiabilities / totalAsset) * 100;
                    let accountReceivableTurnOver =+ (revenue / ((accountReceivable + accountReceivable) / 2)) * 100;
                    let netProfitMargin =+ (revenue / netProfit) * 100;

                    // Tampilkan hasil di field wc_ratio_
                    $('#wc_ratio_'+year).val(workingCapitalRatio.toFixed(2))
                    $('#cf_coverage_'+year).val(cashFlowCoverageRatio.toFixed(2))
                    $('#tie_ratio_'+year).val(timeInterestEarnedRatio.toFixed(2))
                    $('#debt_asset_'+year).val(debtToAssetRatio.toFixed(2))
                    $('#account_receivable_turn_over_'+year).val(accountReceivableTurnOver.toFixed(2))
                    $('#net_profit_margin_'+year).val(netProfitMargin.toFixed(2))
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

                    if(res.data.is_approved == true) {
                        $('#button-for-approval').append(`<button type="button" class="btn btn-danger" id="partner_reject">Reject</button><button type="button" class="btn btn-primary" id="partner_approved">Approved</button>`)
                    }
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

        $(document).on('click', '#btn_create_partner', function(e) {
            e.preventDefault()
            let data_form_company = new FormData($('#form_create_company_partner')[0])
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
                    $('#ModalCreatePartner').modal('hide')
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
                        console.log(value);
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
                }
            })
        })
    })
</script>