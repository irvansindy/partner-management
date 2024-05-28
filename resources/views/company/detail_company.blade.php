@extends('adminlte::page')

@section('title', 'Detail Company')

@section('content_header')
    <h1>Data Partner
        <br>Seleksi Customer
    </h1>
@stop

@section('content')
    <div class="container-fluid">
        <form action="" method="" id="form_detail_company_partner">
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
                                <label for="detail_company_name">Company Name *</label>
                                <br>
                                <p class="fs-6" style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                    Nama
                                    Perusahaan</p>
                            </div>
                            <div class="col-md-9">
                                <input type="text" name="detail_company_name" id="detail_company_name" placeholder=""
                                    class="form-control">
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
                                        <input type="text" name="detail_company_group_name" id="detail_company_group_name"
                                            placeholder="" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <div class="row">
                                    <div class="col-md-auto">
                                        <label for="detail_established_year">Established Since (Year) *</label>
                                        <br>
                                        <p class="fs-6"
                                            style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                            Didirikan Sejak (Tahun)</p>
                                    </div>
                                    <div class="col-md-auto">
                                        <input type="text" name="detail_established_year" id="detail_established_year" placeholder=""
                                            class="form-control">
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
                                        <input type="text" name="detail_total_employee" id="detail_total_employee" placeholder=""
                                            class="form-control">
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="input-group mb-4">
                            <div class="col-md-6 mb-4">
                                <div class="row">
                                    <div class="col-md-auto">
                                        <label for="detail_liable_person_and_position">Liable Person & Position
                                            *</label>
                                        <br>
                                        <p class="fs-6"
                                            style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                            Nama penanggung Jawab & Jabatan</p>
                                    </div>
                                    <div class="col-md-7">
                                        <input type="text" name="detail_liable_person_and_position"
                                            id="detail_liable_person_and_position" placeholder="" class="form-control">
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
                                        <input type="text" name="detail_owner_name" id="detail_owner_name" placeholder=""
                                            class="form-control">
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
                                        <input type="text" name="detail_board_of_directors" id="detail_board_of_directors"
                                            placeholder="" class="form-control">
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
                                        <input type="text" name="detail_major_shareholders" id="detail_major_shareholders"
                                            placeholder="" class="form-control">
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="input-group mb-4">
                            <div class="col-md-auto">
                                <label for="">Business Classification *</label>
                                <br>
                                <p class="fs-6" style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                    Jenis Usaha</p>
                            </div>
                            <div class="col-md-auto">
                                <div class="row px-2">
                                    <div class="col-md-auto">
                                        <div class="form-check form-check-inline mb-1">
                                            <input class="form-check-input" style="margin-bottom: 1rem;" type="radio"
                                                name="detail_business_classification" id="detail_business_classification_manufacturer"
                                                value="Manufacturer">
                                            <label class="form-check-label" for="detail_business_classification_manufacturer">
                                                Manufacturer
                                                <p class="fs-6"
                                                    style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                    Manufaktur</p>
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline mb-1">
                                            <input class="form-check-input" style="margin-bottom: 1rem;" type="radio"
                                                name="detail_business_classification" id="detail_business_classification_trading"
                                                value="Trading">
                                            <label class="form-check-label" for="detail_business_classification_trading">
                                                Trading
                                                <p class="fs-6"
                                                    style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                    Trading</p>
                                            </label>
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline mb-1">
                                            <input class="form-check-input" style="margin-bottom: 1rem;" type="radio"
                                                name="detail_business_classification" id="detail_business_classification_agent"
                                                value="Agent">
                                            <label class="form-check-label" for="detail_business_classification_agent">
                                                Agent
                                                <p class="fs-6"
                                                    style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                    Agen</p>
                                            </label>
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline mb-1">
                                            <input class="form-check-input" style="margin-bottom: 1rem;" type="radio"
                                                name="detail_business_classification" id="detail_business_classification_distributor"
                                                value="Distributor">
                                            <label class="form-check-label" for="detail_business_classification_distributor">
                                                Distributor
                                                <p class="fs-6"
                                                    style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                    Distributor</p>
                                            </label>
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline mb-1">
                                            <input class="form-check-input" style="margin-bottom: 1rem;" type="radio"
                                                name="detail_business_classification" id="detail_business_classification_services"
                                                value="Services">
                                            <label class="form-check-label" for="detail_business_classification_services">
                                                Services
                                                <p class="fs-6"
                                                    style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                    Jasa</p>
                                            </label>
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline mb-1">
                                            <input class="form-check-input" style="margin-bottom: 1rem;" type="radio"
                                                name="detail_business_classification" id="detail_business_classification_contractor"
                                                value="Contractor">
                                            <label class="form-check-label" for="detail_business_classification_contractor">
                                                Contractor
                                                <p class="fs-6"
                                                    style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                    Kontraktor</p>
                                            </label>
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline mb-1">
                                            <input class="form-check-input" style="margin-bottom: 1rem;" type="radio"
                                                name="detail_business_classification" id="detail_business_classification_other"
                                                value="Other">
                                            <label class="form-check-label" for="detail_business_classification_other">
                                                Other
                                                <p class="fs-6"
                                                    style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                    Lain-lain</p>
                                            </label>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-auto">
                                        <input type="text" name="detail_business_classification_other_detail"
                                            id="detail_business_classification_other_detail" placeholder="Other"
                                            class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="input-group mb-4">
                            <div class="col-md-3">
                                <label for="detail_website_address">Website Address *</label>
                                <br>
                                <p class="fs-6" style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                    Alamat Situs</p>
                            </div>
                            <div class="col-md-9">
                                <input type="text" name="detail_website_address" id="detail_website_address" placeholder=""
                                    class="form-control">
                            </div>
                        </div>
                        <div class="input-group mb-4">
                            <div class="col-md-auto">
                                <label for="">System Management *</label>
                                <br>
                                <p class="fs-6" style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                    Jenis Usaha</p>
                            </div>
                            <div class="col-md-auto detail_system_management">
                                <div class="form-check form-check-inline mb-1">
                                    <input class="form-check-input" type="radio" name="detail_system_management"
                                        id="detail_system_management_iso" value="ISO">
                                    <label class="form-check-label" for="detail_system_management_iso">
                                        ISO</label>
                                </div>
                                <div class="form-check form-check-inline mb-1">
                                    <input class="form-check-input" type="radio" name="detail_system_management"
                                        id="detail_system_management_smk3" value="SMK3">
                                    <label class="form-check-label" for="detail_system_management_smk3">
                                        SMK3</label>
                                </div>
                                <div class="form-check form-check-inline mb-1">
                                    <input class="form-check-input" style="margin-bottom: 1rem !important" type="radio"
                                        name="detail_system_management" id="detail_system_management_other" value="Others Certificate">
                                    <label class="form-check-label" for="detail_system_management_other">
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
                                        <label for="detail_contact_person">Contact Persons *</label>
                                        <br>
                                        <p class="fs-6"
                                            style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                            Staff yang dapat dihubungi</p>
                                    </div>
                                    <div class="col-md-auto">
                                        <input type="text" name="detail_contact_person" id="detail_contact_person" placeholder=""
                                            class="form-control">
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
                                            <input class="form-check-input" style="margin-bottom: 1rem !important"
                                                type="radio" name="detail_communication_language"
                                                id="detail_communication_language_bahasa" value="Bahasa">
                                            <label class="form-check-label" for="detail_communication_language_bahasa">
                                                Bahasa
                                                <p class="fs-6"
                                                    style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                    Indonesia</p>
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline mb-1">
                                            <input class="form-check-input" style="margin-bottom: 1rem !important"
                                                type="radio" name="detail_communication_language"
                                                id="detail_communication_language_english" value="English">
                                            <label class="form-check-label" for="detail_communication_language_english">
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
                                <label for="detail_email_address">Email Address (Correspondence) *</label>
                                <p class="fs-6" style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                    Alamat email (Koresponden)</p>
                            </div>
                            <div class="col-md-9">
                                <input type="text" name="detail_email_address" id="detail_email_address" placeholder=""
                                    class="form-control">
                            </div>
                        </div>
                        <div class="detail_company_address_additional" id="detail_company_address_additional">
                            {{-- <fieldset>
                            </fieldset> --}}
                            <div class="input-group mb-4">
                                <div class="col-md-2">
                                    <label for="detail_address">Company Address *<br>
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
                                            <input type="text" name="detail_address[]" id="detail_address" placeholder=""
                                                class="form-control">
                                        </div>
                                        <div class="col-md-auto">
                                            <div class="row">
                                                <div class="col-md-auto">
                                                    <label for="detail_city">City *</label>
                                                    <br>
                                                    <p class="fs-6"
                                                        style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                        Kota</p>
                                                </div>
                                                <div class="col-md-auto">
                                                    <input type="text" name="detail_city[]" id="detail_city" placeholder=""
                                                        class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-auto mb-4">
                                            <div class="row">
                                                <div class="col-md-auto">
                                                    <label for="detail_country">Country *</label>
                                                    <br>
                                                    <p class="fs-6"
                                                        style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                        Negara</p>
                                                </div>
                                                <div class="col-md-auto">
                                                    <input type="text" name="detail_country[]" id="detail_country" placeholder=""
                                                        class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-auto mb-4">
                                            <div class="row">
                                                <div class="col-md-auto">
                                                    <label for="detail_province">Province *</label>
                                                    <br>
                                                    <p class="fs-6"
                                                        style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                        Provinsi</p>
                                                </div>
                                                <div class="col-md-auto">
                                                    <input type="text" name="detail_province[]" id="detail_province"
                                                        placeholder="" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-auto mb-4">
                                            <div class="row">
                                                <div class="col-md-auto">
                                                    <label for="detail_zip_code">Zip Code *</label>
                                                    <br>
                                                    <p class="fs-6"
                                                        style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                        Kode Pos</p>
                                                </div>
                                                <div class="col-md-auto">
                                                    <input type="text" name="detail_zip_code[]" id="detail_zip_code"
                                                        placeholder="" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-auto mb-4">
                                            <div class="row">
                                                <div class="col-md-auto">
                                                    <label for="detail_telephone">Telephone *</label>
                                                    <p class="fs-6"
                                                        style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                        + [Country-Area Code] [No.]
                                                        Telepon +[Negara-Area] [No.]</p>
                                                </div>
                                                <div class="col-md-auto">
                                                    <input type="text" name="detail_telephone[]" id="detail_telephone"
                                                        placeholder="" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-auto mb-4">
                                            <div class="row">
                                                <div class="col-md-auto">
                                                    <label for="detail_fax">Fax *</label>
                                                    <p class="fs-6"
                                                        style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                        + [Country-Area Code] [No.]
                                                        Fax +[Negara-Area] [No.]</p>
                                                </div>
                                                <div class="col-md-auto">
                                                    <input type="text" name="detail_fax[]" id="detail_fax" placeholder=""
                                                        class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col mb-4 align-items-end mr-4">
                                            <button type="button" class="btn btn-primary float-right"
                                                id="add_detail_ynamic_address">+ Address</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="detail_dynamic_company_address">

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
                                        <label for="detail_bank_name">Bank Name *</label>
                                        <br>
                                        <p class="fs-6"
                                            style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                            Nama Bank</p>
                                    </div>
                                    <div class="col-md-auto">
                                        <input type="text" name="detail_bank_name[]" id="detail_bank_name" placeholder=""
                                            class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-auto">
                                        <label for="detail_branch">Branch *</label>
                                        <br>
                                        <p class="fs-6"
                                            style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                            Cabang</p>
                                    </div>
                                    <div class="col-md-auto">
                                        <input type="text" name="detail_branch[]" id="detail_branch" placeholder=""
                                            class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="input-group">
                            <div class="col-md-6 mb-4">
                                <div class="row">
                                    <div class="col-md-auto">
                                        <label for="detail_account_name">Account Name *</label>
                                        <br>
                                        <p class="fs-6"
                                            style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                            Rekening Atas Nama</p>
                                    </div>
                                    <div class="col-md-auto">
                                        <input type="text" name="detail_account_name[]" id="detail_account_name" placeholder=""
                                            class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="row">
                                    <div class="col-md-auto">
                                        <label for="detail_city_or_country">City/Country *</label>
                                        <br>
                                        <p class="fs-6"
                                            style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                            Kota/Negara</p>
                                    </div>
                                    <div class="col-md-auto">
                                        <input type="text" name="detail_city_or_country[]" id="detail_city_or_country"
                                            placeholder="" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="input-group">
                            <div class="col-md-auto mb-4">
                                <div class="row">
                                    <div class="col-md-auto">
                                        <label for="detail_account_number">Account No. *</label>
                                        <br>
                                        <p class="fs-6"
                                            style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                            No Rekening</p>
                                    </div>
                                    <div class="col-md-auto">
                                        <input type="text" name="detail_account_number[]" id="detail_account_number" placeholder=""
                                            class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-auto mb-4">
                                <div class="row">
                                    <div class="col-md-auto">
                                        <label for="detail_currency">Currency *</label>
                                        <br>
                                        <p class="fs-6"
                                            style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                            Mata Uang</p>
                                    </div>
                                    <div class="col-md-auto">
                                        <input type="text" name="detail_currency[]" id="detail_currency" placeholder=""
                                            class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-auto">
                                <div class="row">
                                    <div class="col-md-auto">
                                        <label for="detail_swift_code">Swift Code *</label>
                                        <br>
                                        <p class="fs-6"
                                            style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                            Optional</p>
                                    </div>
                                    <div class="col-md-auto">
                                        <input type="text" name="detail_swift_code[]" id="detail_swift_code" placeholder=""
                                            class="form-control">
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
                                        <label for="detail_register_number_as_in_tax_invoice">Tax Register Number
                                            (As in Tax Invoice) *</label>
                                        <br>
                                        <p class="fs-6"
                                            style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                            Nomor NPWP (Sesuai dengan Faktur Pajak)</p>
                                    </div>
                                    <div class="col-md-auto">
                                        <input type="text" name="detail_register_number_as_in_tax_invoice"
                                            id="detail_register_number_as_in_tax_invoice" placeholder="" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-auto mb-4">
                                <div class="row">
                                    <div class="col-md-auto">
                                        <label for="detail_trc_number">TRC No. *</label>
                                        <br>
                                        <p class="fs-6"
                                            style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                            Nomor TRC</p>
                                    </div>
                                    <div class="col-md-auto">
                                        <input type="text" name="detail_trc_number" id="detail_trc_number" placeholder=""
                                            class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="input-group">
                            <div class="col-md-auto mb-4">
                                <div class="row">
                                    <div class="col-md-auto">
                                        <label for="detail_register_number_related_branch">Tax Register Number
                                            (Related Branch) *</label>
                                        <br>
                                        <p class="fs-6"
                                            style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                            Nomor NPWP (Cabang Terkait)</p>
                                    </div>
                                    <div class="col-md-auto">
                                        <input type="text" name="detail_register_number_related_branch"
                                            id="detail_register_number_related_branch" placeholder="" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-auto mb-4">
                                <div class="row">
                                    <div class="col-md-auto">
                                        <label for="detail_valid_until">Valid Until *</label>
                                        <br>
                                        <p class="fs-6"
                                            style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                            Berlaku Sampai</p>
                                    </div>
                                    <div class="col-md-auto">
                                        <input type="date" name="detail_valid_until" id="detail_valid_until" placeholder=""
                                            class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="input-group mb-4">
                            <div class="col-md-3">
                                <label for="detail_taxable_entrepreneur_number">Taxable Entrepreneur Number
                                    *</label>
                                <p class="fs-6" style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                    Nomor Surat Pengukuhan PKP</p>
                            </div>
                            <div class="col-md-9">
                                <input type="text" name="detail_taxable_entrepreneur_number" id="detail_taxable_entrepreneur_number"
                                    placeholder="" class="form-control">
                            </div>
                        </div>
                        <div class="input-group mb-4">
                            <div class="col-md-3">
                                <label for="detail_tax_invoice_serial_number">Tax Invoice Serial No. *</label>
                                <p class="fs-6" style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                    Nomor Serial Faktur Pajak (pada SK-PKP)</p>
                            </div>
                            <div class="col-md-9">
                                <input type="text" name="detail_tax_invoice_serial_number" id="detail_tax_invoice_serial_number"
                                    placeholder="" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- end company tax --}}

            {{-- additional info --}}
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">
                        4. ADDITIONAL INFORMATION (Informasi Tambahan)
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
                            <div class="input-group mb-4">
                                <div class="col-md-2">
                                    <label for="detail_address_add_info">Another Company Address *</label>
                                    <br>
                                    <p class="fs-6"
                                        style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                        Alamat Perusahaan lainnya:</p>
                                </div>
                                <div class="col-md-10">
                                    <div class="row">
                                        <div class="col-md-6 mb-4">
                                            <input type="text" name="detail_address_add_info[]" id="detail_address_add_info"
                                                placeholder="" class="form-control">
                                        </div>
                                        <div class="col-md-auto">
                                            <div class="row">
                                                <div class="col-md-auto">
                                                    <label for="detail_city_add_info">City *</label>
                                                    <br>
                                                    <p class="fs-6"
                                                        style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                        Kota</p>
                                                </div>
                                                <div class="col-md-auto">
                                                    <input type="text" name="detail_city_add_info[]" id="detail_city_add_info"
                                                        placeholder="" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-auto mb-4">
                                            <div class="row">
                                                <div class="col-md-auto">
                                                    <label for="detail_country_add_info">Country *</label>
                                                    <br>
                                                    <p class="fs-6"
                                                        style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                        Negara</p>
                                                </div>
                                                <div class="col-md-auto">
                                                    <input type="text" name="detail_country_add_info[]" id="detail_country_add_info"
                                                        placeholder="" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-auto mb-4">
                                            <div class="row">
                                                <div class="col-md-auto">
                                                    <label for="detail_province_add_info">Province *</label>
                                                    <br>
                                                    <p class="fs-6"
                                                        style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                        Provinsi</p>
                                                </div>
                                                <div class="col-md-auto">
                                                    <input type="text" name="detail_province_add_info[]"
                                                        id="detail_province_add_info" placeholder="" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-auto mb-4">
                                            <div class="row">
                                                <div class="col-md-auto">
                                                    <label for="detail_zip_code_add_info">Zip Code *</label>
                                                    <br>
                                                    <p class="fs-6"
                                                        style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                        Kode Pos</p>
                                                </div>
                                                <div class="col-md-auto">
                                                    <input type="text" name="detail_zip_code_add_info[]"
                                                        id="detail_zip_code_add_info" placeholder="" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-auto mb-4">
                                            <div class="row">
                                                <div class="col-md-auto">
                                                    <label for="detail_telephone_add_info">Telephone *</label>
                                                    <p class="fs-6"
                                                        style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                        + [Country-Area Code] [No.]
                                                        Telepon <br>+ [Negara-Area] [No.]</p>
                                                </div>
                                                <div class="col-md-auto">
                                                    <input type="text" name="detail_telephone_add_info[]"
                                                        id="detail_telephone_add_info" placeholder="" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-auto mb-4">
                                            <div class="row">
                                                <div class="col-md-auto">
                                                    <label for="detail_fax_add_info">Fax *</label>
                                                    <p class="fs-6"
                                                        style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                        + [Country-Area Code] [No.]
                                                        Fax <br>+ [Negara-Area] [No.]</p>
                                                </div>
                                                <div class="col-md-auto">
                                                    <input type="text" name="detail_fax_add_info[]" id="detail_fax_add_info"
                                                        placeholder="" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col align-items-end mr-4">
                                            <button type="button" class="btn btn-primary float-right" id="add_info">+
                                                Address</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="dynamic_add_info">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- end additional info --}}

            {{-- support document --}}
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">
                        5. SUPPORTING DOCUMENTS (Dokumen yang harus dilengkapi)
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
                                    <a class="nav-link active" href="#detail_document_type_pt" role="tab"
                                        data-toggle="tab">PT</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#detail_document_type_cv" role="tab" data-toggle="tab">CV</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#detail_document_type_ud_or_pd" role="tab"
                                        data-toggle="tab">UD/PD</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#detail_document_type_perorangan" role="tab"
                                        data-toggle="tab">Perorangan</a>
                                </li>
                            </ul>
                            <div class="tab-content mt-4">
                                <div role="tabpanel" class="tab-pane fade" id="detail_document_type_pt">
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
                                <div role="tabpanel" class="tab-pane fade" id="detail_document_type_cv">
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
                                <div role="tabpanel" class="tab-pane fade" id="detail_document_type_ud_or_pd">
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
                                <div role="tabpanel" class="tab-pane fade" id="detail_document_type_perorangan">
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
@stop

@section('css')
@stop

@section('js')
    @include('company.detail_company_js')
@stop
