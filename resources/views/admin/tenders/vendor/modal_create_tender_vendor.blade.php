<!-- Modal -->
<div class="modal fade" id="modal_create_tender_vendor" data-backdrop="static" data-keyboard="false" tabindex="-1"
    role="dialog" aria-labelledby="modal_create_tender_vendor_label" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content ">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modal_create_tender_vendor_label">Create New Tender</h1>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" id="form_create_tender_vendor">
                <div class="modal-body">
                    <!-- Master form tender -->
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="create_tender_name">Tender Name</label>
                                <input type="tetx" class="form-control" id="create_tender_name"
                                    name="create_tender_name" aria-describedby="" required>
                                <div class="invalid-tooltip">
                                    Please provide a valid city.
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="create_tender_type">Tender Type</label>
                                <select class="form-control tender_select2" name="create_tender_type"
                                    id="create_tender_type" style="width: 100%;"></select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="create_tender_source_document">Tender Source Document</label>
                                <input type="file" class="form-control" id="create_tender_source_document"
                                    name="create_tender_source_document" aria-describedby="tender_source_document_help">
                                <small id="tender_source_document_help" class="form-text text-info"
                                    style="cursor: pointer;">Check document</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="create_tender_created_date">Tender Created Date</label>
                                <input type="date" class="form-control" id="create_tender_created_date"
                                    name="create_tender_created_date" aria-describedby="" readonly="true">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="create_tender_effective_date">Tender Effective Date</label>
                                <input type="date" class="form-control" id="create_tender_effective_date"
                                    name="create_tender_effective_date" aria-describedby="">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="create_tender_expired_date">Tender Expired Date</label>
                                <input type="date" class="form-control" id="create_tender_expired_date"
                                    name="create_tender_expired_date" aria-describedby="">
                            </div>
                        </div>
                    </div>
                    <!-- nav tab for tender product and tender vendor -->
                    <ul class="nav nav-tabs mt-2" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="product-tab" data-toggle="tab" data-target="#product"
                                type="button" role="tab" aria-controls="product"
                                aria-selected="true">product</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="vendor-tab" data-toggle="tab" data-target="#vendor"
                                type="button" role="tab" aria-controls="vendor"
                                aria-selected="false">Vendor</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">

                        <div class="tab-pane fade show active" id="product" role="tabpanel"
                            aria-labelledby="product-tab">
                            <div class="row mt-2" id="list_tender_product">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="create_tender_product_name_0">Product Name</label>
                                        <input type="text" class="form-control" id="create_tender_product_name_0"
                                            name="create_tender_product_name[]" aria-describedby="" required>
                                        <div class="invalid-tooltip">
                                            Please provide a valid city.
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="create_tender_product_requirement_0">Product Requirement</label>
                                        <textarea class="form-control" name="create_tender_product_requirement[]" id="create_tender_product_requirement_0"
                                            rows="2"></textarea>
                                    </div>
                                </div>
                                <div class="mr-4">
                                    <button type="button" class="btn btn-primary float-sm-right"
                                        id="add_dynamic_form_tender_product">
                                        <i class="fas fa-ws fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <div id="dynamic_form_tender_product"></div>
                        </div>
                        <div class="tab-pane fade" id="vendor" role="tabpanel" aria-labelledby="vendor-tab">
                            <div class="table-responsive p-2 px-md-2">
                                <table class="table table-hover align-items-center mb-0 data_tables" id="list_view_tender_vendor" width="100%">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" id="checkAll"></th>
                                            <th><label for="checkAll">Name</label></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                    </tbody>
                                </table>
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
