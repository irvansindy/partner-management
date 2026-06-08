<div class="card card-info">
    <div class="card-header">
        <h3 class="card-title">
            @lang('messages.Survey Result')
        </h3>
    </div>
    <div class="card-body">
        <div class="company_contact_additional" id="company_contact_additional">
            <fieldset class="border px-3 py-3 mb-4 rounded">
                <legend class="float-none w-auto px-2 text-bold">@lang('messages.Survey Result')</legend>

                {{-- =============================================
                     ROW 1: Status Kepemilikan (kiri) | Pickup (kanan)
                ============================================= --}}
                <div class="row mt-3 mb-3">
                    <div class="col-md-6 col-sm-12">
                        <label>@lang('messages.Ownership Status')</label>
                        <div class="mt-2">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio"
                                    name="survey_ownership_status"
                                    id="ownership_status_own" value="own">
                                <label class="form-check-label" for="ownership_status_own">@lang('messages.Own')</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio"
                                    name="survey_ownership_status"
                                    id="ownership_status_rented" value="rented">
                                <label class="form-check-label" for="ownership_status_rented">@lang('messages.Rented')</label>
                            </div>
                        </div>
                        <span class="text-danger mt-2" id="message_survey_ownership_status" role="alert"></span>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <label for="survey_pick_up">Pickup</label>
                        <input class="form-control" type="text" name="survey_pick_up"
                            id="survey_pick_up" value=""
                            placeholder="@lang('messages.Placeholder Survey Pickup')">
                        <span class="text-danger mt-2" id="message_survey_pick_up" role="alert"></span>
                    </div>
                </div>

                {{-- =============================================
                     ROW 2: Truck (kiri) | (kanan kosong — bisa diisi field lain jika ada)
                     Saat ini truck berdiri sendiri, ditempatkan kiri agar konsisten
                ============================================= --}}
                <div class="row mb-3">
                    <div class="col-md-6 col-sm-12">
                        <label for="survey_truck">Truck</label>
                        <input class="form-control" type="text" name="survey_truck"
                            id="survey_truck" value=""
                            placeholder="@lang('messages.Placeholder Survey Truck')">
                        <span class="text-danger mt-2" id="message_survey_truck" role="alert"></span>
                    </div>
                </div>

                {{-- =============================================
                     ROW 3+: Produk | Merk | Distributor | Tombol tambah
                ============================================= --}}
                <div class="row mb-3">
                    <div class="col-md-4 col-sm-12">
                        <label for="product_survey_0">@lang('messages.Product')</label>
                        <input type="text" name="product_survey[]" id="product_survey_0"
                            class="form-control"
                            placeholder="@lang('messages.Placeholder Survey Product')">
                        <span class="text-danger mt-2" id="message_product_survey" role="alert"></span>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <label for="merk_survey_0">@lang('messages.Merk')</label>
                        <input type="text" name="merk_survey[]" id="merk_survey_0"
                            class="form-control"
                            placeholder="@lang('messages.Placeholder Survey Merk')">
                        <span class="text-danger mt-2" id="message_merk_survey" role="alert"></span>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <label for="distributor_survey_0">@lang('messages.Distributor')</label>
                        <input type="text" name="distributor_survey[]" id="distributor_survey_0"
                            class="form-control"
                            placeholder="@lang('messages.Placeholder Survey Distributor')">
                        <span class="text-danger mt-2" id="message_distributor_survey" role="alert"></span>
                    </div>
                </div>
                <div class="d-flex justify-content-end mb-2">
                    <button type="button" class="btn btn-primary" id="add_survey_data">
                        <i class="fa fa-plus"></i> @lang('messages.Product')
                    </button>
                </div>

                <div class="dynamic_product_survey"></div>
            </fieldset>
        </div>
    </div>
</div>