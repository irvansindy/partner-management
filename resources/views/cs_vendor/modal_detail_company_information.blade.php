<!-- Modal -->
<div class="modal fade" id="dataDetailPartner" data-backdrop="static" tabindex="-1" role="dialog"
    aria-labelledby="dataDetailPartnerLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="dataDetailPartnerLabel">Detail Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <form action="" method="" id="form_detail_partner_data_by_user">
                        {{-- company information master --}}
                        <div class="card card-info">
                            <div class="card-header">
                                <input type="hidden" name="detail_id" id="detail_id">
                                <h3 class="card-title">1. COMPANY INFORMATION (Informasi Perusahaan)</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <div class="row px-2 py-2" style="line-height: 1">
                                    <div class="input-group my-4">
                                        <div class="col-md-3">
                                            <label for="detail_company_type">Company Type *</label>
                                            <br>
                                            <p class="fs-6"
                                                style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                Jenis
                                                Perusahaan</p>
                                        </div>
                                        <div class="col-md-9">
                                            <select name="detail_company_type" id="detail_company_type"
                                                class="form-control">
                                                <option value="customer">Customer</option>
                                                <option value="vendor">Vendor</option>
                                                <option value="customer dan vendor">Keduanya</option>
                                            </select>
                                            <span class="text-danger mt-2" id="message_detail_company_type"
                                                role="alert"></span>
                                        </div>
                                    </div>
                                    <div class="input-group my-4">
                                        <div class="col-md-3">
                                            <label for="detail_company_name">Company Name *</label>
                                            <br>
                                            <p class="fs-6"
                                                style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                Nama
                                                Perusahaan</p>
                                        </div>
                                        <div class="col-md-9">
                                            <input type="text" name="detail_company_name" id="detail_company_name"
                                                placeholder="" class="form-control">
                                            <span class="text-danger mt-2" id="message_detail_company_name"
                                                role="alert"></span>
                                        </div>
                                    </div>
                                    <div class="input-group mb-4">
                                        <div class="col-md-4 mb-4">
                                            <div class="row">
                                                <div class="col-md-auto">
                                                    <label for="detail_company_group_name">Company Group Name *</label>
                                                    <br>
                                                    <p class="fs-6"
                                                        style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                        Nama Grup Perusahaan</p>
                                                </div>
                                                <div class="col-md-auto">
                                                    <input type="text" name="detail_company_group_name"
                                                        id="detail_company_group_name" placeholder=""
                                                        class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-4">
                                            <div class="row">
                                                <div class="col-md-auto">
                                                    <label for="detail_established_year">Established Since (Year)
                                                        *</label>
                                                    <br>
                                                    <p class="fs-6"
                                                        style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                        Didirikan Sejak (Tahun)</p>
                                                </div>
                                                <div class="col-md-auto">
                                                    <input type="number" name="detail_established_year"
                                                        id="detail_established_year" placeholder=""
                                                        class="form-control">
                                                    <span class="text-danger mt-2" id="message_detail_established_year"
                                                        role="alert"></span>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="row">
                                                <div class="col-md-auto">
                                                    <label for="detail_total_employee">Total Employee *</label>
                                                    <br>
                                                    <p class="fs-6"
                                                        style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                        Jumlah Karyawan</p>
                                                </div>
                                                <div class="col-md-auto">
                                                    <input type="number" name="detail_total_employee"
                                                        id="detail_total_employee" placeholder=""
                                                        class="form-control">
                                                    <span class="text-danger mt-2" id="message_detail_total_employee"
                                                        role="alert"></span>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="input-group mb-4">
                                        <div class="col-md-6 mb-4">
                                            <div class="row">
                                                <div class="col-md-auto">
                                                    <label for="detail_liable_person_and_position">Liable Person &
                                                        Position
                                                        *</label>
                                                    <br>
                                                    <p class="fs-6"
                                                        style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                        Nama penanggung Jawab & Jabatan</p>
                                                </div>
                                                <div class="col-md-7">
                                                    <input type="text" name="detail_liable_person_and_position"
                                                        id="detail_liable_person_and_position" placeholder=""
                                                        class="form-control">
                                                    <span class="text-danger mt-2"
                                                        id="message_detail_liable_person_and_position"
                                                        role="alert"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-auto">
                                                    <label for="detail_owner_name">Owner’s Name *</label>
                                                    <br>
                                                    <p class="fs-6"
                                                        style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                        Nama Pemilik/Presiden</p>
                                                </div>
                                                <div class="col-md-7">
                                                    <input type="text" name="detail_owner_name"
                                                        id="detail_owner_name" placeholder="" class="form-control">
                                                    <span class="text-danger mt-2" id="message_detail_owner_name"
                                                        role="alert"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="input-group mb-4">
                                        <div class="col-md-6 mb-4">
                                            <div class="row">
                                                <div class="col-md-auto">
                                                    <label for="detail_board_of_directors">Board of Directors *</label>
                                                    <br>
                                                    <p class="fs-6"
                                                        style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                        Nama Dewan Direktur</p>
                                                </div>
                                                <div class="col-md-7">
                                                    <input type="text" name="detail_board_of_directors"
                                                        id="detail_board_of_directors" placeholder=""
                                                        class="form-control">
                                                    <span class="text-danger mt-2"
                                                        id="message_detail_board_of_directors" role="alert"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-auto">
                                                    <label for="detail_major_shareholders">Major Shareholders *</label>
                                                    <br>
                                                    <p class="fs-6"
                                                        style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                        Nama Pemilik
                                                        Saham Mayoritas</p>
                                                </div>
                                                <div class="col-md-7">
                                                    <input type="text" name="detail_major_shareholders"
                                                        id="detail_major_shareholders" placeholder=""
                                                        class="form-control">
                                                    <span class="text-danger mt-2"
                                                        id="message_detail_major_shareholders" role="alert"></span>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="input-group mb-4">
                                        <div class="col-md-auto">
                                            <label for="detail_business_classification">Business Classification
                                                *</label>
                                            <br>
                                            <p class="fs-6"
                                                style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                Jenis Usaha</p>
                                        </div>
                                        <div class="col-md-auto">
                                            <div class="row px-2">
                                                <div class="col-md-auto" id="list_business_classification">
                                                    <div class="form-check form-check-inline mb-1">
                                                        <input class="form-check-input" style="margin-bottom: 1rem;"
                                                            type="radio" name="detail_business_classification"
                                                            id="detail_business_classification_manufacturer"
                                                            value="Manufacturer">
                                                        <label class="form-check-label"
                                                            for="detail_business_classification_manufacturer">
                                                            Manufacturer
                                                            <p class="fs-6"
                                                                style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                                Manufaktur</p>
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline mb-1">
                                                        <input class="form-check-input" style="margin-bottom: 1rem;"
                                                            type="radio" name="detail_business_classification"
                                                            id="detail_business_classification_trading"
                                                            value="Trading">
                                                        <label class="form-check-label"
                                                            for="detail_business_classification_trading">
                                                            Trading
                                                            <p class="fs-6"
                                                                style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                                Trading</p>
                                                        </label>
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline mb-1">
                                                        <input class="form-check-input" style="margin-bottom: 1rem;"
                                                            type="radio" name="detail_business_classification"
                                                            id="detail_business_classification_agent" value="Agent">
                                                        <label class="form-check-label"
                                                            for="detail_business_classification_agent">
                                                            Agent
                                                            <p class="fs-6"
                                                                style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                                Agen</p>
                                                        </label>
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline mb-1">
                                                        <input class="form-check-input" style="margin-bottom: 1rem;"
                                                            type="radio" name="detail_business_classification"
                                                            id="detail_business_classification_distributor"
                                                            value="Distributor">
                                                        <label class="form-check-label"
                                                            for="detail_business_classification_distributor">
                                                            Distributor
                                                            <p class="fs-6"
                                                                style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                                Distributor</p>
                                                        </label>
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline mb-1">
                                                        <input class="form-check-input" style="margin-bottom: 1rem;"
                                                            type="radio" name="detail_business_classification"
                                                            id="detail_business_classification_services"
                                                            value="Services">
                                                        <label class="form-check-label"
                                                            for="detail_business_classification_services">
                                                            Services
                                                            <p class="fs-6"
                                                                style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                                Jasa</p>
                                                        </label>
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline mb-1">
                                                        <input class="form-check-input" style="margin-bottom: 1rem;"
                                                            type="radio" name="detail_business_classification"
                                                            id="detail_business_classification_contractor"
                                                            value="Contractor">
                                                        <label class="form-check-label"
                                                            for="detail_business_classification_contractor">
                                                            Contractor
                                                            <p class="fs-6"
                                                                style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                                Kontraktor</p>
                                                        </label>
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline mb-1">
                                                        <input class="form-check-input" style="margin-bottom: 1rem;"
                                                            type="radio" name="detail_business_classification"
                                                            id="detail_business_classification_other" value="Other">
                                                        <label class="form-check-label"
                                                            for="detail_business_classification_other">
                                                            Other
                                                            <p class="fs-6"
                                                                style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                                Lain-lain</p>
                                                        </label>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-auto" id="field_form_detail_business_other">

                                                </div>
                                                <span class="text-danger mt-2"
                                                    id="message_detail_business_classification" role="alert"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="input-group mb-4">
                                        <div class="col-md-12 mb-4">
                                            <div class="row">
                                                <div class="col-md-auto">
                                                    <label for="detail_business_classification_detail">Business Classification Detail*</label>
                                                    <br>
                                                    <p class="fs-6"
                                                        style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                        Detail Jenis Usaha</p>
                                                </div>
                                                <div class="col-md-7">
                                                    <textarea class="form-control" name="detail_business_classification_detail" id="detail_business_classification_detail" cols="20" rows="8"></textarea>
                                                    <span class="text-danger mt-2" id="message_detail_business_classification_detail" role="alert"></span>
    
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="input-group mb-4">
                                        <div class="col-md-3">
                                            <label for="detail_website_address">Website Address *</label>
                                            <br>
                                            <p class="fs-6"
                                                style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                Alamat Situs</p>
                                        </div>
                                        <div class="col-md-9">
                                            <input type="text" name="detail_website_address"
                                                id="detail_website_address" placeholder="" class="form-control">
                                            <span class="text-danger mt-2" id="message_detail_website_address"
                                                role="alert"></span>
                                        </div>
                                    </div>
                                    <div class="input-group mb-4">
                                        <div class="col-md-auto">
                                            <label for="detail_system_management">System Management *</label>
                                            <br>
                                            <p class="fs-6"
                                                style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                Jenis Usaha</p>
                                        </div>
                                        <div class="col-md-auto detail_system_management">
                                            <div class="form-check form-check-inline mb-1">
                                                <input class="form-check-input" type="radio"
                                                    name="detail_system_management" id="detail_system_management_iso"
                                                    value="ISO">
                                                <label class="form-check-label" for="detail_system_management_iso">
                                                    ISO</label>
                                            </div>
                                            <div class="form-check form-check-inline mb-1">
                                                <input class="form-check-input" type="radio"
                                                    name="detail_system_management" id="detail_system_management_smk3"
                                                    value="SMK3">
                                                <label class="form-check-label" for="detail_system_management_smk3">
                                                    SMK3</label>
                                            </div>
                                            <div class="form-check form-check-inline mb-1">
                                                <input class="form-check-input" style="margin-bottom: 1rem !important"
                                                    type="radio" name="detail_system_management"
                                                    id="detail_system_management_other" value="Others Certificate">
                                                <label class="form-check-label" for="detail_system_management_other">
                                                    Others Certificate
                                                    <p class="fs-6"
                                                        style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                        Sertifikat lainnya</p>
                                                </label>
                                            </div>
                                            <span class="text-danger mt-2" id="message_detail_system_management"
                                                role="alert"></span>
                                        </div>
                                    </div>
                                    <div class="input-group mb-4">
                                        <div class="col-md-6 mb-4">
                                            <div class="row">
                                                <div class="col-md-auto">
                                                    <label for="detail_contact_person">Contact Persons *</label>
                                                    <br>
                                                    <p class="fs-6"
                                                        style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                        Staff yang dapat dihubungi</p>
                                                </div>
                                                <div class="col-md-auto">
                                                    <input type="text" name="detail_contact_person"
                                                        id="detail_contact_person" placeholder=""
                                                        class="form-control">
                                                    <span class="text-danger mt-2" id="message_detail_contact_person"
                                                        role="alert"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-auto">
                                                    <label for="detail_communication_language">Comm. Language *</label>
                                                    <br>
                                                    <p class="fs-6"
                                                        style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                        Bahasa Komunikasi</p>
                                                </div>
                                                <div class="col-md-auto detail_option_languange">
                                                    <div class="form-check form-check-inline mb-1">
                                                        <input class="form-check-input"
                                                            style="margin-bottom: 1rem !important" type="radio"
                                                            name="detail_communication_language"
                                                            id="detail_communication_language_bahasa" value="Bahasa">
                                                        <label class="form-check-label"
                                                            for="detail_communication_language_bahasa">
                                                            Bahasa
                                                            <p class="fs-6"
                                                                style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                                Indonesia</p>
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline mb-1">
                                                        <input class="form-check-input"
                                                            style="margin-bottom: 1rem !important" type="radio"
                                                            name="detail_communication_language"
                                                            id="detail_communication_language_english"
                                                            value="English">
                                                        <label class="form-check-label"
                                                            for="detail_communication_language_english">
                                                            English
                                                            <p class="fs-6"
                                                                style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                                Inggris</p>
                                                        </label>
                                                    </div>
                                                </div>
                                                <span class="text-danger mt-2"
                                                    id="message_detail_communication_language" role="alert"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="input-group mb-4">
                                        <div class="col-md-3">
                                            <label for="detail_email_address">Email Address (Correspondence) *</label>
                                            <p class="fs-6"
                                                style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                Alamat email (Koresponden)</p>
                                        </div>
                                        <div class="col-md-9">
                                            <input type="text" name="detail_email_address"
                                                id="detail_email_address" placeholder="" class="form-control">
                                            <span class="text-danger mt-2" id="message_detail_email_address"
                                                role="alert"></span>
                                        </div>
                                    </div>
                                    <div class="input-group mb-4">
                                        <div class="col-sm-6">
                                            <label for="detail_stamp_file">Stamp *</label>
                                            <p class="fs-6"
                                                style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                Stempel
                                            </p>
                                            <div class="form_field_detail_stamp">
                                                <input type="file" name="detail_stamp_file" id="detail_stamp_file"
                                                    class="form-control mb-2" />
                                            </div>
                                            <a id="link_stamp" target="_blank">
                                                <i class="fas fa-file"></i> Your Stamp
                                            </a>
                                            <div class="button-change-stamp" style="cursor: pointer;"></div>
                                        </div>
                                        <span class="text-danger mt-2" id="message_detail_stamp_file"
                                            role="alert"></span>
                                        <div class="col-sm-6">
                                            <label for="detail_signature_file">Signature *</label>
                                            <p class="fs-6"
                                                style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                Tanda Tangan
                                            </p>
                                            <div class="form_field_detail_signature">
                                                <input type="file" name="detail_signature_file"
                                                    id="detail_signature_file" class="form-control mb-2" />
                                            </div>
                                            <a id="link_signature" target="_blank">
                                                <i class="fas fa-file"></i> Your Signature
                                            </a>
                                            <div class="button-change-signature" style="cursor: pointer;"></div>
                                        </div>
                                        <span class="text-danger mt-2" id="message_detail_signature_file"
                                            role="alert"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                </div>
                {{-- end master --}}
            </div>
            <div class="modal-footer" id="button_update_partner">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btn_update_data_company">Update</button>
            </div>
            </form>
        </div>
    </div>
</div>
