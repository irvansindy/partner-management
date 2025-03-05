<div class="card card-info">
    <div class="card-header">
        <h3 class="card-title">
            BANK DATA * (Data Bank)
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
            <div class="input-group d-flex justify-content-end mr-4 mb-4 mt-4">
                <button type="button" class="btn btn-primary" id="add_bank"> +
                    Bank</button>
            </div>
            <div class="dynamic_bank">
            
            </div>
        </div>
    </div>
</div>