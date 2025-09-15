<div class="card card-info">
    <div class="card-header">
        <h3 class="card-title">
            @lang('messages.Data Bank')
        </h3>
        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="row px-2 py-2" style="line-height: 1">
            <fieldset class="border px-2 mb-4">
                <legend class="float-none w-auto text-bold">@lang('messages.Data Bank')</legend>
                <div class="row mt-4">
                    <div class="col-md-6 mb-3">
                        <label for="bank_name_0">@lang('messages.Bank Name') *</label>
                        <input type="text" name="bank_name[]" id="bank_name_0" class="form-control">
                        <span class="text-danger mt-2" id="message_bank_name" role="alert"></span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="branch_0">@lang('messages.Branch') *</label>
                        <input type="text" name="branch[]" id="branch_0" class="form-control">
                        <span class="text-danger mt-2" id="message_branch" role="alert"></span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="account_name_0">@lang('messages.Account Name') *</label>
                        <input type="text" name="account_name[]" id="account_name_0" class="form-control">
                        <span class="text-danger mt-2" id="message_account_name" role="alert"></span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="city_or_country_0">@lang('messages.City/Country') *</label>
                        <input type="text" name="city_or_country[]" id="city_or_country_0" class="form-control">
                        <span class="text-danger mt-2" id="message_city_or_country" role="alert"></span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="account_number_0">@lang('messages.Account Number') *</label>
                        <input type="number" name="account_number[]" id="account_number_0" class="form-control">
                        <span class="text-danger mt-2" id="message_account_number" role="alert"></span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="currency_0">@lang('messages.Currency') *</label>
                        <input type="text" name="currency[]" id="currency_0" class="form-control">
                        <span class="text-danger mt-2" id="message_currency" role="alert"></span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="swift_code_0">@lang('messages.Swift Code (Optional)')</label>
                        <input type="text" name="swift_code[]" id="swift_code_0" class="form-control">
                        <span class="text-danger mt-2" id="message_swift_code" role="alert"></span>
                    </div>
                </div>
            </fieldset>
            <fieldset class="border px-2 mb-4">
                <legend class="float-none w-auto text-bold">Data Bank</legend>
                <div class="row mt-4">
                    <div class="col-md-6 mb-3">
                        <label for="bank_name_1">@lang('messages.Bank Name') *</label>
                        <input type="text" name="bank_name[]" id="bank_name_1" class="form-control">
                        <span class="text-danger mt-2" id="message_bank_name" role="alert"></span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="branch_1">@lang('messages.Branch') *</label>
                        {{-- opsional --}}
                        <input type="text" name="branch[]" id="branch_1" class="form-control">
                        <span class="text-danger mt-2" id="message_branch" role="alert"></span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="account_name_1">@lang('messages.Account Name') *</label>
                        <input type="text" name="account_name[]" id="account_name_1" class="form-control">
                        <span class="text-danger mt-2" id="message_account_name" role="alert"></span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="city_or_country_1">@lang('messages.City/Country') *</label>
                        <input type="text" name="city_or_country[]" id="city_or_country_1" class="form-control">
                        <span class="text-danger mt-2" id="message_city_or_country" role="alert"></span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="account_number_1">@lang('messages.Account Number') *</label>
                        <input type="number" name="account_number[]" id="account_number_1" class="form-control">
                        <span class="text-danger mt-2" id="message_account_number" role="alert"></span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="currency_1">@lang('messages.Currency') *</label>
                        <input type="text" name="currency[]" id="currency_1" class="form-control">
                        <span class="text-danger mt-2" id="message_currency_1" role="alert"></span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="swift_code_1">@lang('messages.Swift Code (Optional)')</label>
                        <input type="text" name="swift_code[]" id="swift_code_1" class="form-control">
                        <span class="text-danger mt-2" id="message_swift_code_1" role="alert"></span>
                    </div>
                </div>
            </fieldset>
            <div class="input-group d-flex justify-content-end mr-4 mb-4 mt-4">
                <button type="button" class="btn btn-primary" id="add_bank">
                    + Bank
                </button>
            </div>
            <div class="dynamic_bank">
            
            </div>
        </div>
    </div>
</div>