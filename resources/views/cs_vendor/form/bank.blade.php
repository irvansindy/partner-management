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
                    <div class="col-md-4 col-lg-4 col-sm-12 mb-3">
                        <label for="bank_name_0">@lang('messages.Bank Name') <span class="text-danger" role="alert">*</span></label>
                        <input type="text" name="bank_name[]" id="bank_name_0" class="form-control">
                        <span class="text-danger mt-2" id="message_bank_name" role="alert"></span>
                    </div>
                    <div class="col-md-4 col-lg-4 col-sm-12 mb-3">
                        <label for="account_name_0">@lang('messages.Account Name') <span class="text-danger" role="alert">*</span></label>
                        <input type="text" name="account_name[]" id="account_name_0" class="form-control">
                        <span class="text-danger mt-2" id="message_account_name" role="alert"></span>
                    </div>
                    <div class="col-md-4 col-lg-4 col-sm-12 mb-3">
                        <label for="account_number_0">@lang('messages.Account Number') <span class="text-danger" role="alert">*</span></label>
                        <input type="number" name="account_number[]" id="account_number_0" class="form-control">
                        <span class="text-danger mt-2" id="message_account_number" role="alert"></span>
                    </div>
                </div>
            </fieldset>
            <div class="input-group d-flex justify-content-end mr-4 mb-4 mt-4">
                <button type="button" class="btn btn-primary" id="add_bank">
                    <i class="fas fa-plus"></i>
                </button>
            </div>
            <div class="dynamic_bank">

            </div>
        </div>
    </div>
</div>