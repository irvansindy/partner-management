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
                <label class="col-md-3" for="company_type">@lang('messages.Company Type') <span class="text-danger"
                        role="alert">*</span></label>
                <div class="col-md-9">
                    <select name="company_type" id="company_type" class="form-control">
                        <option value="">@lang('messages.Select One')</option>
                        <option value="customer">@lang('messages.Customer')</option>
                        <option value="vendor">@lang('messages.Vendor')</option>
                    </select>
                    <span class="text-danger message-danger" id="message_company_type" role="alert"></span>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-md-5 col-lg-6 col-sm-12">
                    <label for="company_name">@lang('messages.Company Name') <span class="text-danger" role="alert">*</span></label>
                    <input type="text" name="company_name" id="company_name" class="form-control" placeholder="@lang("messages.Placeholder Company Name")">
                    <span class="text-danger message-danger" id="message_company_name" role="alert"></span>
                </div>
                <div class="col-md-5 col-lg-6 col-sm-12">
                    <label for="company_group_name">@lang('messages.Company Group Name')</label>
                    <input type="text" name="company_group_name" id="company_group_name" class="form-control" placeholder="@lang('messages.Placeholder Company Group Name')">
                    <span class="text-muted" id="message_company_group_name" role="alert">@lang('messages.Company Group Name Info')</span>
                    <span class="text-danger message-danger" id="message_company_group_name" role="alert"></span>
                </div>
                <div class="col-md-6 col-sm-12">
                    <label for="established_year">@lang('messages.Established Year')</label>
                    <input type="number" name="established_year" id="established_year" class="form-control" min="1900" max="2099" step="1" placeholder="@lang('messages.Placeholder Established Year')">
                    <span class="text-danger message-danger" id="message_established_year" role="alert"></span>
                </div>
                <div class="col-md-6 col-sm-12">
                    <label for="total_employee">@lang('messages.Total Employee')</label>
                    <input type="number" name="total_employee" id="total_employee" class="form-control" min="1"
                        max="9999" maxlength="10" placeholder="@lang('messages.Placeholder Total Employee')">
                    <span class="text-danger message-danger" id="message_total_employee" role="alert"></span>
                </div>

            </div>

            <!-- Liable Person & Owner Name -->
            <div class="row mb-4">
                <h2>@lang('messages.Liable Person') </h2>
                <div class="col-md-4 col-lg-4 col-sm-12 mb-2">
                    <label for="liable_person_0">@lang('messages.Liable Person') <span class="text-danger" role="alert">*</span></label>
                    <input type="text" name="liable_person[]" id="liable_person_0" class="form-control" placeholder="@lang('messages.Placeholder Liable Person')">
                    <span class="text-danger message-danger" id="message_liable_person" role="alert"></span>
                </div>
                <div class="col-md-4 col-lg-4 col-sm-12 mb-2">
                    <label for="liable_position_0">@lang('messages.Liable Position') <span class="text-danger"
                            role="alert">*</span></label>
                    <select name="liable_position[]" id="liable_position_0" class="form-control liable-position-select">
                        <option value="">-- @lang('messages.Placeholder Position') --</option>
                        <option value="Owner">@lang('messages.Owner')</option>
                        <option value="Board of Directors">@lang('messages.Board of Directors')</option>
                        <option value="Shareholders">@lang('messages.Shareholders')</option>
                        <option value="Finance Department">@lang('messages.Finance Department')</option>
                        <option value="Purchase/Procure Department">@lang('messages.Purchase/Procure Department')</option>
                        <option value="Sales Department">@lang('messages.Sales Department')</option>
                        <option value="Other">@lang('messages.Other')</option>
                    </select>
                    <div class="mt-2" id="other_position_container_0" style="display: none;">
                        <input type="text" name="other_position[]" id="other_position_0" class="form-control" placeholder="@lang('messages.Specify Position')">
                        <span class="text-danger message-danger" id="message_other_position_0" role="alert"></span>
                    </div>
                    <span class="text-danger message-danger" id="message_liable_position_0" role="alert"></span>
                </div>
                <div class="col-md-4 col-lg-4 col-sm-12 mb-2">
                    <label for="nik_0">NIK <span class="text-danger" role="alert">*</span></label>
                    <input type="text" name="nik[]" id="nik_0" class="form-control" placeholder="@lang('messages.Placeholder NIK')">
                    <span class="text-danger message-danger" id="message_nik" role="alert"></span>
                </div>
            </div>
            <div class="input-group d-flex justify-content-end mr-4 mb-4 mt-4">
                <button type="button" class="btn btn-primary" id="add_liable_person">
                    <i class="fas fa-plus"></i>
                </button>
            </div>
            <div class="dynamic_liable_person"></div>
            <!-- Business Classification -->
            <div class="row mb-4">
                <label class="col-md-3">@lang('messages.Business Classification') <span class="text-danger" role="alert">*</span></label>
                <div class="col-md-9">
                    <select class="form-select" name="business_classification" id="business_classification">
                        <option value="">-- @lang('messages.Placeholder Business Classification') --</option>
                        @foreach (\App\Models\MasterBusinessClassification::all() as $item)
                            <option value="{{ $item->name }}">{{ $item->name }}</option>
                        @endforeach
                    </select>

                    <!-- field tambahan muncul kalau pilih "Other" -->
                    <div class="col-md-auto my-2" id="field_form_create_business_other"></div>

                    <span class="text-danger message-danger" id="message_business_classification"
                        role="alert"></span>
                </div>

            </div>

            <!-- Business Classification Detail -->
            <div class="row mb-4">
                <label class="col-md-3" for="business_classification_detail">@lang('messages.Business Classification Detail')</label>
                <div class="col-md-9">
                    <textarea class="form-control" name="business_classification_detail" id="business_classification_detail" rows="4" placeholder="@lang('messages.Placeholder Business Classification Detail')"></textarea>
                    <span class="text-danger message-danger" id="message_business_classification_detail"
                        role="alert"></span>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-md-6 col-lg-6 col-sm-12 mb-2">
                    <label for="register_number_as_in_tax_invoice">@lang('messages.Tax Register Number (As in Tax Invoice)') <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="register_number_as_in_tax_invoice" name="register_number_as_in_tax_invoice" placeholder="@lang('messages.Placeholder Tax')">
                </div>
                <div class="col-md-6 col-lg-6 col-sm-12 mb-2">
                    <label for="website_address">@lang('messages.Website Address')</label>
                    <input type="text" name="website_address" id="website_address" class="form-control" placeholder="@lang('messages.Placeholder Website')">
                    <span class="text-danger message-danger" id="message_website_address" role="alert"></span>
                </div>
                <div class="col-md-6 col-lg-6 col-sm-12 mb-2">
                    <label for="system_management">@lang('messages.Certificate Type')</label>
                    <input type="text" name="system_management" id="system_management" class="form-control" placeholder="@lang('messages.Placeholder Certificate')">
                    <span class="text-danger mt-2" id="message_system_management" role="alert"></span>
                </div>
                <div class="col-md-6 col-lg-6 col-sm-12 mb-2">
                    <label for="email_address">@lang('messages.Email Address')</label>
                    <input type="text" name="email_address" id="email_address" class="form-control" placeholder="@lang('messages.Placeholder Email')">
                    <span class="text-danger mt-2" id="message_email_address" role="alert"></span>
                </div>
                <div class="col-md-6 col-lg-6 col-sm-12 mb-2">
                    <label for="credit_limit">@lang('messages.Credit Limit')</label>
                    <input type="number" name="credit_limit" id="credit_limit" class="form-control" min="0"
                        max="9999999999" maxlength="25" placeholder="@lang('messages.Placeholder Credit Limit')">
                    <span class="text-danger mt-2" id="message_credit_limit" role="alert"></span>
                </div>
                <div class="col-md-6 col-lg-6 col-sm-12 mb-2">
                    <label for="term_of_payment">@lang('messages.Term of Payment')</label>
                    <select name="term_of_payment" id="term_of_payment" class="form-control">
                        <option value="">-- @lang('messages.Placeholder TOP') --</option>
                        <option value="cash">@lang('messages.Cash')</option>
                        <option value="14">14 @lang('messages.Day')</option>
                        <option value="30">30 @lang('messages.Day')</option>
                        <option value="45">45 @lang('messages.Day')</option>
                        <option value="60">60 @lang('messages.Day')</option>
                        <option value="90">90 @lang('messages.Day')</option>
                        <option value="Other">@lang('messages.Other')</option>
                    </select>
                    <div class="mt-2" id="other_term_of_payment_container">

                    </div>
                    <span class="text-danger mt-2" id="message_term_of_payment" role="alert"></span>
                </div>
            </div>

        </div>

    </div>
</div>
