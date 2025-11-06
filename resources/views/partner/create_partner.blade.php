<div class="modal fade" id="ModalCreatePartner" data-backdrop="static" tabindex="-1" role="dialog"
    aria-labelledby="ModalCreatePartnerLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalCreatePartnerLabel">Create Partner</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <form action="" method="" id="form_create_company_partner">
                        @include('cs_vendor.form.master_information')

                        @include('cs_vendor.form.contact')

                        @include('cs_vendor.form.address')

                        @include('cs_vendor.form.bank')

                        @include('cs_vendor.form.survey')

                        {{-- @include('cs_vendor.form.tax') --}}

                        @include('cs_vendor.form.file_upload')
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btn_create_partner">Submit</button>
            </div>
        </div>
    </div>
</div>
