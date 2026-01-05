<div class="modal fade" id="formIpAddress" data-backdrop="static" tabindex="-1" role="dialog"
    aria-labelledby="formIpAddressLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="formIpAddressLabel">IP Address Master</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="data_form_ip_address" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="ip_address">IP Address</label>
                        <input type="hidden" class="form-control" id="id_ip_address" placeholder="IP Address"
                            name="id_ip_address">
                        <input type="text" class="form-control" id="ip_address" placeholder="IP Address"
                            name="ip_address">
                        <span class="text-danger" id="message_ip_address"></span>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" placeholder="Description" name="description"></textarea>
                        <span class="text-danger" id="message_description"></span>
                    </div>
                </div>
                <div class="modal-footer" id="button-ip_address">

                </div>
            </form>
        </div>
    </div>
</div>
