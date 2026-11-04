<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDivisionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $division = $this->route('division');
        $divisionId = $division instanceof \App\Models\MasterDivision ? $division->id : $division;

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('master_divisions', 'name')->ignore($divisionId),
            ],
            'gm_user_id' => 'nullable|integer|exists:users,id',
            'office_id' => 'nullable|integer|exists:master_offices,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Division name is required.',
            'name.unique' => 'A division with this name already exists.',
            'gm_user_id.exists' => 'Selected General Manager does not exist.',
            'office_id.exists' => 'Selected office does not exist.',
        ];
    }
}
