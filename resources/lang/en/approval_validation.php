<?php

return [
    'messages' => [
        'max' => ['string' => 'The :attribute must not exceed :max characters.', 'numeric' => 'The :attribute must not exceed :max.', 'array' => 'The :attribute must not exceed :max items.'],
        'min' => ['string' => 'The :attribute must contain at least :min characters.', 'numeric' => 'The :attribute must be at least :min.', 'array' => 'The :attribute must contain at least :min items.'],
        'required' => 'The :attribute field is required.',
        'required_if' => 'The :attribute field is required when :other is :value.',
        'string' => 'The :attribute must be text.',
        'integer' => 'The :attribute must be an integer.',
        'array' => 'The :attribute must be a list.',
        'in' => 'The selected :attribute is invalid.',
        'exists' => 'The selected :attribute is invalid.',
        'distinct' => 'The :attribute must not contain duplicates.',
        'regex' => 'The :attribute format is invalid.',
    ],
    'attributes' => [
        'status' => 'Status', 'action' => 'Decision', 'notes' => 'Notes',
        'term_of_payment' => 'Payment terms', 'credit_limit' => 'Credit limit',
        'approval_master_name' => 'Template name',
        'stagging_approval_office' => 'Office', 'stagging_approval_department' => 'Department',
        'master_approval_id' => 'Approval template ID', 'template_id' => 'Template ID',
        'approval_stages' => 'Approval stages',
        'approval_stages.*.user_ids' => 'Stage approvers', 'approval_stages.*.user_ids.*' => 'Approver',
    ],
];
