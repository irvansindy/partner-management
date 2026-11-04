<div class="modal fade user-management-modal user-delete-modal" id="confirmDeleteUser" data-backdrop="static"
    data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteUserLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <span class="user-modal-kicker">{{ __('messages.User Management Confirmation') }}</span>
                    <h2 class="modal-title" id="confirmDeleteUserLabel">{{ __('messages.User Management Delete User') }}</h2>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('messages.User Management Close') }}">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="delete-user-warning" aria-hidden="true">
                    <i class="material-icons">person_remove</i>
                </div>
                <p class="delete-user-title" id="delete_user_name"></p>
                <p class="delete-user-description">{{ __('messages.User Management Delete Description') }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">{{ __('messages.User Management Cancel') }}</button>
                <button type="button" class="btn btn-danger confirm_delete_user" id="confirm_delete_user"
                    data-delete_user_id="">
                    <i class="material-icons" aria-hidden="true">delete</i>
                    {{ __('messages.User Management Delete User') }}
                </button>
            </div>
        </div>
    </div>
</div>
