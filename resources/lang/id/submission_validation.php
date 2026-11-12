<?php
return [
 'messages' => [
  'required' => ':attribute wajib diisi.', 'required_with' => ':attribute wajib diisi saat :values tersedia.',
  'string' => ':attribute harus berupa teks.', 'array' => ':attribute harus berupa daftar.',
  'integer' => ':attribute harus berupa bilangan bulat.', 'numeric' => ':attribute harus berupa angka.',
  'email' => ':attribute harus berupa alamat email yang valid.', 'url' => ':attribute harus berupa URL yang valid.',
  'in' => ':attribute yang dipilih tidak valid.', 'exists' => ':attribute yang dipilih tidak valid.',
  'file' => ':attribute harus berupa berkas.', 'mimes' => ':attribute harus berjenis :values.',
  'max.string' => ':attribute maksimal :max karakter.', 'max.file' => ':attribute maksimal :max KB.',
  'min.numeric' => ':attribute minimal :min.', 'max.numeric' => ':attribute maksimal :max.',
  'between.numeric' => ':attribute harus di antara :min dan :max.',
 ],
 'attributes' => [
  'name'=>'Nama perusahaan', 'group_name'=>'Nama grup perusahaan', 'established_year'=>'Tahun berdiri',
  'total_employee'=>'Jumlah karyawan', 'business_classification'=>'Klasifikasi bisnis',
  'business_classification_detail'=>'Detail klasifikasi bisnis', 'website_address'=>'Situs web', 'email_address'=>'Email',
  'contacts.*.name'=>'Nama narahubung', 'contacts.*.email'=>'Email narahubung', 'contacts.*.telephone'=>'Telepon narahubung',
  'addresses.*.address'=>'Alamat', 'addresses.*.country_code'=>'Negara', 'addresses.*.province'=>'Provinsi',
  'addresses.*.city'=>'Kota/kabupaten', 'addresses.*.zip_code'=>'Kode pos', 'addresses.*.telephone'=>'Telepon alamat',
  'liable_people.*.name'=>'Nama penanggung jawab', 'liable_people.*.position'=>'Jabatan penanggung jawab',
  'liable_people.*.nik'=>'NIK penanggung jawab', 'banks.*.name'=>'Nama bank', 'banks.*.account_name'=>'Nama rekening',
  'banks.*.account_number'=>'Nomor rekening', 'new_attachments.*'=>'Lampiran',
 ],
];
