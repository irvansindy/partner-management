<!-- Modal -->
<div class="modal fade" id="formCreateSubMenu" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog"
    aria-labelledby="formCreateSubMenu_label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="formCreateSubMenuLabel">Create Menu</h1>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" id="form_create_new_submenu">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="submenu_name">Name</label>
                        <input type="text" class="form-control" id="submenu_name" placeholder="Name">
                    </div>
                    <div class="form-group">
                        <label for="submenu_url">Url</label>
                        <input type="text" class="form-control" id="submenu_url" placeholder="Name">
                    </div>
                    <div class="form-group">
                        <label for="submenu_icon">Icon</label>
                        <input type="text" class="form-control" id="submenu_icon" placeholder="Name">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="submit_create_submenu">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
