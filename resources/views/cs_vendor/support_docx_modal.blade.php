<!-- Modal -->
<div class="modal fade" id="modal_support_document" data-backdrop="static" tabindex="-1" role="dialog"
    aria-labelledby="modal_support_documentLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <form id="form_submit_supporting_document" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal_support_documentLabel">Form Supporting Documents</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row row_list_supporting_document">
                        <div class="col-md-3">
                            <div class="form-group">
                                <select class="form-control select2_supporting_document" name="supporting_document_type[]" id="supporting_document_type_0">
                                    
                                </select>
                                <p class="fs-6 text-info" style="margin-bottom: 0.5rem !important; font-size: 12px !important;">Tipe Dokumen</p>
                                <span class="text-danger mt-2 message_supporting_document_type" id="message_supporting_document_type_0" role="alert"></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <select class="form-control select2_supporting_document" name="supporting_document_business_type[]" id="supporting_document_business_type_0">
                                    <option value="">tipe bisnis dokumen</option>
                                </select>
                                <p class="fs-6 text-info" style="margin-bottom: 0.5rem !important; font-size: 12px !important;">Tipe Usaha</p>
                                <span class="text-danger mt-2 message_supporting_document_business_type" id="message_supporting_document_business_type_0" role="alert"></span>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <input type="file" name="file_supporting_document[]" id="file_supporting_document_0" placeholder="" class="form-control" required/>
                                <p class="fs-6 text-info" style="margin-bottom: 0.5rem !important; font-size: 12px !important;">File Dokumen</p>
                                <span class="text-danger mt-2 message_file_supporting_document" id="message_file_supporting_document_0" role="alert"></span>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn btn-primary btn_add_supporting_document" id="btn_add_supporting_document">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div id="dynamic_row_list_supporting_document">
                        
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="d-flex justify-content-end">
                        <input type="hidden" name="supporting_document_partner_id" id="supporting_document_partner_id">
                        <button type="button" class="btn btn-primary" id="btn_submit_supporting_document">
                            submit
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
