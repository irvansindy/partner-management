<!-- Modal -->
<div class="modal fade" id="formCreateMenu" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog"
    aria-labelledby="formCreateMenu_label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="formCreateMenuLabel">{{ __('messages.Menu Setting Create Menu') }}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('messages.Menu Setting Close') }}"></button>
            </div>
            <form action="" id="form_create_new_menu">
                <input type="hidden" name="id" id="menu_id">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="menu_name">{{ __('messages.Menu Setting Name') }}</label>
                        <input type="text" class="form-control" name="menu_name" id="menu_name" placeholder="{{ __('messages.Menu Setting Name') }}">
                    </div>
                    <div class="form-group">
                        <label for="menu_url">{{ __('messages.Menu Setting URL') }}</label>
                        <input type="text" class="form-control" name="menu_url" id="menu_url" placeholder="{{ __('messages.Menu Setting URL') }}">
                    </div>
                    <div class="form-group">
                        <label for="menu_icon">{{ __('messages.Menu Setting Icon') }}</label>
                        <input type="text" class="form-control" name="menu_icon" id="menu_icon" placeholder="{{ __('messages.Menu Setting Icon') }}">
                    </div>
                    <!-- <div class="form-group">
                        <label for="menu_type">Menu Type</label>
                        <br>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="menu_type" id="menu_type_parent"
                                value="parent">
                            <label class="form-check-label" for="menu_type_parent">Parent</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="menu_type" id="menu_type_children"
                                value="children">
                            <label class="form-check-label" for="menu_type_children">Children</label>
                        </div>
                    </div> -->
                    <div class="form-check">
                        <label class="d-block mb-2" for="menu_type">{{ __('messages.Menu Setting Menu Type') }}</label>
                        <div class="d-flex flex-wrap" style="gap: 1.5rem;">
                            <div class="form-check form-check-inline m-0 d-flex align-items-center" style="gap: .4rem; padding-left: 0;">
                                <input class="form-check-input m-0" style="position: static; float: none;" type="radio"
                                    name="menu_type" id="menu_type_parent" value="parent">
                                <label class="form-check-label m-0" for="menu_type_parent">{{ __('messages.Menu Setting Parent') }}</label>
                            </div>
                            <div class="form-check form-check-inline m-0 d-flex align-items-center" style="gap: .4rem; padding-left: 0;">
                                <input class="form-check-input m-0" style="position: static; float: none;" type="radio"
                                    name="menu_type" id="menu_type_children" value="children">
                                <label class="form-check-label m-0" for="menu_type_children">{{ __('messages.Menu Setting Child') }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group" id="parent_menu">

                    </div>
                    <div class="form-group" id="roles_menu">
                        <label for="roles">{{ __('messages.Menu Setting Roles') }}</label>
                        <select class="js-states form-control" name="roles[]" id="roles" multiple style="width: 100%">
                            <option value="">{{ __('messages.Menu Setting Select One') }}</option>
                            @php
                                $roles = \Spatie\Permission\Models\Role::all();
                            @endphp
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-soft-secondary btn-outline-secondary btn-sm" data-bs-dismiss="modal">{{ __('messages.Menu Setting Close') }}</button>
                    <button type="button" class="btn btn-primary btn-shadow waves-effect waves-themed" id="submit_create_menu">{{ __('messages.Menu Setting Save') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
