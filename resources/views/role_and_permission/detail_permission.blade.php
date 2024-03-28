<!-- Modal -->
<div class="modal fade" id="formUpdatePermission" data-backdrop="static" data-keyboard="false" tabindex="-1"  role="dialog" aria-labelledby="formUpdatePermission_label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="formUpdatePermissionLabel">Detail Permission</h1>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" id="form_update_new_permission">
                <div class="modal-body">
                    <div class="form-group">
                        <input type="hidden" class="form-control" name="update_permission_id" id="update_permission_id">
                        <label for="update_permission_name">Permission Name</label>
                        <input type="text" class="form-control" name="update_permission_name" id="update_permission_name">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="update_permission">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
