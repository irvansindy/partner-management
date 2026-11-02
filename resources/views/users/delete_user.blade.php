<div class="modal fade user-management-modal user-delete-modal" id="confirmDeleteUser" data-backdrop="static"
    data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteUserLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <span class="user-modal-kicker">CONFIRMATION</span>
                    <h2 class="modal-title" id="confirmDeleteUserLabel">Delete User</h2>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="delete-user-warning" aria-hidden="true">
                    <i class="material-icons">person_remove</i>
                </div>
                <p class="delete-user-title" id="delete_user_name"></p>
                <p class="delete-user-description">This action removes the account and its role assignment. It cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger confirm_delete_user" id="confirm_delete_user"
                    data-delete_user_id="">
                    <i class="material-icons" aria-hidden="true">delete</i>
                    Delete User
                </button>
            </div>
        </div>
    </div>
</div>
