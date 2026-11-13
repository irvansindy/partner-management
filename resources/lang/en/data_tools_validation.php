<?php
return [
    'messages' => [
        'required' => 'The :attribute field is required.', 'array' => 'The :attribute must be a list.',
        'string' => 'The :attribute must be text.', 'file' => 'The :attribute must be a file.',
        'mimes' => 'The :attribute must be a file of type: :values.',
        'min' => ['array' => 'Select at least :min columns.'],
        'max' => ['file' => 'The :attribute must not exceed :max KB.'],
    ],
    'attributes' => ['fields' => 'Export columns', 'fields.*' => 'Export column', 'file' => 'Import file', 'csv' => 'CSV file'],
];
