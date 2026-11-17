@php
    $code = $statusCode ?? 500;
    $key = \Illuminate\Support\Facades\Lang::has('error_pages.'.$code) ? (string) $code : 'default';
@endphp
@include('public.form_closed', [
    'pageTitle' => $code.' · '.__('error_pages.'.$key.'.title'),
    'message' => __('error_pages.'.$key.'.message'),
    'helpText' => __('error_pages.help'),
    'actionLabel' => auth()->guard()->hasUser() ? __('error_pages.dashboard') : __('error_pages.login'),
    'actionUrl' => auth()->guard()->hasUser() ? url('/dashboard') : url('/login'),
])
