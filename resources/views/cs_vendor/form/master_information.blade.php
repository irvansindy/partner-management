<div class="card card-info">
    <div class="card-header">
        <h3 class="card-title">
            @lang('messages.Company Information')
        </h3>
        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="container-fluid">
            <p class="mb-3 text-danger">@lang('messages.Mandatory')</p>
            <!-- Company Type -->
            <div class="row mb-4">
                <label class="col-md-3" for="company_type">@lang('messages.Company Type') <span class="text-danger" role="alert">*</span></label>
                <div class="col-md-9">
                    <select name="company_type" id="company_type" class="form-control">
                        <option value="">@lang('messages.Select One')</option>
                        <option value="customer">@lang('messages.Customer')</option>
                        <option value="vendor">@lang('messages.Vendor')</option>
                    </select>
                    <span class="text-danger message-danger" id="message_company_type" role="alert"></span>
                    <div class="form-check form-switch mt-2 switch-customer" style="display: none;">
                        <input class="form-check-input" type="checkbox" role="switch" id="switch-customer">
                        <label class="form-check-label" for="switch-customer">@lang('messages.Form Income Statement')</label>
                    </div>
                </div>
            </div>
        
            <!-- Company Name -->
            <div class="row mb-4">
                <label class="col-md-3" for="company_name">@lang('messages.Company Name') <span class="text-danger" role="alert">*</span></label>
                <div class="col-md-9">
                    <input type="text" name="company_name" id="company_name" class="form-control">
                    <span class="text-danger message-danger" id="message_company_name" role="alert"></span>
                </div>
            </div>
        
            <!-- Company Group Name & Established Year -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <label for="company_group_name">@lang('messages.Company Group Name') <span class="text-danger" role="alert">*</span></label>
                    <input type="text" name="company_group_name" id="company_group_name" class="form-control">
                    <span class="text-muted" id="message_company_group_name" role="alert">@lang('messages.Company Group Name Info')</span>
                    <span class="text-danger message-danger" id="message_company_group_name" role="alert"></span>
                </div>
                <div class="col-md-6">
                    <label for="established_year">@lang('messages.Established Since') <span class="text-danger" role="alert">*</span></label>
                    <input type="text" name="established_year" id="established_year" class="form-control" maxlength="4" pattern="[0-9]{4}" placeholder="YYYY">
                    <span class="text-danger message-danger" id="message_established_year" role="alert"></span>
                </div>
            </div>
        
            <!-- Total Employee -->
            <div class="row mb-4">
                <label class="col-md-3" for="total_employee">@lang('messages.Total Employee') <span class="text-danger" role="alert">*</span></label>
                <div class="col-md-9">
                    <input type="number" name="total_employee" id="total_employee" class="form-control" min="1" max="9999" maxlength="10" placeholder="">
                    <span class="text-danger message-danger" id="message_total_employee" role="alert"></span>
                </div>
            </div>
        
            <!-- Liable Person & Owner Name -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <label for="liable_person_and_position">@lang('messages.Liable Person') <span class="text-danger" role="alert">*</span></label>
                    <input type="text" name="liable_person_and_position" id="liable_person_and_position" class="form-control">
                    <span class="text-danger message-danger" id="message_liable_person_and_position" role="alert"></span>
                </div>
                <div class="col-md-4">
                    <label for="liable_position">@lang('messages.Liable Position') <span class="text-danger" role="alert">*</span></label>
                    <input type="text" name="liable_position" id="liable_position" class="form-control" placeholder="">
                    <span class="text-danger message-danger" id="message_liable_position" role="alert"></span>
                </div>
                <div class="col-md-4">
                    <label for="owner_name">@lang('messages.Owner’s Name') <span class="text-danger" role="alert">*</span></label>
                    <input type="text" name="owner_name" id="owner_name" class="form-control">
                    <span class="text-danger message-danger" id="message_owner_name" role="alert"></span>
                </div>
            </div>
        
            <!-- Board of Directors & Major Shareholders -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <label for="board_of_directors">@lang('messages.Board of Directors') <span class="text-danger" role="alert">*</span></label>
                    <input type="text" name="board_of_directors" id="board_of_directors" class="form-control">
                    <span class="text-danger message-danger" id="message_board_of_directors" role="alert"></span>
                </div>
                <div class="col-md-6">
                    <label for="major_shareholders">@lang('messages.Major Shareholders') <span class="text-danger" role="alert">*</span></label>
                    <input type="text" name="major_shareholders" id="major_shareholders" class="form-control">
                    <span class="text-danger message-danger" id="message_major_shareholders" role="alert"></span>
                </div>
            </div>
        
            <!-- Business Classification -->
            <div class="row mb-4">
                <label class="col-md-3">@lang('messages.Business Classification') <span class="text-danger" role="alert">*</span></label>
                <div class="col-md-9">
                    <select class="form-select" name="business_classification" id="business_classification">
                        <option value="">-- Select Business Classification --</option>
                        @foreach (\App\Models\MasterBusinessClassification::all() as $item)
                            <option value="{{ $item->name }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                    
                    <!-- field tambahan muncul kalau pilih "Other" -->
                    <div class="col-md-auto my-2" id="field_form_create_business_other"></div>
                    
                    <span class="text-danger message-danger" id="message_business_classification" role="alert"></span>
                </div>

            </div>
        
            <!-- Business Classification Detail -->
            <div class="row mb-4">
                <label class="col-md-3" for="business_classification_detail">@lang('messages.Business Classification Detail') <span class="text-danger" role="alert">*</span></label>
                <div class="col-md-9">
                    <textarea class="form-control" name="business_classification_detail" id="business_classification_detail" rows="4"></textarea>
                    <span class="text-danger message-danger" id="message_business_classification_detail" role="alert"></span>
                </div>
            </div>
        
            <!-- Website Address -->
            <div class="row mb-4">
                <label class="col-md-3" for="website_address">@lang('messages.Website Address') <span class="text-danger" role="alert">*</span></label>
                <div class="col-md-9">
                    <input type="text" name="website_address" id="website_address" class="form-control">
                    <span class="text-danger message-danger" id="message_website_address" role="alert"></span>
                </div>
            </div>
            
            <div class="input-group mb-4">
                <div class="col-md-3">
                    <label for="system_management">@lang('messages.Certificate Type') <span class="text-danger" role="alert">*</span></label>
                </div>
                <div class="col-md-9">
                    <input type="text" name="system_management" id="system_management" class="form-control" placeholder="ex: ISO 9001, ISO 14001, ISO 45001, SMK3">
                    <span class="text-danger mt-2" id="message_system_management" role="alert"></span>
                </div>
            </div>
            
            <div class="input-group mb-4">
                <div class="col-md-6">
                    <label for="contact_person">@lang('messages.Contact Person') <span class="text-danger" role="alert">*</span></label>
                    <input type="text" name="contact_person" id="contact_person" class="form-control">
                    <span class="text-danger mt-2" id="message_contact_person" role="alert"></span>
                </div>
                <div class="col-md-6">
                    <label>@lang('messages.Communication Language') <span class="text-danger" role="alert">*</span></label>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="communication_language" id="communication_language_bahasa" value="Bahasa">
                                <label class="form-check-label" for="communication_language_bahasa">Bahasa</label>
                                <p class="fs-6 text-muted mb-0">Indonesia</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="communication_language" id="communication_language_english" value="English">
                                <label class="form-check-label" for="communication_language_english">English</label>
                                <p class="fs-6 text-muted mb-0">Inggris</p>
                            </div>
                        </div>
                    </div>
                    <span class="text-danger mt-2" id="message_communication_language" role="alert"></span>
                </div>
            </div>
            
            <div class="input-group mb-4">
                <div class="col-md-3">
                    <label for="email_address">@lang('messages.Email Address') <span class="text-danger" role="alert">*</span></label>
                </div>
                <div class="col-md-9">
                    <input type="text" name="email_address" id="email_address" class="form-control">
                    <span class="text-danger mt-2" id="message_email_address" role="alert"></span>
                </div>
            </div>            
        </div>
        
    </div>
</div>