<div class="card card-info">
    <div class="card-header">
        <h3 class="card-title">COMPANY INFORMATION (Informasi Perusahaan)</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="container-fluid">
            <!-- Company Type -->
            <div class="row mb-4">
                <label class="col-md-3" for="company_type">Company Type *</label>
                <div class="col-md-9">
                    <select name="company_type" id="company_type" class="form-control">
                        <option value="">Pilih salah satu</option>
                        <option value="customer">Customer</option>
                        <option value="vendor">Vendor</option>
                    </select>
                    <span class="text-danger" id="message_company_type" role="alert"></span>
                    <div class="form-check form-switch mt-2 switch-customer" style="display: none;">
                        <input class="form-check-input" type="checkbox" role="switch" id="switch-customer">
                        <label class="form-check-label" for="switch-customer">Form Income Statement</label>
                    </div>
                </div>
            </div>
        
            <!-- Company Name -->
            <div class="row mb-4">
                <label class="col-md-3" for="company_name">Company Name *</label>
                <div class="col-md-9">
                    <input type="text" name="company_name" id="company_name" class="form-control">
                    <span class="text-danger" id="message_company_name" role="alert"></span>
                </div>
            </div>
        
            <!-- Company Group Name & Established Year -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <label for="company_group_name">Company Group Name *</label>
                    <input type="text" name="company_group_name" id="company_group_name" class="form-control">
                    <span class="text-danger" id="message_company_group_name" role="alert"></span>
                </div>
                <div class="col-md-6">
                    <label for="established_year">Established Since (Year) *</label>
                    <input type="text" name="established_year" id="established_year" class="form-control">
                    <span class="text-danger" id="message_established_year" role="alert"></span>
                </div>
            </div>
        
            <!-- Total Employee -->
            <div class="row mb-4">
                <label class="col-md-3" for="total_employee">Total Employee *</label>
                <div class="col-md-9">
                    <input type="number" name="total_employee" id="total_employee" class="form-control">
                    <span class="text-danger" id="message_total_employee" role="alert"></span>
                </div>
            </div>
        
            <!-- Liable Person & Owner Name -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <label for="liable_person_and_position">Liable Person & Position *</label>
                    <input type="text" name="liable_person_and_position" id="liable_person_and_position" class="form-control">
                    <span class="text-danger" id="message_liable_person_and_position" role="alert"></span>
                </div>
                <div class="col-md-6">
                    <label for="owner_name">Owner’s Name *</label>
                    <input type="text" name="owner_name" id="owner_name" class="form-control">
                    <span class="text-danger" id="message_owner_name" role="alert"></span>
                </div>
            </div>
        
            <!-- Board of Directors & Major Shareholders -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <label for="board_of_directors">Board of Directors *</label>
                    <input type="text" name="board_of_directors" id="board_of_directors" class="form-control">
                    <span class="text-danger" id="message_board_of_directors" role="alert"></span>
                </div>
                <div class="col-md-6">
                    <label for="major_shareholders">Major Shareholders *</label>
                    <input type="text" name="major_shareholders" id="major_shareholders" class="form-control">
                    <span class="text-danger" id="message_major_shareholders" role="alert"></span>
                </div>
            </div>
        
            <!-- Business Classification -->
            <div class="row mb-4">
                <label class="col-md-3">Business Classification *</label>
                <div class="col-md-9">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="business_classification" id="business_classification_manufacturer" value="Manufacturer">
                        <label class="form-check-label" for="business_classification_manufacturer">Manufacturer</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="business_classification" id="business_classification_trading" value="Trading">
                        <label class="form-check-label" for="business_classification_trading">Trading</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="business_classification" id="business_classification_agent" value="Agent">
                        <label class="form-check-label" for="business_classification_agent">Agent</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="business_classification" id="business_classification_distributor" value="Distributor">
                        <label class="form-check-label" for="business_classification_distributor">Distributor</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="business_classification" id="business_classification_services" value="Services">
                        <label class="form-check-label" for="business_classification_services">Services</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="business_classification" id="business_classification_contractor" value="Contractor">
                        <label class="form-check-label" for="business_classification_contractor">Contractor</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="business_classification" id="business_classification_other" value="Other">
                        <label class="form-check-label" for="business_classification_other">Other</label>
                    </div>
                    <div class="col-md-auto my-2" id="field_form_create_business_other"></div>
                    <span class="text-danger" id="message_business_classification" role="alert"></span>
                </div>
            </div>
        
            <!-- Business Classification Detail -->
            <div class="row mb-4">
                <label class="col-md-3" for="business_classification_detail">Business Classification Detail *</label>
                <div class="col-md-9">
                    <textarea class="form-control" name="business_classification_detail" id="business_classification_detail" rows="4"></textarea>
                    <span class="text-danger" id="message_business_classification_detail" role="alert"></span>
                </div>
            </div>
        
            <!-- Website Address -->
            <div class="row mb-4">
                <label class="col-md-3" for="website_address">Website Address *</label>
                <div class="col-md-9">
                    <input type="text" name="website_address" id="website_address" class="form-control">
                    <span class="text-danger" id="message_website_address" role="alert"></span>
                </div>
            </div>
            
            <div class="input-group mb-4">
                <div class="col-md-3">
                    <label>Jenis Usaha *</label>
                </div>
                <div class="col-md-9">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="system_management" id="system_management_iso" value="ISO">
                        <label class="form-check-label" for="system_management_iso">ISO</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="system_management" id="system_management_smk3" value="SMK3">
                        <label class="form-check-label" for="system_management_smk3">SMK3</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="system_management" id="system_management_other" value="Others Certificate">
                        <label class="form-check-label" for="system_management_other">Sertifikat lainnya</label>
                    </div>
                    <span class="text-danger mt-2" id="message_system_management" role="alert"></span>
                </div>
            </div>
            
            <div class="input-group mb-4">
                <div class="col-md-6">
                    <label for="contact_person">Kontak Person *</label>
                    <input type="text" name="contact_person" id="contact_person" class="form-control">
                    <span class="text-danger mt-2" id="message_contact_person" role="alert"></span>
                </div>
                <div class="col-md-6">
                    <label>Bahasa Komunikasi *</label>
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
                    <label for="email_address">Alamat email (Koresponden) *</label>
                </div>
                <div class="col-md-9">
                    <input type="text" name="email_address" id="email_address" class="form-control">
                    <span class="text-danger mt-2" id="message_email_address" role="alert"></span>
                </div>
            </div>
            
            <div class="input-group mb-4">
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
            </div>
            
            <div class="company_address_additional" id="company_address_additional">
                <fieldset class="border px-2 mb-4">
                    <legend class="float-none w-auto text-bold">Data Alamat</legend>
                    <div class="row">
                        <div class="input-group mb-4">
                            <div class="col-md-3">
                                <label>Alamat Perusahaan (sesuai dengan NPWP) *</label>
                            </div>
                            <div class="col-md-9">
                                <input type="text" name="address[]" id="address" class="form-control">
                                <span class="text-danger mt-2 message_address" id="message_address_0" role="alert"></span>
                            </div>
                        </div>
                    
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="city">Kota *</label>
                                <input type="text" name="city[]" id="city" class="form-control">
                                <span class="text-danger mt-2 message_city" id="message_city_0" role="alert"></span>
                            </div>
                    
                            <div class="col-md-6">
                                <label for="country">Negara *</label>
                                <input type="text" name="country[]" id="country" class="form-control">
                                <span class="text-danger mt-2 message_country" id="message_country_0" role="alert"></span>
                            </div>
                        </div>
                    
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="province">Provinsi *</label>
                                <input type="text" name="province[]" id="province" class="form-control">
                                <span class="text-danger mt-2 message_province" id="message_province_0" role="alert"></span>
                            </div>
                    
                            <div class="col-md-6">
                                <label for="zip_code">Kode Pos *</label>
                                <input type="text" name="zip_code[]" id="zip_code" class="form-control">
                                <span class="text-danger mt-2 message_zip_code" id="message_zip_code_0" role="alert"></span>
                            </div>
                        </div>
                    
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="telephone">Telephone *</label>
                                <p class="fs-6 text-muted mb-2">+ [Country-Area Code] [No.]</p>
                                <input type="number" name="telephone[]" id="telephone" class="form-control">
                                <span class="text-danger mt-2 message_telephone" id="message_telephone_0" role="alert"></span>
                            </div>
                    
                            <div class="col-md-6">
                                <label for="fax">Fax *</label>
                                <p class="fs-6 text-muted mb-2">+ [Country-Area Code] [No.]</p>
                                <input type="number" name="fax[]" id="fax" class="form-control">
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
                                <label>Alamat Perusahaan (sesuai dengan NPWP) *</label>
                            </div>
                            <div class="col-md-9">
                                <input type="text" name="address[]" id="address" class="form-control">
                                <span class="text-danger mt-2 message_address" id="message_address_0" role="alert"></span>
                            </div>
                        </div>
                    
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="city">Kota *</label>
                                <input type="text" name="city[]" id="city" class="form-control">
                                <span class="text-danger mt-2 message_city" id="message_city_0" role="alert"></span>
                            </div>
                    
                            <div class="col-md-6">
                                <label for="country">Negara *</label>
                                <input type="text" name="country[]" id="country" class="form-control">
                                <span class="text-danger mt-2 message_country" id="message_country_0" role="alert"></span>
                            </div>
                        </div>
                    
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="province">Provinsi *</label>
                                <input type="text" name="province[]" id="province" class="form-control">
                                <span class="text-danger mt-2 message_province" id="message_province_0" role="alert"></span>
                            </div>
                    
                            <div class="col-md-6">
                                <label for="zip_code">Kode Pos *</label>
                                <input type="text" name="zip_code[]" id="zip_code" class="form-control">
                                <span class="text-danger mt-2 message_zip_code" id="message_zip_code_0" role="alert"></span>
                            </div>
                        </div>
                    
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="telephone">Telephone *</label>
                                <p class="fs-6 text-muted mb-2">+ [Country-Area Code] [No.]</p>
                                <input type="number" name="telephone[]" id="telephone" class="form-control">
                                <span class="text-danger mt-2 message_telephone" id="message_telephone_0" role="alert"></span>
                            </div>
                    
                            <div class="col-md-6">
                                <label for="fax">Fax *</label>
                                <p class="fs-6 text-muted mb-2">+ [Country-Area Code] [No.]</p>
                                <input type="number" name="fax[]" id="fax" class="form-control">
                                <span class="text-danger mt-2 message_fax" id="message_fax_0" role="alert"></span>
                            </div>
                        </div>
                    </div>
                </fieldset>
            
                <button type="button" class="btn btn-primary float-right" id="add_dynamic_address">+ Address</button>
                <div class="dynamic_company_address">

                </div>
            </div>
            
        </div>
        
    </div>
</div>