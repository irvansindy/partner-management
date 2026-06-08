<div class="card card-info">
    <div class="card-header">
        <h3 class="card-title">
            @lang('messages.Data Bank')
        </h3>
    </div>
    <div class="card-body">
        <fieldset class="border px-3 py-3 mb-4 rounded">
            <legend class="float-none w-auto px-2 text-bold">@lang('messages.Data Bank')</legend>
            {{-- =============================================
                 ROW 1: Nama Bank (kiri) | Nama Akun (kanan)
            ============================================= --}}
            <div class="row mt-3 mb-3">
                <div class="col-md-6 col-sm-12">
                    <label for="bank_name_0">@lang('messages.Bank Name') <span class="text-danger">*</span></label>
                    <input type="text" name="bank_name[]" id="bank_name_0" class="form-control">
                    <span class="text-danger mt-2" id="message_bank_name" role="alert"></span>
                </div>
                <div class="col-md-6 col-sm-12">
                    <label for="account_name_0">@lang('messages.Account Name') <span class="text-danger">*</span></label>
                    <input type="text" name="account_name[]" id="account_name_0" class="form-control">
                    <span class="text-danger mt-2" id="message_account_name" role="alert"></span>
                </div>
            </div>
            {{-- =============================================
                 ROW 2: Nomor Rekening (kiri) | Tombol tambah (kanan)
            ============================================= --}}
            <div class="row mb-3">
                <div class="col-md-6 col-sm-12">
                    <label for="account_number_0">@lang('messages.Account Number') <span class="text-danger">*</span></label>
                    <input type="number" name="account_number[]" id="account_number_0" class="form-control"
                        data-maxlength="16" maxlength="16">
                    <span class="text-danger mt-2" id="message_account_number" role="alert"></span>
                </div>
                <div class="col-md-6 col-sm-12 d-flex align-items-end justify-content-end">
                    <button type="button" class="btn btn-primary" id="add_bank">
                        <i class="fas fa-plus"></i> @lang('messages.Data Bank')
                    </button>
                </div>
            </div>
        </fieldset>
        <div class="dynamic_bank"></div>
    </div>
</div>