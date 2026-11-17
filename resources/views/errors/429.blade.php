@include('public.form_closed', [
    'pageTitle' => __('rate_limit.title'),
    'message' => __('rate_limit.message'),
    'helpText' => __('rate_limit.wait', ['seconds' => $retryAfter ?? 60]),
    'actionLabel' => __('rate_limit.retry'),
    'actionUrl' => request()->fullUrl(),
])
