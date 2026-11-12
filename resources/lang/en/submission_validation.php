<?php
return [
 'messages' => [
  'required' => 'The :attribute field is required.', 'required_with' => 'The :attribute field is required when :values is present.',
  'string' => 'The :attribute must be text.', 'array' => 'The :attribute must be a list.',
  'integer' => 'The :attribute must be an integer.', 'numeric' => 'The :attribute must be a number.',
  'email' => 'The :attribute must be a valid email address.', 'url' => 'The :attribute must be a valid URL.',
  'in' => 'The selected :attribute is invalid.', 'exists' => 'The selected :attribute is invalid.',
  'file' => 'The :attribute must be a file.', 'mimes' => 'The :attribute must be a file of type: :values.',
  'max.string' => 'The :attribute must not exceed :max characters.', 'max.file' => 'The :attribute must not exceed :max KB.',
  'min.numeric' => 'The :attribute must be at least :min.', 'max.numeric' => 'The :attribute must not exceed :max.',
  'between.numeric' => 'The :attribute must be between :min and :max.',
 ],
 'attributes' => [
  'name'=>'Company name', 'group_name'=>'Company group name', 'established_year'=>'Established year',
  'total_employee'=>'Employee count', 'business_classification'=>'Business classification',
  'business_classification_detail'=>'Classification details', 'website_address'=>'Website', 'email_address'=>'Email',
  'contacts.*.name'=>'Contact name', 'contacts.*.email'=>'Contact email', 'contacts.*.telephone'=>'Contact telephone',
  'addresses.*.address'=>'Address', 'addresses.*.country_code'=>'Country', 'addresses.*.province'=>'Province',
  'addresses.*.city'=>'City/regency', 'addresses.*.zip_code'=>'Postal code', 'addresses.*.telephone'=>'Address telephone',
  'liable_people.*.name'=>'Liable person name', 'liable_people.*.position'=>'Liable person position',
  'liable_people.*.nik'=>'Liable person ID', 'banks.*.name'=>'Bank name', 'banks.*.account_name'=>'Account name',
  'banks.*.account_number'=>'Account number', 'new_attachments.*'=>'Attachment',
 ],
];
