<!-- Modal -->
<div class="modal fade" id="formOffice" data-backdrop="static" role="dialog" aria-labelledby="formOfficeLabel" aria-hidden="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="formOfficeLabel"></h1>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" id="form_data_office">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="office_name">Name</label>
                        <input type="hidden" class="form-control" name="office_id" id="office_id">
                        <input type="text" class="form-control" name="office_name" id="office_name">
                        <span class="text-danger mt-2 message_office_name" id="message_office_name" role="alert"></span>
                    </div>
                    <div class="form-group">
                        <label for="office_address">Address</label>
                        <textarea class="form-control" name="office_address" id="office_address"></textarea>
                        <span class="text-danger mt-2 message_office_address" id="message_office_address" role="alert"></span>
                    </div>
                </div>
                <div class="modal-footer" id="button_action_office">
                    
                </div>
            </form>
        </div>
    </div>
</div>
