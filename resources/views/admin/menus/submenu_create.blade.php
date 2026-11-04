<!-- Modal -->
<div class="modal fade" id="formCreateSubMenu" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog"
    aria-labelledby="formCreateSubMenu_label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="formCreateSubMenuLabel">{{ __('messages.Menu Setting Create Submenu') }}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('messages.Menu Setting Close') }}"></button>
            </div>
            <form action="" id="form_create_new_submenu">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="submenu_name">{{ __('messages.Menu Setting Name') }}</label>
                        <input type="text" class="form-control" id="submenu_name" placeholder="{{ __('messages.Menu Setting Name') }}">
                    </div>
                    <div class="form-group">
                        <label for="submenu_url">{{ __('messages.Menu Setting URL') }}</label>
                        <input type="text" class="form-control" id="submenu_url" placeholder="{{ __('messages.Menu Setting URL') }}">
                    </div>
                    <div class="form-group">
                        <label for="submenu_icon">{{ __('messages.Menu Setting Icon') }}</label>
                        <input type="text" class="form-control" id="submenu_icon" placeholder="{{ __('messages.Menu Setting Icon') }}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('messages.Menu Setting Close') }}</button>
                    <button type="button" class="btn btn-primary" id="submit_create_submenu">{{ __('messages.Menu Setting Save') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
