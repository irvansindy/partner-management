<!-- Modal -->
<div class="modal fade" id="confirmDeleteUser" data-backdrop="static" data-keyboard="false" tabindex="-1"  role="dialog" aria-labelledby="confirmDeleteUser_label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="confirmDeleteUserLabel">Delete User</h1>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" id="form_delete_user">
                <div class="modal-body">
                    <div class="form-group">
                        <h4 id="delete_user_name"></h4>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger confirm_delete_user" id="confirm_delete_user" data-delete_user_id="" data-delete_role_user="">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>
