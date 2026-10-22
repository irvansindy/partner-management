<?php

return [
    'max_repeat_rows' => 10,
    'max_survey_rows' => 20,
    'rate_limits' => [
        'view_per_ip' => 120,
        'view_per_token' => 60,
        'submit_per_ip' => 20,
        'submit_per_token' => 5,
    ],
];
