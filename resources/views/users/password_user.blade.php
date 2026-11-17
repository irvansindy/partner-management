<div class="modal user-management-modal" id="formPasswordUser" tabindex="-1" role="dialog"
     aria-labelledby="passwordUserTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="passwordUserTitle">{{ __('user_password.title') }}</h2>
                <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('messages.User Management Close') }}"><span aria-hidden="true">&times;</span></button>
            </div>
            <form id="form_user_password">
                <div class="modal-body">
                    <input type="hidden" name="id" id="password_user_id">
                    <p id="password_user_name" class="font-weight-bold"></p>
                    <p class="text-muted">{{ __('user_password.hint') }}</p>
                    <div class="form-group">
                        <label for="new_user_password">{{ __('user_password.password') }}</label>
                        <input class="form-control" type="password" id="new_user_password" name="password" autocomplete="new-password" minlength="8" maxlength="72" required aria-describedby="password_user_error">
                    </div>
                    <div class="form-group">
                        <label for="confirm_user_password">{{ __('user_password.confirmation') }}</label>
                        <input class="form-control" type="password" id="confirm_user_password" name="password_confirmation" autocomplete="new-password" minlength="8" maxlength="72" required>
                    </div>
                    <p class="user-field-error" id="password_user_error" role="alert"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">{{ __('messages.User Management Cancel') }}</button>
                    <button type="submit" class="btn btn-primary" id="save_user_password">{{ __('user_password.save') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
<style>
    #formPasswordUser .modal-dialog { max-width: 520px; }
    #formPasswordUser .form-group > label {
        position: static; display: block; transform: none; margin-bottom: .5rem;
        font-size: 14px; color: #4a4a5a;
    }
    #formPasswordUser .form-control { padding: 12px 16px; }
</style>
