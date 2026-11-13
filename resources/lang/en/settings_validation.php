<?php

return [
    'messages' => [
        'required' => 'The :attribute field is required.', 'string' => 'The :attribute must be text.',
        'integer' => 'The :attribute must be an integer.', 'array' => 'The :attribute must be a list.',
        'email' => 'The :attribute format is invalid.', 'ip' => 'The :attribute format is invalid.',
        'unique' => 'The :attribute is already in use.', 'exists' => 'The selected :attribute is invalid.',
        'in' => 'The selected :attribute is invalid.', 'not_in' => 'The selected :attribute is invalid.',
        'max' => ['string' => 'The :attribute must not exceed :max characters.', 'array' => 'The :attribute must not exceed :max items.', 'numeric' => 'The :attribute must not exceed :max.'],
    ],
    'attributes' => [
        'id' => 'ID', 'name' => 'Name', 'email' => 'Email', 'nik' => 'NIK', 'employee_id' => 'Employee ID',
        'role' => 'Role', 'roles' => 'Roles', 'roles.*' => 'Role', 'office' => 'Office',
        'office_id' => 'Office', 'department' => 'Department', 'division' => 'Division',
        'job_title' => 'Job title', 'parent_user' => 'Supervisor', 'gm_user_id' => 'General manager',
        'department_name' => 'Department name', 'office_name' => 'Office name', 'office_address' => 'Office address',
        'menu_name' => 'Menu name', 'menu_url' => 'Menu URL', 'menu_icon' => 'Menu icon', 'menu_type' => 'Menu type',
        'parent_id' => 'Parent menu', 'role_name' => 'Role name', 'permission_name' => 'Permission name',
        'permission_id' => 'Permission ID', 'ip_address' => 'IP address', 'description' => 'Description',
    ],
];
