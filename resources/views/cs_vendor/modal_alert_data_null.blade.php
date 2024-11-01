<!-- Modal -->
<div class="modal fade" id="alert_modal_data_null" data-backdrop="static" tabindex="-1" role="dialog"
    aria-labelledby="alert_modal_data_nullLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            {{-- <div class="modal-header">
                <h5 class="modal-title" id="alert_modal_data_nullLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> --}}
            <div class="modal-body">
                <h3>Kamu belum terdaftar sebagai customer atau vendor</h3>
            </div>
            <div class="modal-footer">
                {{-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> --}}
                <a href="{{ route('create-partner') }}"><button type="button" class="btn btn-primary">Daftar</button></a>
                
            </div>
        </div>
    </div>
</div>
