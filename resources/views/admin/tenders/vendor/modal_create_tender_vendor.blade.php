<!-- Modal -->
<div class="modal fade" id="modal_create_tender_vendor" data-backdrop="static" data-keyboard="false" tabindex="-1"  role="dialog" aria-labelledby="modal_create_tender_vendor_label" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content ">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modal_create_tender_vendor_label">Create New Tender</h1>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" id="form_create_tender_vendor">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="create_tender_name">Tender Name</label>
                                <input type="tetx" class="form-control" id="create_tender_name" name="create_tender_name" aria-describedby="">
                                {{-- <small id="tender_name_help" class="form-text text-muted">We'll never share your email with anyone else.</small> --}}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="create_tender_type">Tender Type</label>
                                <select class="form-control tender_select2" name="create_tender_type" id="create_tender_type" style="width: 100%;"></select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="create_tender_source_document">Tender Source Document</label>
                                <input type="file" class="form-control" id="create_tender_source_document" name="create_tender_source_document" aria-describedby="tender_source_document_help">
                                <small id="tender_source_document_help" class="form-text text-muted" style="cursor: pointer;">Check document</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="create_tender_created_date">Tender Created Date</label>
                                <input type="date" class="form-control" id="create_tender_created_date" name="create_tender_created_date" aria-describedby="" readonly="true">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="create_tender_effective_date">Tender Effective Date</label>
                                <input type="date" class="form-control" id="create_tender_effective_date" name="create_tender_effective_date" aria-describedby="">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="create_tender_expired_date">Tender Expired Date</label>
                                <input type="date" class="form-control" id="create_tender_expired_date" name="create_tender_expired_date" aria-describedby="">
                            </div>
                        </div>
                        
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="submit_create_tender_vendor">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
