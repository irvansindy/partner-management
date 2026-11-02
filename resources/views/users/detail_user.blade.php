<div class="modal fade user-management-modal user-form-modal" id="formUpdateUser" data-backdrop="static" data-keyboard="false"
    tabindex="-1" role="dialog" aria-labelledby="formUpdateUserLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <span class="user-modal-kicker">USER ACCOUNT</span>
                    <h2 class="modal-title" id="formUpdateUserLabel">Update User</h2>
                    <p class="user-modal-description">Review account details and organizational assignment.</p>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form id="form_detail_current_user" novalidate>
                <div class="modal-body">
                    <section class="user-form-section" aria-labelledby="update_account_heading">
                        <div class="user-form-section-heading">
                            <h3 id="update_account_heading">Account information</h3>
                            <p>Keep the user's identity and contact information accurate.</p>
                        </div>
                        <div class="user-form-grid">
                            <div class="form-group">
                                <label for="update_user_name">Name <span class="required-mark">*</span></label>
                                <input type="text" class="form-control" name="update_user_name" id="update_user_name"
                                    placeholder="Enter full name" autocomplete="name" required>
                                <span class="user-field-error" id="message_update_user_name" role="alert"></span>
                            </div>
                            <div class="form-group">
                                <label for="update_user_email">Email <span class="required-mark">*</span></label>
                                <input type="email" class="form-control" name="update_user_email" id="update_user_email"
                                    placeholder="name@company.com" autocomplete="email" required>
                                <span class="user-field-error" id="message_update_user_email" role="alert"></span>
                            </div>
                            <div class="form-group">
                                <label for="update_user_nik">NIK <span class="required-mark">*</span></label>
                                <input type="text" class="form-control" name="update_user_nik" id="update_user_nik"
                                    placeholder="Enter NIK" inputmode="numeric" autocomplete="off" required>
                                <span class="user-field-error" id="message_update_user_nik" role="alert"></span>
                            </div>
                            <div class="form-group">
                                <label for="update_user_employee_id">Employee ID <span class="required-mark">*</span></label>
                                <input type="text" class="form-control" name="update_user_employee_id"
                                    id="update_user_employee_id" placeholder="Enter employee ID" autocomplete="off" required>
                                <span class="user-field-error" id="message_update_user_employee_id" role="alert"></span>
                            </div>
                        </div>
                    </section>

                    <section class="user-form-section" aria-labelledby="update_organization_heading">
                        <div class="user-form-section-heading">
                            <h3 id="update_organization_heading">Organization and access</h3>
                            <p>Update the access level, work location, and reporting structure.</p>
                        </div>
                        <div class="user-form-grid">
                            <div class="form-group">
                                <input type="hidden" name="update_current_role" id="update_current_role">
                                <label for="update_user_role">Role <span class="required-mark">*</span></label>
                                <select class="form-control" name="update_user_role" id="update_user_role" required></select>
                                <span class="user-field-error" id="message_update_user_role" role="alert"></span>
                            </div>
                            <div class="form-group">
                                <label for="update_user_office">Office <span class="required-mark">*</span></label>
                                <select class="form-control" name="update_user_office" id="update_user_office" required></select>
                                <span class="user-field-error" id="message_update_user_office" role="alert"></span>
                            </div>
                            <div class="form-group">
                                <label for="update_user_division">Division <span class="required-mark">*</span></label>
                                <select class="form-control" name="update_user_division" id="update_user_division" required></select>
                                <span class="user-field-error" id="message_update_user_division" role="alert"></span>
                            </div>
                            <div class="form-group">
                                <label for="update_user_department">Department <span class="required-mark">*</span></label>
                                <select class="form-control" name="update_user_department" id="update_user_department" required></select>
                                <span class="user-field-error" id="message_update_user_department" role="alert"></span>
                            </div>
                            <div class="form-group">
                                <label for="update_user_job_title">Job Title <span class="optional-label">Optional</span></label>
                                <select class="form-control" name="update_user_job_title" id="update_user_job_title"></select>
                                <span class="user-field-error" id="message_update_user_job_title" role="alert"></span>
                            </div>
                            <div class="form-group">
                                <label for="update_user_parent">Parent / Supervisor <span class="optional-label">Optional</span></label>
                                <select class="form-control" name="update_user_parent" id="update_user_parent">
                                    <option value="">Select supervisor</option>
                                </select>
                                <span class="user-field-error" id="message_update_user_parent" role="alert"></span>
                            </div>
                        </div>
                    </section>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary update_user" id="update_user" data-update_user_id="">
                        <i class="material-icons" aria-hidden="true">save</i>
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
