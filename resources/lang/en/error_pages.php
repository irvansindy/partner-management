<?php

return [
    'help' => 'If the problem continues, contact your administrator with the page address and the error code above.',
    'dashboard' => 'Go to dashboard',
    'login' => 'Go to sign in',
    '401' => ['title' => 'Please Sign In', 'message' => 'You need to sign in to access this page.'],
    '403' => ['title' => 'Access Denied', 'message' => 'Your account does not have permission to access this page. Contact your administrator if you need access.'],
    '404' => ['title' => 'Page Not Found', 'message' => 'The page you requested is unavailable. Please check the link address.'],
    '405' => ['title' => 'Request Not Supported', 'message' => 'This page does not support that request method.'],
    '408' => ['title' => 'Request Timed Out', 'message' => 'The request took too long. Please try again.'],
    '413' => ['title' => 'Upload Too Large', 'message' => 'The submitted data exceeds the server limit. Reduce the size or number of files before trying again.'],
    '419' => ['title' => 'Session Expired', 'message' => 'Your page session is no longer valid. Open the page again and retry.'],
    '422' => ['title' => 'Invalid Data', 'message' => 'Please check your input before submitting again.'],
    '500' => ['title' => 'System Error', 'message' => 'The system cannot process your request right now. Please try again shortly.'],
    '502' => ['title' => 'Service Unreachable', 'message' => 'The server did not receive a valid response from a related service. Please try again shortly.'],
    '503' => ['title' => 'Service Temporarily Unavailable', 'message' => 'The system is undergoing maintenance or is temporarily unavailable. Please return shortly.'],
    '504' => ['title' => 'Service Timed Out', 'message' => 'A related service did not respond in time. Please try again shortly.'],
    'default' => ['title' => 'Unable to Process Request', 'message' => 'The system could not complete this request. Please check the link or try again shortly.'],
];
