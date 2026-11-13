@push('styles')
    @include('admin.form_links._alpha_styles')
@endpush
<div class="modal fade" id="ModalDetailPartner" tabindex="-1" role="dialog" aria-labelledby="ModalDetailPartnerLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="ModalDetailPartnerLabel" class="modal-title">{{ __('partner.Detail Partner') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('partner.Tutup') }}"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body" id="partner-public-detail-content" style="background: #f5f6fa">
                <p role="status">{{ __('partner.Memuat data partner...') }}</p>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('partner.Tutup') }}</button></div>
        </div>
    </div>
</div>
