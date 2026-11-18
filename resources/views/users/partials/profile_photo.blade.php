<div class="modal" id="profilePhotoModal" tabindex="-1" role="dialog" aria-labelledby="profilePhotoTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="profilePhotoTitle">{{ __('profile.change_photo') }}</h5>
                <button type="button" class="close profile-photo-close" aria-label="{{ __('messages.User Management Close') }}"><span aria-hidden="true">&times;</span></button>
            </div>
            <form id="profilePhotoForm" action="{{ route('profile.photo.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <img id="profilePhotoPreview" src="{{ $user->avatar_url }}" alt="{{ __('profile.photo') }}">
                    <label for="profilePhotoFile">{{ __('profile.photo') }}</label>
                    <input type="file" id="profilePhotoFile" name="avatar" accept="image/jpeg,image/png,image/webp" required aria-describedby="profilePhotoHint profilePhotoError">
                    <small id="profilePhotoHint" class="text-muted d-block mt-2">{{ __('profile.photo_hint') }}</small>
                    <div id="profilePhotoError" class="text-danger mt-2" role="alert">{{ $errors->photoUpdate->first('avatar') }}</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light profile-photo-close">{{ __('messages.User Management Cancel') }}</button>
                    <button type="submit" class="btn btn-primary" id="profilePhotoSave">{{ __('profile.photo_save') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
<style>
    #profilePhotoModal .modal-dialog { max-width: 520px; }
    #profilePhotoPreview { display: block; width: 144px; height: 144px; object-fit: cover; border: 1px solid #e3e8ef; border-radius: 12px; margin: 0 auto 24px; }
    #profilePhotoModal label { display: block; position: static; margin-bottom: 10px; }
    #profilePhotoFile { width: 100%; border: 1px solid #e3e8ef; border-radius: 6px; padding: 10px; }
    #profilePhotoModal .modal-footer { display: flex; flex-wrap: wrap; gap: 10px; }
    #profilePhotoModal .modal-footer .btn { margin: 0; min-height: 44px; }
    @media (max-width: 575px) { #profilePhotoModal .modal-footer .btn { width: 100%; } }
</style>
@push('scripts')
<script>
    (function () {
        const modal = $('#profilePhotoModal');
        const form = document.getElementById('profilePhotoForm');
        const input = document.getElementById('profilePhotoFile');
        const preview = document.getElementById('profilePhotoPreview');
        const initialSource = preview.src;
        const error = document.getElementById('profilePhotoError');
        const save = document.getElementById('profilePhotoSave');
        let objectUrl;
        let processing = false;
        function releasePreview() {
            processing = false;
            if (objectUrl) URL.revokeObjectURL(objectUrl);
            objectUrl = null;
        }
        function invalid() {
            input.value = '';
            error.textContent = @json(__('profile.photo_invalid'));
            releasePreview();
            preview.src = initialSource;
        }
        $('#openProfilePhoto').on('click', function () { modal.modal('show'); });
        $('.profile-photo-close').on('click', function () { if (!save.disabled) modal.modal('hide'); });
        modal.on('hidden.bs.modal', function () {
            form.reset();
            releasePreview();
            preview.src = initialSource;
            error.textContent = '';
            document.getElementById('openProfilePhoto').focus();
        });
        input.addEventListener('change', function () {
            releasePreview();
            error.textContent = '';
            const file = input.files[0];
            if (!file) { preview.src = initialSource; return; }
            if (!['image/jpeg', 'image/png', 'image/webp'].includes(file.type) || file.size > 2 * 1024 * 1024) { invalid(); return; }
            objectUrl = URL.createObjectURL(file);
            const selectedUrl = objectUrl;
            processing = true;
            const image = new Image();
            image.onload = function () {
                if (objectUrl !== selectedUrl) return;
                processing = false;
                if (image.width > 2048 || image.height > 2048) { invalid(); return; }
                preview.src = selectedUrl;
            };
            image.onerror = function () {
                if (objectUrl !== selectedUrl) return;
                processing = false;
                invalid();
            };
            image.src = selectedUrl;
        });
        form.addEventListener('submit', function (event) {
            if (processing || save.disabled || !form.checkValidity()) { event.preventDefault(); return; }
            save.disabled = true;
            save.innerHTML = '<span class="spinner-border spinner-border-sm" aria-hidden="true"></span> ' + @json(__('user_password.saving'));
        });
        window.addEventListener('pageshow', function () {
            save.disabled = false;
            save.textContent = @json(__('profile.photo_save'));
        });
        @if($errors->photoUpdate->any())
            modal.modal('show');
        @endif
    })();
</script>
@endpush
