<!-- Modal -->
<div class="modal fade" id="formDepartment" data-backdrop="static" data-keyboard="false" tabindex="-1"  role="dialog" aria-labelledby="formDepartmentLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="formDepartmentLabel"></h1>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" id="form_data_department">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="department_name">Name</label>
                        <input type="hidden" class="form-control" name="department_id" id="department_id">
                        <input type="text" class="form-control" name="department_name" id="department_name">
                        <span class="text-danger mt-2 message_department_name" id="message_department_name" role="alert"></span>
                    </div>
                </div>
                <div class="modal-footer" id="button_action_department">
                    
                </div>
            </form>
        </div>
    </div>
</div>
