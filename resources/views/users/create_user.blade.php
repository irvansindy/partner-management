<!-- Modal -->
<div class="modal fade" id="formCreateUser" data-backdrop="static" data-keyboard="false" tabindex="-1"  role="dialog" aria-labelledby="formCreateUser_label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="formCreateUserLabel">Create User</h1>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" id="form_create_new_user">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="user_name">Name</label>
                        <input type="text" class="form-control" name="user_name" id="user_name">
                    </div>           
                    <div class="form-group">
                        <label for="user_email">Email</label>
                        <input type="email" class="form-control" name="user_email" id="user_email">
                    </div>           
                    <div class="form-group">
                        <label for="user_role">Role</label>
                        <select class="form-control" name="user_role" id="user_role" style="width: 100% !important;"></select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="save_user">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
