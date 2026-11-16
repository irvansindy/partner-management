@extends('adminlte::page')

@section('title', __('supporting.Detail Company'))

@section('content_header')
    <h1>{{ __('supporting.Partner Data') }}
        <br>{{ __('supporting.Customer Selection') }}
    </h1>
@stop

@section('content')
    <div class="container-fluid">
        <form action="" method="" id="form_detail_company_partner">
            {{-- company information master --}}
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">{{ __('supporting.Company Information') }}</h3>
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
                                <label for="detail_company_type">{{ __('supporting.Company Type') }} *</label>
                                <br>
                                <p class="fs-6" style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                    {{ __('supporting.Company Type') }}</p>
                            </div>
                            <div class="col-md-9">
                                <select name="detail_company_type" id="detail_company_type" class="form-control">
                                    <option value="customer">{{ __('supporting.Customer') }}</option>
                                    <option value="vendor">{{ __('supporting.Vendor') }}</option>
                                    <option value="customer dan vendor">{{ __('supporting.Both') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="input-group my-4">
                            <div class="col-md-3">
                                <label for="detail_company_name">{{ __('supporting.Company Name') }} *</label>
                                <br>
                                <p class="fs-6" style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                    {{ __('supporting.Company Name') }}</p>
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
                                        <label for="detail_company_group_name">{{ __('supporting.Company Group Name') }} *</label>
                                        <br>
                                        <p class="fs-6"
                                            style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                            {{ __('supporting.Company Group Name') }}</p>
                                    </div>
                                    <div class="col-md-auto">
                                        <input type="text" name="detail_company_group_name"
                                            id="detail_company_group_name" placeholder="" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <div class="row">
                                    <div class="col-md-auto">
                                        <label for="detail_established_year">{{ __('supporting.Established Since (Year)') }} *</label>
                                        <br>
                                        <p class="fs-6"
                                            style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                            {{ __('supporting.Established Year') }}</p>
                                    </div>
                                    <div class="col-md-auto">
                                        <input type="text" name="detail_established_year" id="detail_established_year"
                                            placeholder="" class="form-control">
                                    </div>

                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="row">
                                    <div class="col-md-auto">
                                        <label for="detail_total_employee">{{ __('supporting.Total Employee') }} *</label>
                                        <br>
                                        <p class="fs-6"
                                            style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                            {{ __('supporting.Total Employee') }}</p>
                                    </div>
                                    <div class="col-md-auto">
                                        <input type="text" name="detail_total_employee" id="detail_total_employee"
                                            placeholder="" class="form-control">
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="input-group mb-4">
                            <div class="col-md-6 mb-4">
                                <div class="row">
                                    <div class="col-md-auto">
                                        <label for="detail_liable_person_and_position">{{ __('supporting.Liable Person & Position') }} *</label>
                                        <br>
                                        <p class="fs-6"
                                            style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                            {{ __('supporting.Liable Person & Position') }}</p>
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
                                        <label for="detail_owner_name">{{ __('supporting.Owner Name') }} *</label>
                                        <br>
                                        <p class="fs-6"
                                            style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                            {{ __('supporting.Owner Name') }}</p>
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
                                        <label for="detail_board_of_directors">{{ __('supporting.Board of Directors') }} *</label>
                                        <br>
                                        <p class="fs-6"
                                            style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                            {{ __('supporting.Board of Directors') }}</p>
                                    </div>
                                    <div class="col-md-7">
                                        <input type="text" name="detail_board_of_directors"
                                            id="detail_board_of_directors" placeholder="" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-auto">
                                        <label for="detail_major_shareholders">{{ __('supporting.Major Shareholders') }} *</label>
                                        <br>
                                        <p class="fs-6"
                                            style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                            {{ __('supporting.Major Shareholders') }}</p>
                                    </div>
                                    <div class="col-md-7">
                                        <input type="text" name="detail_major_shareholders"
                                            id="detail_major_shareholders" placeholder="" class="form-control">
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="input-group mb-4">
                            <div class="col-md-auto">
                                <label for="">{{ __('supporting.Business Classification') }} *</label>
                                <br>
                                <p class="fs-6" style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                    {{ __('supporting.Business Classification') }}</p>
                            </div>
                            <div class="col-md-auto">
                                <div class="row px-2">
                                    <div class="col-md-auto" id="list_business_classification">
                                        <div class="form-check form-check-inline mb-1">
                                            <input class="form-check-input" style="margin-bottom: 1rem;" type="radio"
                                                name="detail_business_classification"
                                                id="detail_business_classification_manufacturer" value="Manufacturer">
                                            <label class="form-check-label"
                                                for="detail_business_classification_manufacturer">
                                                {{ __('supporting.Manufacturer') }}
                                                <p class="fs-6"
                                                    style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                    {{ __('supporting.Manufacturer') }}</p>
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline mb-1">
                                            <input class="form-check-input" style="margin-bottom: 1rem;" type="radio"
                                                name="detail_business_classification"
                                                id="detail_business_classification_trading" value="Trading">
                                            <label class="form-check-label" for="detail_business_classification_trading">
                                                {{ __('supporting.Trading') }}
                                                <p class="fs-6"
                                                    style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                    {{ __('supporting.Trading') }}</p>
                                            </label>
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline mb-1">
                                            <input class="form-check-input" style="margin-bottom: 1rem;" type="radio"
                                                name="detail_business_classification"
                                                id="detail_business_classification_agent" value="Agent">
                                            <label class="form-check-label" for="detail_business_classification_agent">
                                                {{ __('supporting.Agent') }}
                                                <p class="fs-6"
                                                    style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                    {{ __('supporting.Agent') }}</p>
                                            </label>
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline mb-1">
                                            <input class="form-check-input" style="margin-bottom: 1rem;" type="radio"
                                                name="detail_business_classification"
                                                id="detail_business_classification_distributor" value="Distributor">
                                            <label class="form-check-label"
                                                for="detail_business_classification_distributor">
                                                {{ __('supporting.Distributor') }}
                                                <p class="fs-6"
                                                    style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                    {{ __('supporting.Distributor') }}</p>
                                            </label>
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline mb-1">
                                            <input class="form-check-input" style="margin-bottom: 1rem;" type="radio"
                                                name="detail_business_classification"
                                                id="detail_business_classification_services" value="Services">
                                            <label class="form-check-label" for="detail_business_classification_services">
                                                {{ __('supporting.Services') }}
                                                <p class="fs-6"
                                                    style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                    {{ __('supporting.Services') }}</p>
                                            </label>
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline mb-1">
                                            <input class="form-check-input" style="margin-bottom: 1rem;" type="radio"
                                                name="detail_business_classification"
                                                id="detail_business_classification_contractor" value="Contractor">
                                            <label class="form-check-label"
                                                for="detail_business_classification_contractor">
                                                {{ __('supporting.Contractor') }}
                                                <p class="fs-6"
                                                    style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                    {{ __('supporting.Contractor') }}</p>
                                            </label>
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline mb-1">
                                            <input class="form-check-input" style="margin-bottom: 1rem;" type="radio"
                                                name="detail_business_classification"
                                                id="detail_business_classification_other" value="Other">
                                            <label class="form-check-label" for="detail_business_classification_other">
                                                {{ __('supporting.Other') }}
                                                <p class="fs-6"
                                                    style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                    {{ __('supporting.Other') }}</p>
                                            </label>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-auto" id="field_form_detail_business_other">

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="input-group mb-4">
                            <div class="col-md-3">
                                <label for="detail_website_address">{{ __('supporting.Website') }} *</label>
                                <br>
                                <p class="fs-6" style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                    {{ __('supporting.Website') }}</p>
                            </div>
                            <div class="col-md-9">
                                <input type="text" name="detail_website_address" id="detail_website_address"
                                    placeholder="" class="form-control">
                            </div>
                        </div>
                        <div class="input-group mb-4">
                            <div class="col-md-auto">
                                <label for="">{{ __('supporting.System Management') }} *</label>
                                <br>
                                <p class="fs-6" style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                    {{ __('supporting.Business Classification') }}</p>
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
                                        name="detail_system_management" id="detail_system_management_other"
                                        value="Others Certificate">
                                    <label class="form-check-label" for="detail_system_management_other">
                                        {{ __('supporting.Others Certificate') }}
                                        <p class="fs-6"
                                            style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                            {{ __('supporting.Others Certificate') }}</p>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="input-group mb-4">
                            <div class="col-md-6 mb-4">
                                <div class="row">
                                    <div class="col-md-auto">
                                        <label for="detail_contact_person">{{ __('supporting.Contact Persons') }} *</label>
                                        <br>
                                        <p class="fs-6"
                                            style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                            {{ __('supporting.Contact Person') }}</p>
                                    </div>
                                    <div class="col-md-auto">
                                        <input type="text" name="detail_contact_person" id="detail_contact_person"
                                            placeholder="" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-auto">
                                        <label for="detail_communication_language">{{ __('supporting.Communication Language') }} *</label>
                                        <br>
                                        <p class="fs-6"
                                            style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                            {{ __('supporting.Communication Language') }}</p>
                                    </div>
                                    <div class="col-md-auto detail_option_languange">
                                        <div class="form-check form-check-inline mb-1">
                                            <input class="form-check-input" style="margin-bottom: 1rem !important"
                                                type="radio" name="detail_communication_language"
                                                id="detail_communication_language_bahasa" value="Bahasa">
                                            <label class="form-check-label" for="detail_communication_language_bahasa">
                                                {{ __('supporting.Indonesian') }}
                                                <p class="fs-6"
                                                    style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                    {{ __('supporting.Indonesian') }}</p>
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline mb-1">
                                            <input class="form-check-input" style="margin-bottom: 1rem !important"
                                                type="radio" name="detail_communication_language"
                                                id="detail_communication_language_english" value="English">
                                            <label class="form-check-label" for="detail_communication_language_english">
                                                {{ __('supporting.English') }}
                                                <p class="fs-6"
                                                    style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                    {{ __('supporting.English') }}</p>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="input-group mb-4">
                            <div class="col-md-3">
                                <label for="detail_email_address">{{ __('supporting.Correspondence Email') }} *</label>
                                <p class="fs-6" style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                    {{ __('supporting.Correspondence Email') }}</p>
                            </div>
                            <div class="col-md-9">
                                <input type="text" name="detail_email_address" id="detail_email_address"
                                    placeholder="" class="form-control">
                            </div>
                        </div>
                        <div class="input-group mb-4">
                            <div class="col-sm-6">
                                <label for="detail_stamp_file">{{ __('supporting.Stamp') }} *</label>
                                <p class="fs-6" style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                    {{ __('supporting.Stamp') }}</p>
                                <input type="file" name="detail_stamp_file" id="detail_stamp_file"
                                    class="form-control" />
                                <a id="link_stamp" target="_blank"><i class="fas fa-file"></i> {{ __('supporting.Your Stamp') }}</a>
                            </div>
                            <div class="col-sm-6">
                                <label for="detail_signature_file">{{ __('supporting.Signature') }} *</label>
                                <p class="fs-6" style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                    {{ __('supporting.Signature') }}</p>
                                <input type="file" name="detail_signature_file" id="detail_signature_file"
                                    class="form-control" />
                                <a id="link_signature" target="_blank"><i class="fas fa-file"></i> {{ __('supporting.Your Signature') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="detail_company_address_additional" id="detail_company_address_additional">
                        {{-- <fieldset>
                            </fieldset> --}}
                        <div class="input-group mb-4">
                            <div class="col-md-2">
                                <label for="detail_address">{{ __('supporting.Company Address') }} *<br>
                                    {{ __('supporting.Tax address hint') }} *</label>
                                <br>
                                <p class="fs-6" style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                    {{ __('supporting.Tax address hint') }}</p>
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
                                                <label for="detail_city">{{ __('supporting.City') }} *</label>
                                                <br>
                                                <p class="fs-6"
                                                    style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                    {{ __('supporting.City') }}</p>
                                            </div>
                                            <div class="col-md-auto">
                                                <input type="text" name="detail_city[]" id="detail_city"
                                                    placeholder="" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-auto mb-4">
                                        <div class="row">
                                            <div class="col-md-auto">
                                                <label for="detail_country">{{ __('supporting.Country') }} *</label>
                                                <br>
                                                <p class="fs-6"
                                                    style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                    {{ __('supporting.Country') }}</p>
                                            </div>
                                            <div class="col-md-auto">
                                                <input type="text" name="detail_country[]" id="detail_country"
                                                    placeholder="" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-auto mb-4">
                                        <div class="row">
                                            <div class="col-md-auto">
                                                <label for="detail_province">{{ __('supporting.Province') }} *</label>
                                                <br>
                                                <p class="fs-6"
                                                    style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                    {{ __('supporting.Province') }}</p>
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
                                                <label for="detail_zip_code">{{ __('supporting.Postal Code') }} *</label>
                                                <br>
                                                <p class="fs-6"
                                                    style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                    {{ __('supporting.Postal Code') }}</p>
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
                                                <label for="detail_telephone">{{ __('supporting.Telephone') }} *</label>
                                                <p class="fs-6"
                                                    style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                    {{ __('supporting.Phone format hint') }}</p>
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
                                                <label for="detail_fax">{{ __('supporting.Fax') }} *</label>
                                                <p class="fs-6"
                                                    style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                                    {{ __('supporting.Phone format hint') }}</p>
                                            </div>
                                            <div class="col-md-auto">
                                                <input type="text" name="detail_fax[]" id="detail_fax" placeholder=""
                                                    class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col mb-4 align-items-end mr-4">
                                        <button type="button" class="btn btn-primary float-right"
                                            id="add_detail_dynamic_address">{{ __('supporting.Add Address') }}</button>
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
                {{ __('supporting.Bank Information') }}
            </h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="row px-2 py-2" id="list_data_bank" style="line-height: 1">
                <div class="input-group mt-4">
                    <div class="col-md-6 mb-4">
                        <div class="row">
                            <div class="col-md-auto">
                                <label for="detail_bank_name">{{ __('supporting.Bank Name') }} *</label>
                                <br>
                                <p class="fs-6" style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                    {{ __('supporting.Bank Name') }}</p>
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
                                <label for="detail_branch">{{ __('supporting.Branch') }} *</label>
                                <br>
                                <p class="fs-6" style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                    {{ __('supporting.Branch') }}</p>
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
                                <label for="detail_account_name">{{ __('supporting.Account Name') }} *</label>
                                <br>
                                <p class="fs-6" style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                    {{ __('supporting.Account Name') }}</p>
                            </div>
                            <div class="col-md-auto">
                                <input type="text" name="detail_account_name[]" id="detail_account_name"
                                    placeholder="" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="row">
                            <div class="col-md-auto">
                                <label for="detail_city_or_country">{{ __('supporting.City/Country') }} *</label>
                                <br>
                                <p class="fs-6" style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                    {{ __('supporting.City/Country') }}</p>
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
                                <label for="detail_account_number">{{ __('supporting.Account No.') }} *</label>
                                <br>
                                <p class="fs-6" style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                    {{ __('supporting.Account No.') }}</p>
                            </div>
                            <div class="col-md-auto">
                                <input type="text" name="detail_account_number[]" id="detail_account_number"
                                    placeholder="" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-auto mb-4">
                        <div class="row">
                            <div class="col-md-auto">
                                <label for="detail_currency">{{ __('supporting.Currency') }} *</label>
                                <br>
                                <p class="fs-6" style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                    {{ __('supporting.Currency') }}</p>
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
                                <label for="detail_swift_code">{{ __('supporting.Swift Code') }} *</label>
                                <br>
                                <p class="fs-6" style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                    {{ __('supporting.Optional') }}</p>
                            </div>
                            <div class="col-md-auto">
                                <input type="text" name="detail_swift_code[]" id="detail_swift_code" placeholder=""
                                    class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="input-group d-flex justify-content-end mr-4 mb-4 mt-4">
                    <button type="button" class="btn btn-primary" id="add_bank"> {{ __('supporting.Add Bank') }}</button>
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
                {{ __('supporting.Tax Information') }}
            </h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="row px-2 py-2" id="list_detail_tax" style="line-height: 1">
                <div class="input-group mt-4">
                    <div class="col-md-auto mb-4">
                        <div class="row">
                            <div class="col-md-auto">
                                <label for="detail_register_number_as_in_tax_invoice">{{ __('supporting.Tax Register Number (As in Tax Invoice)') }} *</label>
                                <br>
                                <p class="fs-6" style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                    {{ __('supporting.Tax Register Number (As in Tax Invoice)') }}</p>
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
                                <label for="detail_trc_number">{{ __('supporting.TRC No.') }} *</label>
                                <br>
                                <p class="fs-6" style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                    {{ __('supporting.TRC No.') }}</p>
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
                                <label for="detail_register_number_related_branch">{{ __('supporting.Tax Register Number (Related Branch)') }} *</label>
                                <br>
                                <p class="fs-6" style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                    {{ __('supporting.Tax Register Number (Related Branch)') }}</p>
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
                                <label for="detail_valid_until">{{ __('supporting.Valid Until') }} *</label>
                                <br>
                                <p class="fs-6" style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                                    {{ __('supporting.Valid Until') }}</p>
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
                        <label for="detail_taxable_entrepreneur_number">{{ __('supporting.Taxable Entrepreneur Number') }} *</label>
                        <p class="fs-6" style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                            {{ __('supporting.Taxable Entrepreneur Number') }}</p>
                    </div>
                    <div class="col-md-9">
                        <input type="text" name="detail_taxable_entrepreneur_number"
                            id="detail_taxable_entrepreneur_number" placeholder="" class="form-control">
                    </div>
                </div>
                <div class="input-group mb-4">
                    <div class="col-md-3">
                        <label for="detail_tax_invoice_serial_number">{{ __('supporting.Tax Invoice Serial No.') }} *</label>
                        <p class="fs-6" style="margin-bottom: 0.5rem !important; font-size: 10px !important;">
                            {{ __('supporting.Tax Invoice Serial No.') }}</p>
                    </div>
                    <div class="col-md-9">
                        <input type="text" name="detail_tax_invoice_serial_number"
                            id="detail_tax_invoice_serial_number" placeholder="" class="form-control">
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
                {{ __('supporting.Supporting Documents') }}
            </h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="row px-2 py-2" style="line-height: 1">
                <div class="container-fluid">
                    <ul class="nav nav-tabs mb-4" role="tablist">
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
                                data-toggle="tab">{{ __('supporting.Individual') }}</a>
                        </li>
                    </ul>
                    <div class="tab-content mt-5">
                        <div role="tabpanel" class="tab-pane fade table-responsive-lg show" id="detail_document_type_pt">
                            <table class="table table-hover w-100">
                                <thead>
                                    <tr>
                                        <th>{{ __('supporting.Document Type') }}</th>
                                        <th>{{ __('supporting.Document File') }}</th>
                                    </tr>
                                </thead>
                                <tbody id="data_doc_type_pt">
                                </tbody>
                            </table>
                        </div>
                        <div role="tabpanel" class="tab-pane fade table-responsive-lg" id="detail_document_type_cv">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>{{ __('supporting.Document Type') }}</th>
                                        <th>{{ __('supporting.Document File') }}</th>
                                    </tr>
                                </thead>
                                <tbody id="data_doc_type_cv">
                                </tbody>
                            </table>
                        </div>
                        <div role="tabpanel" class="tab-pane fade table-responsive-lg"
                            id="detail_document_type_ud_or_pd">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>{{ __('supporting.Document Type') }}</th>
                                        <th>{{ __('supporting.Document File') }}</th>
                                    </tr>
                                </thead>
                                <tbody id="data_doc_type_ud_or_pd">
                                </tbody>
                            </table>
                        </div>
                        <div role="tabpanel" class="tab-pane fade table-responsive-lg"
                            id="detail_document_type_perorangan">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>{{ __('supporting.Document Type') }}</th>
                                        <th>{{ __('supporting.Document File') }}</th>
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
        <div class="card-footer" id="button_partner">

        </div>
    </div>
    {{-- end support document --}}
    </form>
    </div>
@stop

@section('css')
    <!-- select 2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container--default .select2-selection--single {
            background-color: #f8fafc !important;
        }

        .select2-container .select2-selection--single {
            height: 38px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px !important;
        }
    </style>
@stop

@section('js')
    <!-- select 2 -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    @include('company.detail_company_js')
@stop
