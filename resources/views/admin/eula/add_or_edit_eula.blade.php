<!-- Modal -->
<div class="modal fade" id="AddOrEditEULA" data-backdrop="static" data-keyboard="false" tabindex="-1"  role="dialog" aria-labelledby="AddOrEditEULA_label" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content ">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="AddOrEditEULALabel">End User License Agreements</h1>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" id="form_end_user_license_agreements">
                <div class="modal-body">
                    <input type="hidden" name="end_user_license_agreements_id" id="end_user_license_agreements_id">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="end_user_license_agreements_name">Name</label>
                                <input type="text" class="form-control" id="end_user_license_agreements_name">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="end_user_license_agreements_year">Year</label>
                                <input type="number" class="form-control" id="end_user_license_agreements_year">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="custom-control custom-switch mb-3">
                                <input type="checkbox" class="custom-control-input" id="end_user_license_agreements_is_active" name="end_user_license_agreements_is_active">
                                <label class="custom-control-label" for="end_user_license_agreements_is_active">Toggle this switch element</label>
                            </div>
                        </div>
                    </div>
                    <textarea id="summernote_end_user_license_agreements"></textarea>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="submit_end_user_license_agreements">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
