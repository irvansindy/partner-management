<!-- Modal -->
<div class="modal fade" id="formUpdateUser" data-backdrop="static" data-keyboard="false" tabindex="-1"  role="dialog" aria-labelledby="formUpdateUser_label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="formUpdateUserLabel">Detail User</h1>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" id="form_detail_current_user">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="update_user_name">Name</label>
                        <input type="text" class="form-control" name="update_user_name" id="update_user_name">
                    </div>           
                    <div class="form-group">
                        <label for="update_user_email">Email</label>
                        <input type="email" class="form-control" name="update_user_email" id="update_user_email">
                    </div>           
                    <div class="form-group">
                        <input type="hidden" class="form-control" name="update_current_role" id="update_current_role">
                        <label for="update_user_role">Role</label>
                        <select class="form-control" name="update_user_role" id="update_user_role" style="width: 100% !important;"></select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary update_user" id="update_user" data-update_user_id="">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
