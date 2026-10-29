<div class="modal fade approval-modal" id="formCreateApproval" data-backdrop="static" tabindex="-1" role="dialog"
    aria-labelledby="formCreateApprovalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <span class="approval-modal__eyebrow">New workflow template</span>
                    <h5 class="modal-title" id="formCreateApprovalLabel">Create Approval Template</h5>
                    <p class="approval-modal__subtitle">Define where this approval flow applies. Approvers are arranged after the template is created.</p>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close create approval dialog">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="data_approval_master" novalidate>
                <div class="modal-body">
                    <div class="approval-form-section">
                        <div class="approval-form-section__title">
                            <span class="approval-form-section__number">1</span>
                            <div><strong>Template identity</strong><small>Use a name that clearly identifies the business flow.</small></div>
                        </div>
                        <div class="form-group mb-0">
                            <label for="approval_master_name">Template name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="approval_master_name" id="approval_master_name"
                                required maxlength="255" autocomplete="off" placeholder="Example: Vendor Purchasing Approval">
                            <small class="form-text text-muted">Letters, numbers, spaces, hyphens, and underscores are supported.</small>
                            <span class="invalid-feedback d-block" data-field-error="approval_master_name"></span>
                        </div>
                    </div>

                    <div class="approval-form-section mb-0">
                        <div class="approval-form-section__title">
                            <span class="approval-form-section__number">2</span>
                            <div><strong>Assignment scope</strong><small>Only one active template can be used for each office and department combination.</small></div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-md-0">
                                    <label for="stagging_approval_office">Office <span class="text-danger">*</span></label>
                                    <select class="form-control option_approval_master" name="stagging_approval_office"
                                        id="stagging_approval_office" required></select>
                                    <span class="invalid-feedback d-block" data-field-error="stagging_approval_office"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-0">
                                    <label for="stagging_approval_department">Department <span class="text-danger">*</span></label>
                                    <select class="form-control option_approval_master" name="stagging_approval_department"
                                        id="stagging_approval_department" required></select>
                                    <span class="invalid-feedback d-block" data-field-error="stagging_approval_department"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="create_approval_master_data">
                        <i class="fas fa-save mr-1" aria-hidden="true"></i> Create Template
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
