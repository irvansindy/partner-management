@extends('layouts.main')

@section('content')
    <div class="mb-4">
        <h2>{{ __('profile.title') }}</h2>
        <p class="text-muted">{{ __('profile.description') }}</p>
    </div>
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">{{ __('messages.User Management Account Information') }}</h5>
        </div>
        <div class="card-body">
            <dl class="row mb-0">
                @foreach([
                    'User Management Full Name' => $user->name,
                    'User Management Email' => $user->email,
                    'User Management NIK' => $user->nik,
                    'User Management Employee ID' => $user->employee_id,
                    'User Management Role' => $user->roles->pluck('name')->join(', '),
                    'User Management Office' => optional($user->office)->name,
                    'User Management Division' => optional($user->division)->name,
                    'User Management Department' => optional($user->dept)->name,
                    'User Management Job Title' => optional($user->jobTitle)->name,
                ] as $label => $value)
                    <dt class="col-sm-4 col-lg-3 mb-sm-3 text-muted">{{ __('messages.'.$label) }}</dt>
                    <dd class="col-sm-8 col-lg-9 mb-3 text-break">{{ filled($value) ? $value : __('messages.User Management Not Set') }}</dd>
                @endforeach
            </dl>
        </div>
    </div>
@endsection
