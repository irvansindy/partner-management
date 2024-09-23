<!-- Modal -->
<div class="modal fade" id="formCreatePermission" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog"
    aria-labelledby="formCreatePermission_label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="formCreatePermissionLabel">Create Permission</h1>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" id="form_create_new_permission">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="permission_name">Permission Name</label>
                        <input type="text" class="form-control" name="permission_name" id="permission_name">
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input for_set_menu_or_submenu" type="radio" name="select_menu_or_submenu" id="select_menu">
                                <label class="form-check-label" for="select_menu">
                                    Create as menu
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input for_set_menu_or_submenu" type="radio" name="select_menu_or_submenu" id="select_submenu">
                                <label class="form-check-label" for="select_submenu">
                                    Create as submenu
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group" id="get_menu"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="save_permission">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
