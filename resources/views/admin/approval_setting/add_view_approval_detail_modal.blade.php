<div class="modal fade approval-modal" id="addViewApprovalDetail" data-backdrop="static" tabindex="-1" role="dialog"
    aria-labelledby="addViewApprovalDetailLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <span class="approval-modal__eyebrow">{{ __('approval.Urutan approval') }}</span>
                    <h5 class="modal-title" id="addViewApprovalDetailLabel">{{ __('approval.Atur Tahap dan PIC') }}</h5>
                    <p class="approval-modal__subtitle">{{ __('approval.Tahap dijalankan berurutan dari atas ke bawah.') }}</p>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('approval.Close approver dialog') }}">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="data_approval_detail" novalidate>
                <div class="modal-body">
                    <input type="hidden" name="master_approval_id" id="master_approval_id">

                    <div class="approval-modal-context" aria-label="{{ __('approval.Selected approval template') }}">
                        <div class="approval-modal-context__main">
                            <span class="approval-modal-context__label">{{ __('approval.Template') }}</span>
                            <strong id="master_approval_name">-</strong>
                        </div>
                        <div><span>{{ __('admin.Office') }}</span><strong data-template-office>-</strong></div>
                        <div><span>{{ __('admin.Department') }}</span><strong data-template-department>-</strong></div>
                    </div>

                    <div class="approval-list-header">
                        <div>
                            <h6>{{ __('approval.Tahap approval') }}</h6>
                            <p>{{ __('approval.Satu persetujuan dari salah satu PIC akan menyelesaikan tahap tersebut.') }}</p>
                        </div>
                        <button type="button" class="btn btn-outline-primary" id="add_stagging">
                            <i class="fas fa-plus mr-1" aria-hidden="true"></i> {{ __('approval.Tambah tahap') }}
                        </button>
                    </div>

                    <div class="approval-modal-state" data-approval-loading aria-live="polite">
                        <span class="spinner-border spinner-border-sm" aria-hidden="true"></span> {{ __('approval.Loading approvers...') }}
                    </div>
                    <div class="approval-modal-state approval-modal-state--error" data-approval-error role="alert" hidden></div>
                    <div class="approver-list" data-approver-list aria-live="polite"></div>
                    <span class="invalid-feedback d-block" data-field-error="approval_stages"></span>
                </div>
                <div class="modal-footer">
                    <span class="approval-modal-footer-note"><i class="fas fa-info-circle" aria-hidden="true"></i> {{ __('approval.Changes apply to new approval processes.') }}</span>
                    <button type="button" class="btn btn-light" data-dismiss="modal">{{ __('admin.Cancel') }}</button>
                    <button type="submit" class="btn btn-primary" id="create_approval_detail_data">
                        <i class="fas fa-save mr-1" aria-hidden="true"></i> {{ __('approval.Simpan urutan') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
