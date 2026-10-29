<div class="modal fade approval-modal" id="addViewApprovalDetail" data-backdrop="static" tabindex="-1" role="dialog"
    aria-labelledby="addViewApprovalDetailLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <span class="approval-modal__eyebrow">Approval sequence</span>
                    <h5 class="modal-title" id="addViewApprovalDetailLabel">Arrange Approvers</h5>
                    <p class="approval-modal__subtitle">Approvals run from the first step to the last step in the order shown below.</p>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close approver dialog">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="data_approval_detail" novalidate>
                <div class="modal-body">
                    <input type="hidden" name="master_approval_id" id="master_approval_id">

                    <div class="approval-modal-context" aria-label="Selected approval template">
                        <div class="approval-modal-context__main">
                            <span class="approval-modal-context__label">Template</span>
                            <strong id="master_approval_name">-</strong>
                        </div>
                        <div><span>Office</span><strong data-template-office>-</strong></div>
                        <div><span>Department</span><strong data-template-department>-</strong></div>
                    </div>

                    <div class="approval-list-header">
                        <div>
                            <h6>Approval steps</h6>
                            <p>Select a different user for every step. Maximum 10 approvers.</p>
                        </div>
                        <button type="button" class="btn btn-outline-primary" id="add_stagging">
                            <i class="fas fa-plus mr-1" aria-hidden="true"></i> Add step
                        </button>
                    </div>

                    <div class="approval-modal-state" data-approval-loading aria-live="polite">
                        <span class="spinner-border spinner-border-sm" aria-hidden="true"></span> Loading approvers...
                    </div>
                    <div class="approval-modal-state approval-modal-state--error" data-approval-error role="alert" hidden></div>
                    <div class="approver-list" data-approver-list aria-live="polite"></div>
                    <span class="invalid-feedback d-block" data-field-error="stagging_approval_name"></span>
                </div>
                <div class="modal-footer">
                    <span class="approval-modal-footer-note"><i class="fas fa-info-circle" aria-hidden="true"></i> Changes apply to new approval processes.</span>
                    <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="create_approval_detail_data">
                        <i class="fas fa-save mr-1" aria-hidden="true"></i> Save Sequence
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
