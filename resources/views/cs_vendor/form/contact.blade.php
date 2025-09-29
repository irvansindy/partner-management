<div class="card card-info">
    <div class="card-header">
        <h3 class="card-title">
            @lang('messages.Contact Person')
        </h3>
        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="row px-2 py-2" style="line-height: 1">
            <div class="company_contact_additional" id="company_contact_additional">
                <fieldset class="border px-2 mb-4">
                    <legend class="float-none w-auto text-bold">@lang('messages.Contact Person')</legend>
                    <div class="row mt-4">
                        <div class="col-md-auto col-lg-auto col-sm-12 mb-3">
                            <label for="contact_department_0">@lang('messages.Department') *</label>
                            <input type="text" name="contact_department[]" id="contact_department_0" class="form-control">
                            <span class="text-danger mt-2" id="message_contact_department" role="alert"></span>
                        </div>
                        <div class="col-md-auto col-lg-auto col-sm-12 mb-3">
                            <label for="contact_position_0">@lang('messages.Position') *</label>
                            <input type="text" name="contact_position[]" id="contact_position_0" class="form-control">
                            <span class="text-danger mt-2" id="message_contact_position" role="alert"></span>
                        </div>
                        <div class="col-md-auto col-lg-auto col-sm-12 mb-3">
                            <label for="contact_name_0">@lang('messages.Name') *</label>
                            <input type="text" name="contact_name[]" id="contact_name_0" class="form-control">
                            <span class="text-danger mt-2" id="message_contact_name" role="alert"></span>
                        </div>
                        <div class="col-md-auto col-lg-auto col-sm-12 mb-3">
                            <label for="contact_email_0">@lang('messages.Email') *</label>
                            <input type="text" name="contact_email[]" id="contact_email_0" class="form-control">
                            <span class="text-danger mt-2" id="message_contact_email" role="alert"></span>
                        </div>
                        <div class="col-md-auto col-lg-auto col-sm-12 mb-3">
                            <label for="contact_telephone_0">@lang('messages.Telephone') *</label>
                            <input type="text" name="contact_telephone[]" id="contact_telephone_0" class="form-control">
                            <span class="text-danger mt-2" id="message_contact_telephone" role="alert"></span>
                        </div>
                        <div class="col-md-auto col-lg-auto col-sm-12 mb-3">
                            <div class="input-group d-flex justify-content-end mb-4 mt-4">
                                <button class="btn btn-primary" id="add_contact"><i class="fa fa-plus"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="dynamic_contact">
                        
                    </div>
                </fieldset>
            </div>
        </div>
    </div>
</div>