<!-- Modal -->
<div class="modal fade" id="confirmDeleteRole" data-backdrop="static" data-keyboard="false" tabindex="-1"  role="dialog" aria-labelledby="confirmDeleteRole_label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="confirmDeleteRoleLabel">{{ __('messages.Role Permission Delete Title') }}</h1>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="{{ __('messages.Role Permission Close') }}"></button>
            </div>
            <form action="" id="form_delete_role">
                <div class="modal-body">
                    <input type="hidden" id="delete_role_id" name="role_id">
                    <p class="mb-0" id="delete_role_warning"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('messages.Role Permission Close') }}</button>
                    <button type="button" class="btn btn-danger" id="confirm_delete_role">{{ __('messages.Role Permission Confirm Delete') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
