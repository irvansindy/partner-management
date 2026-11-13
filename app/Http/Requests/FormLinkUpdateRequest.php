<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FormLinkUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'expires_at' => 'nullable|date',
            'max_submissions' => 'nullable|integer|min:1',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return array_merge(__('submission_validation.messages'), [
            'title.required' => __('form_links.title_required'),
            'title.max' => __('form_links.title_max'),
            'max_submissions.min' => __('form_links.limit_min'),
        ]);
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'title' => __('admin.Title'),
            'description' => __('admin.Description'),
            'expires_at' => __('admin.Expires At'),
            'max_submissions' => __('admin.Submissions'),
        ];
    }
}
