<div class="card card-info">
    <div class="card-header">
        <h3 class="card-title">
            {{ __('messages.Tax Data') }}
        </h3>
        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="row px-2 py-2" style="line-height: 1">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="register_number_as_in_tax_invoice">{{ __('messages.Tax Register Number (As in Tax Invoice)') }} *</label>
                    <input type="text" class="form-control" id="register_number_as_in_tax_invoice" name="register_number_as_in_tax_invoice">
                </div>
                <div class="col-md-6">
                    {{-- opsional --}}
                    <label for="trc_number">{{ __('messages.Tax Residency Certificate') }} *</label>
                    <input type="text" class="form-control" id="trc_number" name="trc_number">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="register_number_related_branch">{{ __('messages.Tax Register Number (Related Branch)') }} *</label>
                    <input type="text" class="form-control" id="register_number_related_branch" name="register_number_related_branch">
                </div>
                <div class="col-md-6">
                    <label for="valid_until">{{ __('messages.Valid Until') }} *</label>
                    <input type="date" class="form-control" id="valid_until" name="valid_until">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="taxable_entrepreneur_number">{{ __('messages.Taxable Entrepreneur Number') }} *</label>
                    <input type="text" class="form-control" id="taxable_entrepreneur_number" name="taxable_entrepreneur_number">
                </div>
                <div class="col-md-6">
                    {{-- nomor seri faktur pajak --}}
                    <label for="tax_invoice_serial_number">{{ __('messages.Tax Invoice Serial Number') }} *</label>
                    <input type="text" class="form-control" id="tax_invoice_serial_number" name="tax_invoice_serial_number">
                </div>
            </div>
        </div>
    </div>
</div>
