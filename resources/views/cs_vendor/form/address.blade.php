<div class="card card-info">
    <div class="card-header">
        <h3 class="card-title">
            @lang('messages.Address Data')
        </h3>
        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="row px-2 py-2" style="line-height: 1">
            <div class="company_address_additional" id="company_address_additional">
                <fieldset class="border px-2 mb-4">
                    <legend class="float-none w-auto text-bold">@lang('messages.Address Data')</legend>
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
                    <legend class="float-none w-auto text-bold">@lang('messages.Address Data')</legend>
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