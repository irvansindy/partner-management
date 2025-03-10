<!-- Modal -->
<div class="modal fade" id="modalCreatePartner" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="modalCreatePartnerLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCreatePartnerLabel">Form Partner</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <form action="" method="" id="form_company">
                        {{-- company information master --}}
                        <div class="card card-info">
                            <div class="card-header">
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
                                            <label for="company_type">Company Type *</label>
                                            <br>
                                            <p class="fs-6"
                                                style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                Jenis
                                                Perusahaan</p>
                                        </div>
                                        <div class="col-md-9">
                                            {{-- <input type="text" name="company_name" id="company_name" placeholder=""
                                                class="form-control"> --}}
                                                <select name="company_type" id="company_type">
                                                    <option value="customer">Customer</option>
                                                    <option value="vendor">Vendor</option>
                                                    <option value="customer dan vendor">Keduanya</option>
                                                </select>
                                        </div>
                                    </div>
                                    <div class="input-group my-4">
                                        <div class="col-md-3">
                                            <label for="">Company Name *</label>
                                            <br>
                                            <p class="fs-6"
                                                style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                Nama
                                                Perusahaan</p>
                                        </div>
                                        <div class="col-md-9">
                                            <input type="text" name="company_name" id="company_name" placeholder=""
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="input-group mb-4">
                                        <div class="col-md-4 mb-4">
                                            <div class="row">
                                                <div class="col-md-auto">
                                                    <label for="company_group_name">Company Group Name *</label>
                                                    <br>
                                                    <p class="fs-6"
                                                        style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                        Nama Grup Perusahaan</p>
                                                </div>
                                                <div class="col-md-auto">
                                                    <input type="text" name="company_group_name"
                                                        id="company_group_name" placeholder="" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-4">
                                            <div class="row">
                                                <div class="col-md-auto">
                                                    <label for="established_year">Established Since (Year) *</label>
                                                    <br>
                                                    <p class="fs-6"
                                                        style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                        Didirikan Sejak (Tahun)</p>
                                                </div>
                                                <div class="col-md-auto">
                                                    <input type="text" name="established_year" id="established_year"
                                                        placeholder="" class="form-control">
                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="row">
                                                <div class="col-md-auto">
                                                    <label for="total_employee">Total Employee *</label>
                                                    <br>
                                                    <p class="fs-6"
                                                        style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                        Jumlah Karyawan</p>
                                                </div>
                                                <div class="col-md-auto">
                                                    <input type="text" name="total_employee" id="total_employee"
                                                        placeholder="" class="form-control">
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="input-group mb-4">
                                        <div class="col-md-6 mb-4">
                                            <div class="row">
                                                <div class="col-md-auto">
                                                    <label for="liable_person_and_position">Liable Person & Position
                                                        *</label>
                                                    <br>
                                                    <p class="fs-6"
                                                        style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                        Nama penanggung Jawab & Jabatan</p>
                                                </div>
                                                <div class="col-md-7">
                                                    <input type="text" name="liable_person_and_position"
                                                        id="liable_person_and_position" placeholder=""
                                                        class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-auto">
                                                    <label for="owner_name">Owner’s Name *</label>
                                                    <br>
                                                    <p class="fs-6"
                                                        style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                        Nama Pemilik/Presiden</p>
                                                </div>
                                                <div class="col-md-7">
                                                    <input type="text" name="owner_name" id="owner_name"
                                                        placeholder="" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="input-group mb-4">
                                        <div class="col-md-6 mb-4">
                                            <div class="row">
                                                <div class="col-md-auto">
                                                    <label for="board_of_directors">Board of Directors *</label>
                                                    <br>
                                                    <p class="fs-6"
                                                        style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                        Nama Dewan Direktur</p>
                                                </div>
                                                <div class="col-md-7">
                                                    <input type="text" name="board_of_directors"
                                                        id="board_of_directors" placeholder="" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-auto">
                                                    <label for="major_shareholders">Major Shareholders *</label>
                                                    <br>
                                                    <p class="fs-6"
                                                        style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                        Nama Pemilik
                                                        Saham Mayoritas</p>
                                                </div>
                                                <div class="col-md-7">
                                                    <input type="text" name="major_shareholders"
                                                        id="major_shareholders" placeholder="" class="form-control">
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="input-group mb-4">
                                        <div class="col-md-auto">
                                            <label for="">Business Classification *</label>
                                            <br>
                                            <p class="fs-6"
                                                style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                Jenis Usaha</p>
                                        </div>
                                        <div class="col-md-auto">
                                            <div class="row px-2">
                                                <div class="col-md-auto">
                                                    <div class="form-check form-check-inline mb-1">
                                                        <input class="form-check-input" style="margin-bottom: 1rem;"
                                                            type="radio" name="business_classification"
                                                            id="business_classification_manufacturer"
                                                            value="Manufacturer">
                                                        <label class="form-check-label"
                                                            for="business_classification_manufacturer">
                                                            Manufacturer
                                                            <p class="fs-6"
                                                                style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                                Manufaktur</p>
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline mb-1">
                                                        <input class="form-check-input" style="margin-bottom: 1rem;"
                                                            type="radio" name="business_classification"
                                                            id="business_classification_trading" value="Trading">
                                                        <label class="form-check-label"
                                                            for="business_classification_trading">
                                                            Trading
                                                            <p class="fs-6"
                                                                style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                                Trading</p>
                                                        </label>
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline mb-1">
                                                        <input class="form-check-input" style="margin-bottom: 1rem;"
                                                            type="radio" name="business_classification"
                                                            id="business_classification_agent" value="Agent">
                                                        <label class="form-check-label"
                                                            for="business_classification_agent">
                                                            Agent
                                                            <p class="fs-6"
                                                                style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                                Agen</p>
                                                        </label>
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline mb-1">
                                                        <input class="form-check-input" style="margin-bottom: 1rem;"
                                                            type="radio" name="business_classification"
                                                            id="business_classification_distributor"
                                                            value="Distributor">
                                                        <label class="form-check-label"
                                                            for="business_classification_distributor">
                                                            Distributor
                                                            <p class="fs-6"
                                                                style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                                Distributor</p>
                                                        </label>
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline mb-1">
                                                        <input class="form-check-input" style="margin-bottom: 1rem;"
                                                            type="radio" name="business_classification"
                                                            id="business_classification_services" value="Services">
                                                        <label class="form-check-label"
                                                            for="business_classification_services">
                                                            Services
                                                            <p class="fs-6"
                                                                style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                                Jasa</p>
                                                        </label>
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline mb-1">
                                                        <input class="form-check-input" style="margin-bottom: 1rem;"
                                                            type="radio" name="business_classification"
                                                            id="business_classification_contractor"
                                                            value="Contractor">
                                                        <label class="form-check-label"
                                                            for="business_classification_contractor">
                                                            Contractor
                                                            <p class="fs-6"
                                                                style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                                Kontraktor</p>
                                                        </label>
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline mb-1">
                                                        <input class="form-check-input" style="margin-bottom: 1rem;"
                                                            type="radio" name="business_classification"
                                                            id="business_classification_other" value="Other">
                                                        <label class="form-check-label"
                                                            for="business_classification_other">
                                                            Other
                                                            <p class="fs-6"
                                                                style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                                Lain-lain</p>
                                                        </label>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-auto" id="field_form_create_business_other">
                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="input-group mb-4">
                                        <div class="col-md-3">
                                            <label for="website_address">Website Address *</label>
                                            <br>
                                            <p class="fs-6"
                                                style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                Alamat Situs</p>
                                        </div>
                                        <div class="col-md-9">
                                            <input type="text" name="website_address" id="website_address"
                                                placeholder="" class="form-control">
                                        </div>
                                    </div>
                                    <div class="input-group mb-4">
                                        <div class="col-md-auto">
                                            <label for="">System Management *</label>
                                            <br>
                                            <p class="fs-6"
                                                style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                Jenis Usaha</p>
                                        </div>
                                        <div class="col-md-auto">
                                            <div class="form-check form-check-inline mb-1">
                                                <input class="form-check-input" type="radio"
                                                    name="system_management" id="system_management_iso"
                                                    value="ISO">
                                                <label class="form-check-label" for="system_management_iso">
                                                    ISO</label>
                                            </div>
                                            <div class="form-check form-check-inline mb-1">
                                                <input class="form-check-input" type="radio"
                                                    name="system_management" id="system_management_smk3"
                                                    value="SMK3">
                                                <label class="form-check-label" for="system_management_smk3">
                                                    SMK3</label>
                                            </div>
                                            <div class="form-check form-check-inline mb-1">
                                                <input class="form-check-input" style="margin-bottom: 1rem !important"
                                                    type="radio" name="system_management"
                                                    id="system_management_other" value="Others Certificate">
                                                <label class="form-check-label" for="system_management_other">
                                                    Others Certificate
                                                    <p class="fs-6"
                                                        style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                        Sertifikat lainnya</p>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="input-group mb-4">
                                        <div class="col-md-6 mb-4">
                                            <div class="row">
                                                <div class="col-md-auto">
                                                    <label for="contact_person">Contact Persons *</label>
                                                    <br>
                                                    <p class="fs-6"
                                                        style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                        Staff yang dapat dihubungi</p>
                                                </div>
                                                <div class="col-md-auto">
                                                    <input type="text" name="contact_person" id="contact_person"
                                                        placeholder="" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-auto">
                                                    <label for="communication_language">Comm. Language *</label>
                                                    <br>
                                                    <p class="fs-6"
                                                        style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                        Bahasa Komunikasi</p>
                                                </div>
                                                <div class="col-md-auto">
                                                    <div class="form-check form-check-inline mb-1">
                                                        <input class="form-check-input"
                                                            style="margin-bottom: 1rem !important" type="radio"
                                                            name="communication_language"
                                                            id="communication_language_bahasa" value="Bahasa">
                                                        <label class="form-check-label"
                                                            for="communication_language_bahasa">
                                                            Bahasa
                                                            <p class="fs-6"
                                                                style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                                Indonesia</p>
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline mb-1">
                                                        <input class="form-check-input"
                                                            style="margin-bottom: 1rem !important" type="radio"
                                                            name="communication_language"
                                                            id="communication_language_english" value="English">
                                                        <label class="form-check-label"
                                                            for="communication_language_english">
                                                            English
                                                            <p class="fs-6"
                                                                style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                                Inggris</p>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="input-group mb-4">
                                        <div class="col-md-3">
                                            <label for="email_address">Email Address (Correspondence) *</label>
                                            <p class="fs-6"
                                                style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                Alamat email (Koresponden)</p>
                                        </div>
                                        <div class="col-md-9">
                                            <input type="text" name="email_address" id="email_address"
                                                placeholder="" class="form-control">
                                        </div>
                                    </div>
                                    <div class="input-group mb-4">
                                        <div class="col-md-6">
                                            <label for="email_address">Stamp *</label>
                                            <p class="fs-6"
                                                style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                Stempel</p>
                                            <input type="file" name="stamp_file" id="stamp_file" class="form-control"/>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="email_address">Signature *</label>
                                            <p class="fs-6"
                                                style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                Tanda Tangan</p>
                                            <input type="file" name="signature_file" id="signature_file" class="form-control"/>
                                        </div>
                                    </div>
                                    <div class="company_address_additional" id="company_address_additional">
                                        {{-- <fieldset>
                                        </fieldset> --}}
                                        <div class="input-group mb-4">
                                            <div class="col-md-2">
                                                <label for="address">Company Address *<br>
                                                    (according to Company Address stated in the Tax Register Number):
                                                    *</label>
                                                <br>
                                                <p class="fs-6"
                                                    style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                    Alamat Perusahaan
                                                    (sesuai dengan NPWP)</p>
                                            </div>
                                            <div class="col-md-10">
                                                <div class="row">
                                                    <div class="col-md-6 mb-4">
                                                        <input type="text" name="address[]" id="address"
                                                            placeholder="" class="form-control">
                                                    </div>
                                                    <div class="col-md-auto">
                                                        <div class="row">
                                                            <div class="col-md-auto">
                                                                <label for="city">City *</label>
                                                                <br>
                                                                <p class="fs-6"
                                                                    style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                                    Kota</p>
                                                            </div>
                                                            <div class="col-md-auto">
                                                                <input type="text" name="city[]" id="city"
                                                                    placeholder="" class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-auto mb-4">
                                                        <div class="row">
                                                            <div class="col-md-auto">
                                                                <label for="country">Country *</label>
                                                                <br>
                                                                <p class="fs-6"
                                                                    style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                                    Negara</p>
                                                            </div>
                                                            <div class="col-md-auto">
                                                                <input type="text" name="country[]" id="country"
                                                                    placeholder="" class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-auto mb-4">
                                                        <div class="row">
                                                            <div class="col-md-auto">
                                                                <label for="province">Province *</label>
                                                                <br>
                                                                <p class="fs-6"
                                                                    style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                                    Provinsi</p>
                                                            </div>
                                                            <div class="col-md-auto">
                                                                <input type="text" name="province[]"
                                                                    id="province" placeholder=""
                                                                    class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-auto mb-4">
                                                        <div class="row">
                                                            <div class="col-md-auto">
                                                                <label for="zip_code">Zip Code *</label>
                                                                <br>
                                                                <p class="fs-6"
                                                                    style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                                    Kode Pos</p>
                                                            </div>
                                                            <div class="col-md-auto">
                                                                <input type="text" name="zip_code[]"
                                                                    id="zip_code" placeholder=""
                                                                    class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-auto mb-4">
                                                        <div class="row">
                                                            <div class="col-md-auto">
                                                                <label for="telephone">Telephone *</label>
                                                                <p class="fs-6"
                                                                    style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                                    + [Country-Area Code] [No.]
                                                                    Telepon +[Negara-Area] [No.]</p>
                                                            </div>
                                                            <div class="col-md-auto">
                                                                <input type="text" name="telephone[]"
                                                                    id="telephone" placeholder=""
                                                                    class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-auto mb-4">
                                                        <div class="row">
                                                            <div class="col-md-auto">
                                                                <label for="fax">Fax *</label>
                                                                <p class="fs-6"
                                                                    style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                                    + [Country-Area Code] [No.]
                                                                    Fax +[Negara-Area] [No.]</p>
                                                            </div>
                                                            <div class="col-md-auto">
                                                                <input type="text" name="fax[]" id="fax"
                                                                    placeholder="" class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-auto">
                                                        <div class="form-check">
                                                            <input class="form-check-input main_address" type="radio" name="main_address[]" id="main_address">
                                                            <label class="form-check-label" for="main_address">
                                                                Main Address
                                                            </label>
                                                            <p class="fs-6" style="margin-bottom: 0.5rem !important; font-size: 10px !important;">Alamat Utama</p>

                                                        </div>
                                                    </div>
                                                    <div class="col mb-4 align-items-end mr-4">
                                                        <button type="button" class="btn btn-primary float-right"
                                                            id="add_dynamic_address">+ Address</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="dynamic_company_address">

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        {{-- end master --}}

                        {{-- company bank --}}
                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title">
                                    2. BANK DATA * (Data Bank)
                                </h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row px-2 py-2" style="line-height: 1">
                                    <div class="input-group mt-4">
                                        <div class="col-md-6 mb-4">
                                            <div class="row">
                                                <div class="col-md-auto">
                                                    <label for="bank_name">Bank Name *</label>
                                                    <br>
                                                    <p class="fs-6"
                                                        style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                        Nama Bank</p>
                                                </div>
                                                <div class="col-md-auto">
                                                    <input type="text" name="bank_name[]" id="bank_name"
                                                        placeholder="" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-auto">
                                                    <label for="branch">Branch *</label>
                                                    <br>
                                                    <p class="fs-6"
                                                        style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                        Cabang</p>
                                                </div>
                                                <div class="col-md-auto">
                                                    <input type="text" name="branch[]" id="branch"
                                                        placeholder="" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="input-group">
                                        <div class="col-md-6 mb-4">
                                            <div class="row">
                                                <div class="col-md-auto">
                                                    <label for="account_name">Account Name *</label>
                                                    <br>
                                                    <p class="fs-6"
                                                        style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                        Rekening Atas Nama</p>
                                                </div>
                                                <div class="col-md-auto">
                                                    <input type="text" name="account_name[]" id="account_name"
                                                        placeholder="" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-4">
                                            <div class="row">
                                                <div class="col-md-auto">
                                                    <label for="city_or_country">City/Country *</label>
                                                    <br>
                                                    <p class="fs-6"
                                                        style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                        Kota/Negara</p>
                                                </div>
                                                <div class="col-md-auto">
                                                    <input type="text" name="city_or_country[]"
                                                        id="city_or_country" placeholder="" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="input-group">
                                        <div class="col-md-auto mb-4">
                                            <div class="row">
                                                <div class="col-md-auto">
                                                    <label for="account_number">Account No. *</label>
                                                    <br>
                                                    <p class="fs-6"
                                                        style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                        No Rekening</p>
                                                </div>
                                                <div class="col-md-auto">
                                                    <input type="text" name="account_number[]" id="account_number"
                                                        placeholder="" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-auto mb-4">
                                            <div class="row">
                                                <div class="col-md-auto">
                                                    <label for="currency">Currency *</label>
                                                    <br>
                                                    <p class="fs-6"
                                                        style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                        Mata Uang</p>
                                                </div>
                                                <div class="col-md-auto">
                                                    <input type="text" name="currency[]" id="currency"
                                                        placeholder="" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-auto">
                                            <div class="row">
                                                <div class="col-md-auto">
                                                    <label for="swift_code">Swift Code *</label>
                                                    <br>
                                                    <p class="fs-6"
                                                        style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                        Optional</p>
                                                </div>
                                                <div class="col-md-auto">
                                                    <input type="text" name="swift_code[]" id="swift_code"
                                                        placeholder="" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="input-group d-flex justify-content-end mr-4 mb-4 mt-4">
                                        <button type="button" class="btn btn-primary" id="add_bank"> +
                                            Bank</button>
                                    </div>
                                    <div class="dynamic_bank">

                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- end company bank --}}

                        {{-- company tax --}}
                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title">
                                    3. TAX DATA * (Data Pajak)
                                </h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row px-2 py-2" style="line-height: 1">
                                    <div class="input-group mt-4">
                                        <div class="col-md-auto mb-4">
                                            <div class="row">
                                                <div class="col-md-auto">
                                                    <label for="register_number_as_in_tax_invoice">Tax Register Number
                                                        (As in Tax Invoice) *</label>
                                                    <br>
                                                    <p class="fs-6"
                                                        style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                        Nomor NPWP (Sesuai dengan Faktur Pajak)</p>
                                                </div>
                                                <div class="col-md-auto">
                                                    <input type="text" name="register_number_as_in_tax_invoice"
                                                        id="register_number_as_in_tax_invoice" placeholder=""
                                                        class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-auto mb-4">
                                            <div class="row">
                                                <div class="col-md-auto">
                                                    <label for="trc_number">TRC No. *</label>
                                                    <br>
                                                    <p class="fs-6"
                                                        style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                        Nomor TRC</p>
                                                </div>
                                                <div class="col-md-auto">
                                                    <input type="text" name="trc_number" id="trc_number"
                                                        placeholder="" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="input-group">
                                        <div class="col-md-auto mb-4">
                                            <div class="row">
                                                <div class="col-md-auto">
                                                    <label for="register_number_related_branch">Tax Register Number
                                                        (Related Branch) *</label>
                                                    <br>
                                                    <p class="fs-6"
                                                        style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                        Nomor NPWP (Cabang Terkait)</p>
                                                </div>
                                                <div class="col-md-auto">
                                                    <input type="text" name="register_number_related_branch"
                                                        id="register_number_related_branch" placeholder=""
                                                        class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-auto mb-4">
                                            <div class="row">
                                                <div class="col-md-auto">
                                                    <label for="valid_until">Valid Until *</label>
                                                    <br>
                                                    <p class="fs-6"
                                                        style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                        Berlaku Sampai</p>
                                                </div>
                                                <div class="col-md-auto">
                                                    <input type="date" name="valid_until" id="valid_until"
                                                        placeholder="" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="input-group mb-4">
                                        <div class="col-md-3">
                                            <label for="taxable_entrepreneur_number">Taxable Entrepreneur Number
                                                *</label>
                                            <p class="fs-6"
                                                style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                Nomor Surat Pengukuhan PKP</p>
                                        </div>
                                        <div class="col-md-9">
                                            <input type="text" name="taxable_entrepreneur_number"
                                                id="taxable_entrepreneur_number" placeholder="" class="form-control">
                                        </div>
                                    </div>
                                    <div class="input-group mb-4">
                                        <div class="col-md-3">
                                            <label for="tax_invoice_serial_number">Tax Invoice Serial No. *</label>
                                            <p class="fs-6"
                                                style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                Nomor Serial Faktur Pajak (pada SK-PKP)</p>
                                        </div>
                                        <div class="col-md-9">
                                            <input type="text" name="tax_invoice_serial_number"
                                                id="tax_invoice_serial_number" placeholder="" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- end company tax --}}

                        {{-- support document --}}
                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title">
                                    4. SUPPORTING DOCUMENTS (Dokumen yang harus dilengkapi)
                                </h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row px-2 py-2" style="line-height: 1">
                                    <div class="input-group mt-2">
                                        <ul class="nav nav-tabs" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" href="#document_type_pt" role="tab"
                                                    data-toggle="tab">PT</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#document_type_cv" role="tab"
                                                    data-toggle="tab">CV</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#document_type_ud_or_pd" role="tab"
                                                    data-toggle="tab">UD/PD</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#document_type_perorangan" role="tab"
                                                    data-toggle="tab">Perorangan</a>
                                            </li>
                                        </ul>
                                        <div class="tab-content mt-4">
                                            <div role="tabpanel" class="tab-pane fade" id="document_type_pt">
                                                <table class="table table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>Doc Type</th>
                                                            <th>File</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="data_doc_type_pt">
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div role="tabpanel" class="tab-pane fade" id="document_type_cv">
                                                <table class="table table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>Doc Type</th>
                                                            <th>File</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="data_doc_type_cv">
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div role="tabpanel" class="tab-pane fade" id="document_type_ud_or_pd">
                                                <table class="table table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>Doc Type</th>
                                                            <th>File</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="data_doc_type_ud_or_pd">
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div role="tabpanel" class="tab-pane fade" id="document_type_perorangan">
                                                <table class="table table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>Doc Type</th>
                                                            <th>File</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="data_doc_type_perorangan">
                                                    </tbody>
                                                </table>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="d-flex justify-content-end">
                                    <button type="button" class="btn btn-primary" id="btn_submit_data_company">
                                        submit
                                    </button>
                                </div>
                            </div>
                        </div>
                        {{-- end support document --}}
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="submit_form_partner">Create</button>
            </div>
        </div>
    </div>
</div>