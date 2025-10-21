<div class="card card-info">
    <div class="card-header">
        <h3 class="card-title">
            @lang('messages.Survey Result')
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
                    <legend class="float-none w-auto text-bold">@lang('messages.Survey Result')</legend>
                    <div class="row mt-4">
                        <div class="col-md-4 col-lg-4 col-sm-12">
                            <label>@lang('messages.Ownership Status')</label>
                            <br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="survey_ownership_status" id="ownership_status_own" value="own">
                                <label class="form-check-label" for="ownership_status_own">@lang('messages.Own')</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="survey_ownership_status" id="ownership_status_rented" value="rented">
                                <label class="form-check-label" for="ownership_status_rented">@lang('messages.Rented')</label>
                            </div>
                            <span class="text-danger mt-2" id="message_survey_ownership_status" role="alert"></span>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-12 mb-3">
                            <label for="survey_pick_up">Pickup</label>
                            <input class="form-control" type="text" name="survey_pick_up" id="survey_pick_up" value="" placeholder="@lang('messages.Placeholder Survey Pickup')">
                            <span class="text-danger mt-2" id="message_survey_pick_up" role="alert"></span>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-12 mb-3">
                            <label for="survey_truck">Truck</label>
                            <input class="form-control" type="text" name="survey_truck" id="survey_truck" value="" placeholder="@lang('messages.Placeholder Survey Truck')">
                            <span class="text-danger mt-2" id="message_survey_truck" role="alert"></span>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-3 col-lg-3 col-sm-12 mb-3">
                            <label for="product_survey_0">@lang('messages.Product')</label>
                            <input type="text" name="product_survey[]" id="product_survey_0" class="form-control" placeholder="@lang('messages.Placeholder Survey Product')">
                            <span class="text-danger mt-2" id="message_product_survey" role="alert"></span>
                        </div>
                        <div class="col-md-3 col-lg-3 col-sm-12 mb-3">
                            <label for="merk_survey_0">@lang('messages.Merk')</label>
                            <input type="text" name="merk_survey[]" id="merk_survey_0" class="form-control" placeholder="@lang('messages.Placeholder Survey Merk')">
                            <span class="text-danger mt-2" id="message_merk_survey" role="alert"></span>
                        </div>
                        <div class="col-md-3 col-lg-3 col-sm-12 mb-3">
                            <label for="distributor_survey_0">@lang('messages.Distributor')</label>
                            <input type="text" name="distributor_survey[]" id="distributor_survey_0" class="form-control" placeholder="@lang('messages.Placeholder Survey Distributor')">
                            <span class="text-danger mt-2" id="message_distributor_survey" role="alert"></span>
                        </div>

                        <div class="col-md-3 col-lg-3 col-sm-12 mb-3">
                            <div class="input-group d-flex justify-content-end mb-4 mt-4">
                                <button type="button" class="btn btn-primary" id="add_survey_data"><i
                                        class="fa fa-plus"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="dynamic_product_survey">

                    </div>
                </fieldset>
            </div>
        </div>
    </div>
</div>
