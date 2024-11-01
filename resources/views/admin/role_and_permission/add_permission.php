<!-- Modal -->
<div class="modal fade" id="formAddPermission" data-backdrop="static" data-keyboard="false" tabindex="-1"  role="dialog" aria-labelledby="formAddPermission_label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="formAddPermissionLabel">Permission list</h1>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" id="form_add_remove_permissions">
                <input type="hidden" name="role_id_for_permission" id="role_id_for_permission" readonly>
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
            </form>
        </div>
    </div>
</div>
