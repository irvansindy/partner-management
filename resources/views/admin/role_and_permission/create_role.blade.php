<!-- Modal -->
<div class="modal fade" id="formCreateRole" data-backdrop="static" data-keyboard="false" tabindex="-1"  role="dialog" aria-labelledby="formCreateRole_label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="formCreateRoleLabel">{{ __('messages.Role Permission Create Role') }}</h1>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="{{ __('messages.Role Permission Close') }}"></button>
            </div>
            <form action="" id="form_create_new_role">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="role_name">{{ __('messages.Role Permission Role Name') }}</label>
                        <input type="text" class="form-control" name="role_name" id="role_name" placeholder="{{ __('messages.Role Permission Role Placeholder') }}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('messages.Role Permission Close') }}</button>
                    <button type="button" class="btn btn-primary" id="save_role">{{ __('messages.Role Permission Save') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
