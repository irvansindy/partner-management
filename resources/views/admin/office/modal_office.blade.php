<!-- Modal -->
<div class="modal fade" id="formOffice" data-backdrop="static" role="dialog" aria-labelledby="formOfficeLabel" aria-hidden="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <span class="text-primary text-uppercase small font-weight-bold">{{ __('messages.Office Setting Title') }}</span>
                    <h1 class="modal-title fs-5 mb-1" id="formOfficeLabel"></h1>
                    <p class="text-muted mb-0">{{ __('messages.Office Setting Form Description') }}</p>
                </div>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="{{ __('messages.Office Setting Close') }}"></button>
            </div>
            <form action="" id="form_data_office">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="office_name">{{ __('messages.Office Setting Name') }} <span class="text-danger">*</span></label>
                        <input type="hidden" class="form-control" name="office_id" id="office_id">
                        <input type="text" class="form-control" name="office_name" id="office_name" placeholder="{{ __('messages.Office Setting Name Placeholder') }}" autocomplete="organization">
                        <span class="text-danger mt-2 message_office_name" id="message_office_name" role="alert"></span>
                    </div>
                    <div class="form-group">
                        <label for="office_address">{{ __('messages.Office Setting Address') }} <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="office_address" id="office_address" rows="4" placeholder="{{ __('messages.Office Setting Address Placeholder') }}" autocomplete="street-address"></textarea>
                        <span class="text-danger mt-2 message_office_address" id="message_office_address" role="alert"></span>
                    </div>
                </div>
                <div class="modal-footer" id="button_action_office">
                    
                </div>
            </form>
        </div>
    </div>
</div>
