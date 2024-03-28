<!-- Modal -->
<div class="modal fade" id="confirmDeleteRole" data-backdrop="static" data-keyboard="false" tabindex="-1"  role="dialog" aria-labelledby="confirmDeleteRole_label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="confirmDeleteRoleLabel">Delete Role</h1>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" id="form_delete_role">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="role_name">Role Name</label>
                        <input type="text" class="form-control" name="role_name" id="role_name">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="delete_role">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>
