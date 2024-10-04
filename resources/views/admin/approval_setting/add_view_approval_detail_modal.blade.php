<div class="modal fade" id="addViewApprovalDetail" data-backdrop="static" tabindex="-1" role="dialog"
    aria-labelledby="addViewApprovalDetailLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addViewApprovalDetailLabel">Approval Detail</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="data_approval_detail">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="hidden" class="form-control" name="master_approval_id" id="master_approval_id" readonly="true">
                                <input type="text" class="form-control" name="master_approval_name" id="master_approval_name" readonly="true">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-10">
                            <div class="form-group">
                                <select class="form-control option_approval_detail" name="stagging_approval_name[]" id="stagging_approval_name_0" style="width: 100%">
                                    <option value="">Select One</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-auto">
                            <button type="button" class="btn btn-outline-primary float-right" id="add_stagging">
                                <i class="fas fa-fw fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="dynamic_approval"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="create_approval_detail_data" data-master_approval_stagging="">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
