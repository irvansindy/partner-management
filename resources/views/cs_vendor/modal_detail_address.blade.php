<!-- Modal -->
<div class="modal fade" id="dataDetailPartnerAddress" data-backdrop="static" tabindex="-1" role="dialog"
    aria-labelledby="dataDetailPartnerAddress" data-backdrop="static" tabindex="-1" role="dialog"Label" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="dataDetailPartnerAddress" data-backdrop="static" tabindex="-1" role="dialog"Label">Detail Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <form action="" method="" id="form_detail_partner_data_by_user">
                        {{-- company information master --}}
                        <div class="card card-info">
                            <div class="card-header">
                                <input type="hidden" name="detail_id" id="detail_id">
                                <h3 class="card-title">Detail Address</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <div class="list_detail_address" id="list_detail_address">

                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                </div>
                {{-- end master --}}
            </div>
            <div class="modal-footer" id="button_update_partner">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btn_update_data_address">Update</button>
            </div>
            </form>
        </div>
    </div>
</div>
