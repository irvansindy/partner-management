<!-- Modal -->
<div class="modal fade" id="formUpdateUser" data-backdrop="static" data-keyboard="false" tabindex="-1"  role="dialog" aria-labelledby="formUpdateUser_label" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content ">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="formUpdateUserLabel">Detail User</h1>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" id="form_detail_current_user">
                <div class="modal-body row">
                    <div class="form-group mb-3 col-lg-6 col-md-6 col-sm-12">
                        <label for="update_user_name">Name</label>
                        <input type="text" class="form-control" name="update_user_name" id="update_user_name">
                        <span class="text-danger mt-2 message_update_user_name" id="message_update_user_name" role="alert"></span>
                    </div>
                    <div class="form-group mb-3 col-lg-6 col-md-6 col-sm-12">
                        <label for="update_user_email">Email</label>
                        <input type="email" class="form-control" name="update_user_email" id="update_user_email">
                        <span class="text-danger mt-2 message_update_user_email" id="message_update_user_email" role="alert"></span>
                    </div>
                    <div class="form-group mb-3 col-lg-6 col-md-6 col-sm-12">
                        <label for="update_user_nik">NIK</label>
                        <input type="text" class="form-control" name="update_user_nik" id="update_user_nik">
                        <span class="text-danger mt-2 message_update_user_nik" id="message_update_user_nik" role="alert"></span>
                    </div>

                    <div class="form-group mb-3 col-lg-6 col-md-6 col-sm-12">
                        <label for="update_user_employee_id">Employee ID</label>
                        <input type="text" class="form-control" name="update_user_employee_id" id="update_user_employee_id">
                        <span class="text-danger mt-2 message_update_user_employee_id" id="message_update_user_employee_id" role="alert"></span>
                    </div>
                    <div class="form-group mb-3 col-lg-6 col-md-6 col-sm-12">
                        <input type="hidden" class="form-control" name="update_current_role" id="update_current_role">
                        <label for="update_user_role">Role</label>
                        <select class="form-control" name="update_user_role" id="update_user_role" style="width: 100% !important;"></select>
                        <span class="text-danger mt-2 message_update_user_role" id="message_update_user_role" role="alert"></span>
                    </div>
                    <!-- Job Title -->
                    <div class="form-group mb-3 col-lg-6 col-md-6 col-sm-12 mb-3">
                        <label for="update_user_job_title">Job Title</label>
                        <select class="form-control" name="update_user_job_title" id="update_user_job_title" style="width: 100% !important;">
                            <!-- Data job title akan diisi dari AJAX -->
                        </select>
                        <span class="text-danger mt-1 d-block" id="message_update_user_job_title"></span>
                    </div>
                    <!-- Division -->
                    <div class="form-group mb-3 col-lg-6 col-md-6 col-sm-12 mb-3">
                        <label for="update_user_division">Division</label>
                        <select class="form-control" name="update_user_division" id="update_user_division" style="width: 100% !important;">
                            <!-- Data division akan diisi dari AJAX -->
                        </select>
                        <span class="text-danger mt-1 d-block" id="message_update_user_division"></span>
                    </div>
                    <div class="form-group mb-3 col-lg-6 col-md-6 col-sm-12">
                        <input type="hidden" class="form-control" name="update_current_department" id="update_current_department">
                        <label for="update_user_department">Department</label>
                        <select class="form-control" name="update_user_department" id="update_user_department" style="width: 100% !important;"></select>
                        <span class="text-danger mt-2 message_update_user_department" id="message_update_user_department" role="alert"></span>
                    </div>
                    <div class="form-group mb-3 col-lg-6 col-md-6 col-sm-12">
                        <input type="hidden" class="form-control" name="update_current_office" id="update_current_office">
                        <label for="update_user_office">Office</label>
                        <select class="form-control" name="update_user_office" id="update_user_office" style="width: 100% !important;"></select>
                        <span class="text-danger mt-2 message_update_user_office" id="message_update_user_office" role="alert"></span>
                    </div>
                    <!-- Parent User -->
                    <div class="form-group mb-3 col-lg-6 col-md-6 col-sm-12 mb-3">
                        <label for="update_user_parent">Parent (Atasan)</label>
                        <select class="form-control" name="update_user_parent" id="update_user_parent" style="width: 100% !important;">
                            <option value="">-- Optional --</option>
                            <!-- Data parent user akan diisi dari AJAX -->
                        </select>
                        <span class="text-danger mt-1 d-block" id="message_update_user_parent"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary update_user" id="update_user" data-update_user_id="">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
