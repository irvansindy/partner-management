<?php

return [
    'messages' => [
        'max' => ['string' => ':attribute maksimal :max karakter.', 'numeric' => ':attribute maksimal :max.', 'array' => ':attribute maksimal :max item.'],
        'min' => ['string' => ':attribute minimal :min karakter.', 'numeric' => ':attribute minimal :min.', 'array' => ':attribute minimal :min item.'],
        'required' => ':attribute wajib diisi.',
        'required_if' => ':attribute wajib diisi saat :other bernilai :value.',
        'string' => ':attribute harus berupa teks.',
        'integer' => ':attribute harus berupa bilangan bulat.',
        'array' => ':attribute harus berupa daftar.',
        'in' => ':attribute yang dipilih tidak valid.',
        'exists' => ':attribute yang dipilih tidak valid.',
        'distinct' => ':attribute tidak boleh duplikat.',
        'regex' => 'Format :attribute tidak valid.',
    ],
    'attributes' => [
        'status' => 'Status', 'action' => 'Keputusan', 'notes' => 'Catatan',
        'term_of_payment' => 'Jangka pembayaran', 'credit_limit' => 'Batas kredit',
        'approval_master_name' => 'Nama templat',
        'stagging_approval_office' => 'Kantor', 'stagging_approval_department' => 'Departemen',
        'master_approval_id' => 'ID templat persetujuan', 'template_id' => 'ID templat',
        'approval_stages' => 'Tahap persetujuan',
        'approval_stages.*.user_ids' => 'PIC tahap', 'approval_stages.*.user_ids.*' => 'PIC',
    ],
];
