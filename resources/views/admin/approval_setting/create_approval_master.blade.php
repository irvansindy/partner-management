<div class="modal fade approval-modal" id="formCreateApproval" data-backdrop="static" tabindex="-1" role="dialog"
    aria-labelledby="formCreateApprovalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <span class="approval-modal__eyebrow">{{ __('approval.New workflow template') }}</span>
                    <h5 class="modal-title" id="formCreateApprovalLabel">{{ __('approval.Create Approval Template') }}</h5>
                    <p class="approval-modal__subtitle">{{ __('approval.Define where this approval flow applies. Approvers are arranged after the template is created.') }}</p>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('approval.Close create approval dialog') }}">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="data_approval_master" novalidate>
                <div class="modal-body">
                    <div class="approval-form-section">
                        <div class="approval-form-section__title">
                            <span class="approval-form-section__number">1</span>
                            <div><strong>{{ __('approval.Template identity') }}</strong><small>{{ __('approval.Use a name that clearly identifies the business flow.') }}</small></div>
                        </div>
                        <div class="form-group mb-0">
                            <label for="approval_master_name">{{ __('approval.Template name') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="approval_master_name" id="approval_master_name"
                                required maxlength="255" autocomplete="off" placeholder="{{ __('approval.Example: Vendor Purchasing Approval') }}">
                            <small class="form-text text-muted">{{ __('approval.Letters, numbers, spaces, hyphens, and underscores are supported.') }}</small>
                            <span class="invalid-feedback d-block" data-field-error="approval_master_name"></span>
                        </div>
                    </div>

                    <div class="approval-form-section mb-0">
                        <div class="approval-form-section__title">
                            <span class="approval-form-section__number">2</span>
                            <div><strong>{{ __('approval.Assignment scope') }}</strong><small>{{ __('approval.Only one active template can be used for each office and department combination.') }}</small></div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-md-0">
                                    <label for="stagging_approval_office">{{ __('approval.Office') }} <span class="text-danger">*</span></label>
                                    <select class="form-control option_approval_master" name="stagging_approval_office"
                                        id="stagging_approval_office" required></select>
                                    <span class="invalid-feedback d-block" data-field-error="stagging_approval_office"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-0">
                                    <label for="stagging_approval_department">{{ __('approval.Department') }} <span class="text-danger">*</span></label>
                                    <select class="form-control option_approval_master" name="stagging_approval_department"
                                        id="stagging_approval_department" required></select>
                                    <span class="invalid-feedback d-block" data-field-error="stagging_approval_department"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">{{ __('admin.Cancel') }}</button>
                    <button type="submit" class="btn btn-primary" id="create_approval_master_data">
                        <i class="fas fa-save mr-1" aria-hidden="true"></i> {{ __('approval.Create Template') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
