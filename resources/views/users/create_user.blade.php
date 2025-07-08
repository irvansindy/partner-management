<!-- Modal -->
<div class="modal fade" id="formCreateUser" data-backdrop="static" data-keyboard="false" tabindex="-1"  role="dialog" aria-labelledby="formCreateUser_label" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="formCreateUserLabel">Create User</h1>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" id="form_create_new_user">
                <div class="modal-body">
                    <!-- Name -->
                    <div class="form-group mb-3">
                        <label for="user_name">Name</label>
                        <input type="text" class="form-control" name="user_name" id="user_name">
                        <span class="text-danger mt-1 d-block" id="message_user_name"></span>
                    </div>

                    <!-- Email -->
                    <div class="form-group mb-3">
                        <label for="user_email">Email</label>
                        <input type="email" class="form-control" name="user_email" id="user_email">
                        <span class="text-danger mt-1 d-block" id="message_user_email"></span>
                    </div>

                    <!-- NIK -->
                    <div class="form-group mb-3">
                        <label for="user_nik">NIK</label>
                        <input type="text" class="form-control" name="user_nik" id="user_nik">
                        <span class="text-danger mt-1 d-block" id="message_user_nik"></span>
                    </div>

                    <!-- Employee ID -->
                    <div class="form-group mb-3">
                        <label for="user_employee_id">Employee ID</label>
                        <input type="text" class="form-control" name="user_employee_id" id="user_employee_id">
                        <span class="text-danger mt-1 d-block" id="message_user_employee_id"></span>
                    </div>

                    <!-- Role -->
                    <div class="form-group mb-3">
                        <label for="user_role">Role</label>
                        <select class="form-control" name="user_role" id="user_role" style="width: 100% !important;">
                            <!-- Data role akan diisi dari AJAX -->
                        </select>
                        <span class="text-danger mt-1 d-block" id="message_user_role"></span>
                    </div>

                    <!-- Job Title -->
                    <div class="form-group mb-3">
                        <label for="user_job_title">Job Title</label>
                        <select class="form-control" name="user_job_title" id="user_job_title" style="width: 100% !important;">
                            <!-- Data job title akan diisi dari AJAX -->
                        </select>
                        <span class="text-danger mt-1 d-block" id="message_user_job_title"></span>
                    </div>

                    <!-- Division -->
                    <div class="form-group mb-3">
                        <label for="user_division">Division</label>
                        <select class="form-control" name="user_division" id="user_division" style="width: 100% !important;">
                            <!-- Data division akan diisi dari AJAX -->
                        </select>
                        <span class="text-danger mt-1 d-block" id="message_user_division"></span>
                    </div>

                    <!-- Department -->
                    <div class="form-group mb-3">
                        <label for="user_department">Department</label>
                        <select class="form-control" name="user_department" id="user_department" style="width: 100% !important;">
                            <!-- Akan terisi otomatis saat division dipilih -->
                        </select>
                        <span class="text-danger mt-1 d-block" id="message_user_department"></span>
                    </div>

                    <!-- Office -->
                    <div class="form-group mb-3">
                        <label for="user_office">Office</label>
                        <select class="form-control" name="user_office" id="user_office" style="width: 100% !important;">
                            <!-- Data office akan diisi dari AJAX -->
                        </select>
                        <span class="text-danger mt-1 d-block" id="message_user_office"></span>
                    </div>

                    <!-- Parent User -->
                    <div class="form-group mb-3">
                        <label for="user_parent">Parent (Atasan)</label>
                        <select class="form-control" name="user_parent" id="user_parent" style="width: 100% !important;">
                            <option value="">-- Optional --</option>
                            <!-- Data parent user akan diisi dari AJAX -->
                        </select>
                        <span class="text-danger mt-1 d-block" id="message_user_parent"></span>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="save_user">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
