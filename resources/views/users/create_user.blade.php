<div class="modal fade user-management-modal user-form-modal" id="formCreateUser" data-backdrop="static" data-keyboard="false"
    tabindex="-1" role="dialog" aria-labelledby="formCreateUserLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <span class="user-modal-kicker">{{ __('messages.User Management Account') }}</span>
                    <h2 class="modal-title" id="formCreateUserLabel">{{ __('messages.User Management Create User') }}</h2>
                    <p class="user-modal-description">{{ __('messages.User Management Create Description') }}</p>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('messages.User Management Close') }}">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form id="form_create_new_user" novalidate>
                <div class="modal-body">
                    <section class="user-form-section" aria-labelledby="create_account_heading">
                        <div class="user-form-section-heading">
                            <h3 id="create_account_heading">{{ __('messages.User Management Account Information') }}</h3>
                            <p>{{ __('messages.User Management Create Account Description') }}</p>
                        </div>
                        <div class="user-form-grid">
                            <div class="form-group">
                                <label for="user_name">{{ __('messages.User Management Full Name') }} <span class="required-mark">*</span></label>
                                <input type="text" class="form-control" name="user_name" id="user_name"
                                    placeholder="{{ __('messages.User Management Enter Full Name') }}" autocomplete="name" required>
                                <span class="user-field-error" id="message_user_name" role="alert"></span>
                            </div>
                            <div class="form-group">
                                <label for="user_email">{{ __('messages.User Management Email') }} <span class="required-mark">*</span></label>
                                <input type="email" class="form-control" name="user_email" id="user_email"
                                    placeholder="name@company.com" autocomplete="email" required>
                                <span class="user-field-error" id="message_user_email" role="alert"></span>
                            </div>
                            <div class="form-group">
                                <label for="user_nik">{{ __('messages.User Management NIK') }} <span class="required-mark">*</span></label>
                                <input type="text" class="form-control" name="user_nik" id="user_nik"
                                    placeholder="{{ __('messages.User Management Enter NIK') }}" inputmode="numeric" autocomplete="off" required>
                                <span class="user-field-error" id="message_user_nik" role="alert"></span>
                            </div>
                            <div class="form-group">
                                <label for="user_employee_id">{{ __('messages.User Management Employee ID') }} <span class="required-mark">*</span></label>
                                <input type="text" class="form-control" name="user_employee_id" id="user_employee_id"
                                    placeholder="{{ __('messages.User Management Enter Employee ID') }}" autocomplete="off" required>
                                <span class="user-field-error" id="message_user_employee_id" role="alert"></span>
                            </div>
                        </div>
                    </section>

                    <section class="user-form-section" aria-labelledby="create_organization_heading">
                        <div class="user-form-section-heading">
                            <h3 id="create_organization_heading">{{ __('messages.User Management Organization Access') }}</h3>
                            <p>{{ __('messages.User Management Create Organization Description') }}</p>
                        </div>
                        <div class="user-form-grid">
                            <div class="form-group">
                                <label for="user_role">{{ __('messages.User Management Role') }} <span class="required-mark">*</span></label>
                                <select class="form-control" name="user_role" id="user_role" required></select>
                                <span class="user-field-error" id="message_user_role" role="alert"></span>
                            </div>
                            <div class="form-group">
                                <label for="user_office">{{ __('messages.User Management Office') }} <span class="required-mark">*</span></label>
                                <select class="form-control" name="user_office" id="user_office" required></select>
                                <span class="user-field-error" id="message_user_office" role="alert"></span>
                            </div>
                            <div class="form-group">
                                <label for="user_division">{{ __('messages.User Management Division') }} <span class="required-mark">*</span></label>
                                <select class="form-control" name="user_division" id="user_division" required></select>
                                <span class="user-field-error" id="message_user_division" role="alert"></span>
                            </div>
                            <div class="form-group">
                                <label for="user_department">{{ __('messages.User Management Department') }} <span class="required-mark">*</span></label>
                                <select class="form-control" name="user_department" id="user_department" required></select>
                                <span class="user-field-error" id="message_user_department" role="alert"></span>
                            </div>
                            <div class="form-group">
                                <label for="user_job_title">{{ __('messages.User Management Job Title') }} <span class="optional-label">{{ __('messages.User Management Optional') }}</span></label>
                                <select class="form-control" name="user_job_title" id="user_job_title"></select>
                                <span class="user-field-error" id="message_user_job_title" role="alert"></span>
                            </div>
                            <div class="form-group">
                                <label for="user_parent">{{ __('messages.User Management Supervisor') }} <span class="optional-label">{{ __('messages.User Management Optional') }}</span></label>
                                <select class="form-control" name="user_parent" id="user_parent">
                                    <option value="">{{ __('messages.User Management Select Supervisor') }}</option>
                                </select>
                                <span class="user-field-error" id="message_user_parent" role="alert"></span>
                            </div>
                        </div>
                    </section>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">{{ __('messages.User Management Cancel') }}</button>
                    <button type="submit" class="btn btn-primary" id="save_user">
                        <i class="material-icons" aria-hidden="true">save</i>
                        {{ __('messages.User Management Save User') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
