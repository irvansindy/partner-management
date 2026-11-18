@extends('layouts.main')

@section('content')
    @php
        $nameParts = preg_split('/\s+/u', trim($user->name), -1, PREG_SPLIT_NO_EMPTY);
        $initials = collect($nameParts)->take(2)->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))->join('');
    @endphp
    <div class="profile-page">
        <div class="profile-page-heading">
            <h2>{{ __('profile.title') }}</h2>
            <p>{{ __('profile.description') }}</p>
        </div>
        @if(session('password_updated'))
            <div class="alert alert-success" role="status">{{ session('password_updated') }}</div>
        @endif
        @if(session('photo_updated'))
            <div class="alert alert-success" role="status">{{ session('photo_updated') }}</div>
        @endif
        <div class="profile-overview">
            <aside class="profile-identity" aria-label="{{ __('messages.User Management Full Name') }}">
                <div class="profile-identity-banner" aria-hidden="true"></div>
                <div class="profile-identity-content">
                    <button type="button" class="profile-avatar-trigger" id="openProfilePhoto" aria-label="{{ __('profile.change_photo') }}" title="{{ __('profile.change_photo') }}" aria-haspopup="dialog" aria-controls="profilePhotoModal">
                        <span class="profile-avatar" aria-hidden="true">
                            @if($user->avatar_path)
                                <img src="{{ $user->avatar_url }}" alt="">
                            @else
                                {{ $initials }}
                            @endif
                        </span>
                        <span class="profile-photo-edit"><i class="fas fa-camera" aria-hidden="true"></i></span>
                    </button>
                    <h3>{{ $user->name }}</h3>
                    <p class="profile-email">{{ $user->email }}</p>
                    <div class="profile-role">
                        <span>{{ __('messages.User Management Role') }}</span>
                        <strong>{{ $user->roles->pluck('name')->join(', ') ?: __('messages.User Management Not Set') }}</strong>
                    </div>
                </div>
            </aside>
            <section class="profile-details" aria-labelledby="profile-account-title">
                <div class="profile-account-header">
                    <h3 id="profile-account-title">{{ __('messages.User Management Account Information') }}</h3>
                    <button type="button" class="btn btn-primary" id="openProfilePassword" aria-haspopup="dialog" aria-controls="profilePasswordModal">
                        <i class="fas fa-key" aria-hidden="true"></i> {{ __('user_password.title') }}
                    </button>
                </div>
                <div class="profile-details-body">
                    <dl class="profile-field-grid">
                        @foreach([
                            'User Management NIK' => $user->nik,
                            'User Management Employee ID' => $user->employee_id,
                        ] as $label => $value)
                            <div class="profile-field">
                                <dt>{{ __('messages.'.$label) }}</dt>
                                <dd class="{{ filled($value) ? '' : 'profile-value-empty' }}">{{ filled($value) ? $value : __('messages.User Management Not Set') }}</dd>
                            </div>
                        @endforeach
                    </dl>
                    <h4 class="profile-section-title"><i class="fas fa-building" aria-hidden="true"></i> {{ __('messages.User Management Organization Access') }}</h4>
                    <dl class="profile-field-grid">
                        @foreach([
                            'User Management Office' => optional($user->office)->name,
                            'User Management Division' => optional($user->division)->name,
                            'User Management Department' => optional($user->dept)->name,
                            'User Management Job Title' => optional($user->jobTitle)->name,
                        ] as $label => $value)
                            <div class="profile-field">
                                <dt>{{ __('messages.'.$label) }}</dt>
                                <dd class="{{ filled($value) ? '' : 'profile-value-empty' }}">{{ filled($value) ? $value : __('messages.User Management Not Set') }}</dd>
                            </div>
                        @endforeach
                    </dl>
                </div>
            </section>
        </div>
    </div>
    @include('users.partials.profile_photo')
    <div class="modal" id="profilePasswordModal" tabindex="-1" role="dialog" aria-labelledby="profilePasswordTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="profilePasswordTitle">{{ __('user_password.title') }}</h5>
                    <button type="button" class="close profile-password-close" aria-label="{{ __('messages.User Management Close') }}"><span aria-hidden="true">&times;</span></button>
                </div>
                <form class="profile-password-form" id="profilePasswordForm" action="{{ route('profile.password.update') }}" method="POST">
                    <div class="modal-body">
                        @csrf
                        <p class="text-muted">{{ __('profile.password_help') }}</p>
                        <p class="text-muted">{{ __('user_password.hint') }}</p>
                        @foreach([
                            'current_password' => __('profile.current_password'),
                            'password' => __('user_password.password'),
                            'password_confirmation' => __('user_password.confirmation'),
                        ] as $field => $label)
                            <div class="form-group">
                                <label for="profile_{{ $field }}">{{ $label }}</label>
                                <div class="password-visibility-field">
                                <input type="password" class="form-control @error($field, 'passwordUpdate') is-invalid @enderror"
                                       id="profile_{{ $field }}" name="{{ $field }}" required
                                       autocomplete="{{ $field === 'current_password' ? 'current-password' : 'new-password' }}"
                                       @if($field !== 'current_password') minlength="8" maxlength="72" @endif
                                       aria-describedby="profile_{{ $field }}_error">
                                @include('users.partials.password_toggle', ['inputId' => 'profile_'.$field])
                                <span class="invalid-feedback" id="profile_{{ $field }}_error" role="alert">{{ $errors->passwordUpdate->first($field) }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light profile-password-close">{{ __('messages.User Management Cancel') }}</button>
                        <button type="submit" class="btn btn-primary" id="profilePasswordSave">{{ __('user_password.save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <style>
        .profile-page { max-width: 1200px; }
        .profile-page-heading { margin-bottom: 28px; }
        .profile-page-heading h2 { color: #202638; font-size: 28px; font-weight: 600; margin: 0 0 8px; }
        .profile-page-heading p { color: #70798b; margin: 0; }
        .profile-overview { display: grid; grid-template-columns: minmax(0, 300px) minmax(0, 1fr); gap: 24px; align-items: start; }
        .profile-identity, .profile-details { background: #fff; border: 1px solid #e3e8ef; border-radius: 12px; overflow: hidden; }
        .profile-identity-banner { height: 88px; background: #6558d3; }
        .profile-identity-content { padding: 0 24px 24px; }
        .profile-avatar { position: relative; display: flex; align-items: center; justify-content: center; width: 80px; height: 80px; margin: 0; border: 5px solid #fff; border-radius: 16px; background: #efedff; color: #5043bd; font-size: 26px; font-weight: 700; }
        .profile-avatar-trigger { display: block; position: relative; width: 80px; height: 80px; margin: -40px 0 20px; padding: 0; border: 0; border-radius: 16px; background: none; cursor: pointer; }
        .profile-avatar-trigger:focus-visible { outline: 2px solid #5043bd; outline-offset: 4px; }
        .profile-avatar img { width: 100%; height: 100%; object-fit: cover; border-radius: 10px; }
        .profile-photo-edit { position: absolute; bottom: -4px; right: -8px; display: flex; align-items: center; justify-content: center; width: 32px; height: 32px; border: 2px solid #fff; border-radius: 50%; background: #6558d3; color: #fff; font-size: 12px; }
        .profile-identity h3 { color: #202638; font-size: 23px; font-weight: 600; line-height: 1.35; margin: 0 0 8px; overflow-wrap: anywhere; }
        .profile-email { color: #70798b; font-size: 14px; margin: 0; overflow-wrap: anywhere; }
        .profile-role { display: grid; gap: 6px; border-top: 1px solid #e3e8ef; margin-top: 26px; padding-top: 20px; }
        .profile-role span { color: #70798b; font-size: 12px; }
        .profile-role strong { color: #5043bd; font-size: 14px; font-weight: 600; overflow-wrap: anywhere; }
        .profile-account-header { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px; padding: 22px 26px; border-bottom: 1px solid #e3e8ef; }
        .profile-account-header h3 { font-size: 17px; font-weight: 600; color: #202638; margin: 0; }
        .profile-account-header .btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; min-height: 44px; margin: 0; border-radius: 6px; font-size: 12px; box-shadow: none; background: #6558d3; border-color: #6558d3; color: #fff; }
        .profile-account-header .btn:hover { background: #5043bd; border-color: #5043bd; }
        .profile-details-body { padding: 26px; }
        .profile-field-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 26px 32px; margin: 0; }
        .profile-field dt { color: #70798b; font-size: 12px; font-weight: 500; margin-bottom: 7px; }
        .profile-field dd { color: #202638; font-size: 15px; font-weight: 500; margin: 0; overflow-wrap: anywhere; }
        .profile-field dd.profile-value-empty { color: #8a92a1; font-weight: 400; }
        .profile-section-title { display: flex; align-items: center; gap: 10px; font-size: 14px; font-weight: 600; color: #202638; border-top: 1px solid #e3e8ef; padding-top: 24px; margin: 28px 0 24px; }
        .profile-section-title i { color: #6558d3; }
        @media (max-width: 991px) {
            .profile-overview { grid-template-columns: minmax(0, 1fr); gap: 20px; }
        }
        @media (max-width: 575px) {
            .profile-account-header, .profile-details-body { padding: 20px; }
            .profile-account-header .btn { width: 100%; }
            .profile-field-grid { grid-template-columns: minmax(0, 1fr); gap: 22px; }
        }
        #profilePasswordModal .modal-dialog { max-width: 520px; }
        #profilePasswordModal .modal-content { max-height: calc(100vh - 48px); overflow: hidden; }
        #profilePasswordForm { display: flex; flex-direction: column; min-height: 0; overflow: hidden; }
        #profilePasswordModal .modal-body { overflow-y: auto; }
        #profilePasswordModal .modal-header, #profilePasswordModal .modal-footer { flex-shrink: 0; }
        #profilePasswordModal .modal-footer { display: flex; gap: 10px; }
        #profilePasswordModal .modal-footer .btn { margin: 0; }
        .profile-password-form { width: 100%; }
        .profile-password-form .form-group > label { position: static; display: block; transform: none; margin-bottom: 8px; font-size: 14px; }
        .profile-password-form .form-control { box-sizing: border-box; width: 100%; height: 48px; padding: 12px 16px; }
        .profile-password-form .btn { min-height: 44px; }
        @media (max-width: 575px) { .profile-password-form .btn { width: 100%; } }
    </style>
@endsection

@push('scripts')
    @include('users.partials.password_visibility')
    <script>
        const passwordModal = $('#profilePasswordModal');
        $('#openProfilePassword').on('click', function () { passwordModal.modal('show'); });
        $('.profile-password-close').on('click', function (event) {
            event.preventDefault();
            if (!document.getElementById('profilePasswordSave').disabled) passwordModal.modal('hide');
        });
        passwordModal.on('shown.bs.modal', function () {
            document.getElementById('profile_current_password').focus();
        }).on('hidden.bs.modal', function () {
            document.getElementById('profilePasswordForm').reset();
            document.getElementById('profile_password_confirmation').setCustomValidity('');
            passwordModal.find('.is-invalid').removeClass('is-invalid');
            passwordModal.find('.invalid-feedback').text('');
            document.getElementById('openProfilePassword').focus();
        });
        @if($errors->passwordUpdate->any())
            passwordModal.modal('show');
        @endif
        const passwordForm = document.getElementById('profilePasswordForm');
        const newPassword = document.getElementById('profile_password');
        const confirmation = document.getElementById('profile_password_confirmation');
        const passwordSave = document.getElementById('profilePasswordSave');
        function validatePasswordConfirmation() {
            confirmation.setCustomValidity(confirmation.value && confirmation.value !== newPassword.value
                ? @json(__('profile.password_mismatch')) : '');
        }
        newPassword.addEventListener('input', validatePasswordConfirmation);
        confirmation.addEventListener('input', validatePasswordConfirmation);
        passwordForm.addEventListener('submit', function (event) {
            validatePasswordConfirmation();
            if (!passwordForm.checkValidity() || passwordSave.disabled) {
                event.preventDefault();
                passwordForm.reportValidity();
                return;
            }
            passwordSave.disabled = true;
            passwordSave.innerHTML = '<span class="spinner-border spinner-border-sm" aria-hidden="true"></span> ' + @json(__('user_password.saving'));
        });
        window.addEventListener('pageshow', function () {
            passwordSave.disabled = false;
            passwordSave.textContent = @json(__('user_password.save'));
        });
    </script>
@endpush
