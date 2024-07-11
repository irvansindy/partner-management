<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyProfileValidation extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'company_name' => 'required|string',
            'company_group_name' => 'required|string',
            'established_year' => 'required|string',
            'total_employee' => 'required|integer',
            'liable_person_and_position' => 'required|string',
            'owner_name' => 'required|string',
            'board_of_directors' => 'required|string',
            'major_shareholders' => 'required|string',
            'business_classification' => 'required|string',
            'website_address' => 'required|string',
            'system_management' => 'required|string',
            'contact_person' => 'required|string',
            'communication_language' => 'required|string',
            'email_address' => 'required|string|unique:company_informations,email_address',
            'address.*' => 'string',
            'city.*' => 'string',
            'country.*' => 'string',
            'province.*' => 'string',
            'zip_code.*' => 'string',
            'telephone.*' => 'string',
            'fax.*' => 'string',
        ];
    }
}
