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
                    <input type="text" name="established_year" id="established_year" class="form-control">
                    <span class="text-danger message-danger" id="message_established_year" role="alert"></span>
                </div>
            </div>
        
            <!-- Total Employee -->
            <div class="row mb-4">
                <label class="col-md-3" for="total_employee">@lang('messages.Total Employee') <span class="text-danger" role="alert">*</span></label>
                <div class="col-md-9">
                    <input type="number" name="total_employee" id="total_employee" class="form-control">
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
            
            {{-- <div class="input-group mb-4">
                <div class="col-md-6">
                    <label for="stamp_file">Stempel *</label>
                    <input type="file" name="stamp_file" id="stamp_file" class="form-control">
                    <span class="text-danger mt-2" id="message_stamp_file" role="alert"></span>
                </div>
                <div class="col-md-6">
                    <label for="signature_file">Tanda Tangan *</label>
                    <input type="file" name="signature_file" id="signature_file" class="form-control">
                    <span class="text-danger mt-2" id="message_signature_file" role="alert"></span>
                </div>
            </div> --}}
            
            <div class="company_address_additional" id="company_address_additional">
                <fieldset class="border px-2 mb-4">
                    <legend class="float-none w-auto text-bold">Data Alamat</legend>
                    <div class="row">
                        <div class="input-group mb-4">
                            <div class="col-md-3">
                                <label>@lang('messages.Company Address (according to NPWP)') <span class="text-danger" role="alert">*</span></label>
                            </div>
                            <div class="col-md-9">
                                <input type="text" name="address[]" id="address_0" class="form-control">
                                <span class="text-danger mt-2 message_address" id="message_address_0" role="alert"></span>
                            </div>
                        </div>
                    
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="city_0">@lang('messages.City') <span class="text-danger" role="alert">*</span></label>
                                <input type="text" name="city[]" id="city_0" class="form-control">
                                <span class="text-danger mt-2 message_city" id="message_city_0" role="alert"></span>
                            </div>
                    
                            <div class="col-md-6">
                                <label for="country_0">@lang('messages.Country') <span class="text-danger" role="alert">*</span></label>
                                <input type="text" name="country[]" id="country_0" class="form-control">
                                <span class="text-danger mt-2 message_country" id="message_country_0" role="alert"></span>
                            </div>
                        </div>
                    
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="province_0">@lang('messages.Province') <span class="text-danger" role="alert">*</span></label>
                                <input type="text" name="province[]" id="province_0" class="form-control">
                                <span class="text-danger mt-2 message_province" id="message_province_0" role="alert"></span>
                            </div>
                    
                            <div class="col-md-6">
                                <label for="zip_code_0">@lang('messages.Postal Code') <span class="text-danger" role="alert">*</span></label>
                                <input type="text" name="zip_code[]" id="zip_code_0" class="form-control">
                                <span class="text-danger mt-2 message_zip_code" id="message_zip_code_0" role="alert"></span>
                            </div>
                        </div>
                    
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="telephone_0">@lang('messages.Telephone') <span class="text-danger" role="alert">*</span></label>
                                <p class="fs-6 text-muted mb-2">@lang('messages.Telephone Info')</p>
                                <input type="number" name="telephone[]" id="telephone_0" class="form-control">
                                <span class="text-danger mt-2 message_telephone" id="message_telephone_0" role="alert"></span>
                            </div>
                    
                            <div class="col-md-6">
                                <label for="fax_0">@lang('messages.Fax') <span class="text-danger" role="alert">*</span></label>
                                <p class="fs-6 text-muted mb-2">@lang('messages.Fax Info')</p>
                                <input type="number" name="fax[]" id="fax_0" class="form-control">
                                <span class="text-danger mt-2 message_fax" id="message_fax_0" role="alert"></span>
                            </div>
                        </div>
                    </div>
                </fieldset>
                <fieldset class="border px-2 mb-4">
                    <legend class="float-none w-auto text-bold">Data Alamat</legend>
                    <div class="row">
                        <div class="input-group mb-4">
                            <div class="col-md-3">
                                <label>@lang('messages.Company Address (Other)') *</label>
                            </div>
                            <div class="col-md-9">
                                <input type="text" name="address[]" id="address_1" class="form-control" placeholder="ex: Jl. HM Ashari No. 47 001/001 Cibinong">
                                <span class="text-muted mt-2 message_address" id="message_address_1" role="alert">@lang('messages.Format Address')</span>
                                {{-- <span class="text-muted mt-2 message_address" id="message_address_1" role="alert">Contoh : Jl. HM Ashari No. 47 001/001 Cibinong</span> --}}
                                <span class="text-danger mt-2 message_address" id="message_address_1" role="alert"></span>
                            </div>
                        </div>
                    
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="city_1">@lang('messages.City') *</label>
                                <input type="text" name="city[]" id="city_1" class="form-control">
                                <span class="text-danger mt-2 message_city" id="message_city_1" role="alert"></span>
                            </div>
                    
                            <div class="col-md-6">
                                <label for="country_1">@lang('messages.Country') *</label>
                                <input type="text" name="country[]" id="country_1" class="form-control">
                                <span class="text-danger mt-2 message_country" id="message_country_1" role="alert"></span>
                            </div>
                        </div>
                    
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="province_1">@lang('messages.Province') *</label>
                                <input type="text" name="province[]" id="province_1" class="form-control">
                                <span class="text-danger mt-2 message_province" id="message_province_1" role="alert"></span>
                            </div>
                    
                            <div class="col-md-6">
                                <label for="zip_code_1">@lang('messages.Postal Code') *</label>
                                <input type="text" name="zip_code[]" id="zip_code_1" class="form-control">
                                <span class="text-danger mt-2 message_zip_code" id="message_zip_code_1" role="alert"></span>
                            </div>
                        </div>
                    
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="telephone_1">@lang('messages.Telephone') *</label>
                                <p class="fs-6 text-muted mb-2">@lang('messages.Telephone Info')</p>
                                <input type="number" name="telephone[]" id="telephone_1" class="form-control">
                                <span class="text-danger mt-2 message_telephone" id="message_telephone_1" role="alert"></span>
                            </div>
                    
                            <div class="col-md-6">
                                <label for="fax_1">@lang('messages.Fax') *</label>
                                {{-- opsional --}}
                                <p class="fs-6 text-muted mb-2">@lang('messages.Fax Info')</p>
                                <input type="number" name="fax[]" id="fax_1" class="form-control">
                                <span class="text-danger mt-2 message_fax" id="message_fax_1" role="alert"></span>
                            </div>
                        </div>
                    </div>
                </fieldset>
            
                {{-- <button type="button" class="btn btn-primary float-right" id="add_dynamic_address">+ Address</button> --}}
                <div class="dynamic_company_address">

                </div>
            </div>
            
        </div>
        
    </div>
</div>