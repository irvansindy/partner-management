<?php

return [
    'messages' => [
        'required' => ':attribute wajib diisi.', 'string' => ':attribute harus berupa teks.',
        'integer' => ':attribute harus berupa bilangan bulat.', 'array' => ':attribute harus berupa daftar.',
        'email' => 'Format :attribute tidak valid.', 'ip' => 'Format :attribute tidak valid.',
        'unique' => ':attribute sudah digunakan.', 'exists' => ':attribute yang dipilih tidak valid.',
        'in' => ':attribute yang dipilih tidak valid.', 'not_in' => ':attribute yang dipilih tidak valid.',
        'max' => ['string' => ':attribute maksimal :max karakter.', 'array' => ':attribute maksimal :max item.', 'numeric' => ':attribute maksimal :max.'],
    ],
    'attributes' => [
        'id' => 'ID', 'name' => 'Nama', 'email' => 'Email', 'nik' => 'NIK', 'employee_id' => 'ID karyawan',
        'role' => 'Peran', 'roles' => 'Peran', 'roles.*' => 'Peran', 'office' => 'Kantor',
        'office_id' => 'Kantor', 'department' => 'Departemen', 'division' => 'Divisi',
        'job_title' => 'Jabatan', 'parent_user' => 'Atasan', 'gm_user_id' => 'Manajer umum',
        'department_name' => 'Nama departemen', 'office_name' => 'Nama kantor', 'office_address' => 'Alamat kantor',
        'menu_name' => 'Nama menu', 'menu_url' => 'URL menu', 'menu_icon' => 'Ikon menu', 'menu_type' => 'Jenis menu',
        'parent_id' => 'Menu induk', 'role_name' => 'Nama peran', 'permission_name' => 'Nama izin',
        'permission_id' => 'ID izin', 'ip_address' => 'Alamat IP', 'description' => 'Deskripsi',
    ],
];
