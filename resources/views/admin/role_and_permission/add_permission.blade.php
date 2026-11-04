<div class="modal fade" id="formAddPermission" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="formAddPermissionLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="formAddPermissionLabel">{{ __('messages.Role Permission Manage Permissions') }}</h1>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="{{ __('messages.Role Permission Close') }}"></button>
            </div>
            <form id="form_add_remove_permissions">
                @csrf
                <input type="hidden" name="role_id_for_permission" id="role_id_for_permission" readonly>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th scope="col" class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">#</th>
                                    <th scope="col" class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{ __('messages.Role Permission Permission List') }}</th>
                                </tr>
                            </thead>
                            <tbody id="list_permission_data"></tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('messages.Role Permission Close') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
