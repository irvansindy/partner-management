<!-- Modal -->
<div class="modal fade" id="modal_support_document_edit" data-backdrop="static" tabindex="-1" role="dialog"
    aria-labelledby="modal_support_document_editLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="form_update_supporting_document" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal_support_document_editLabel">Form Edit Supporting Documents</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input type="file" name="update_file_supporting_document" id="update_file_supporting_document" placeholder="" class="form-control" required/>
                        <p class="fs-6 text-info" style="margin-bottom: 0.5rem !important; font-size: 12px !important;">
                            <a href="" id="detail_link_attachment" target="blank">File Dokumen</a>
                        </p>
                        <span class="text-danger mt-2 message_update_file_supporting_document" id="message_update_file_supporting_document_0" role="alert"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="d-flex justify-content-end">
                        <input type="hidden" name="update_supporting_document_id" id="update_supporting_document_id">
                        <input type="hidden" name="update_supporting_document_partner_id" id="update_supporting_document_partner_id">
                        <button type="button" class="btn btn-primary" id="btn_update_supporting_document">
                            submit
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>