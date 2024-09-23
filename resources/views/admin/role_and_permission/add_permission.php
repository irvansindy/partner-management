<!-- Modal -->
<div class="modal fade" id="formAddPermission" data-backdrop="static" data-keyboard="false" tabindex="-1"  role="dialog" aria-labelledby="formAddPermission_label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="formAddPermissionLabel">Permission list</h1>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" id="form_create_new_role">
                <div class="modal-body">
                    <table class="table table-hover table-striped align-items-center">
                        <thead>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                #
                            </th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                Permission
                            </th>
                        </thead>
                        <tbody id="list_permission_data">

                        </tbody>
                    </table>
                </div>
                <!-- <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="add_permission">Save</button>
                </div> -->
            </form>
        </div>
    </div>
</div>
