<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FormLinkStoreRequest extends FormRequest
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
            'form_type' => 'required|in:vendor,customer',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'expires_at' => 'nullable|date|after:now',
            'max_submissions' => 'nullable|integer|min:1',
            'user_form' => 'nullable|exists:users,id', // untuk super-admin
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return array_merge(__('submission_validation.messages'), [
            'form_type.required' => __('form_links.type_required'),
            'form_type.in' => __('form_links.type_invalid'),
            'title.required' => __('form_links.title_required'),
            'title.max' => __('form_links.title_max'),
            'expires_at.after' => __('form_links.expiry_after'),
            'max_submissions.min' => __('form_links.limit_min'),
            'user_form.exists' => __('form_links.user_invalid'),
        ]);
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'form_type' => __('admin.Form Type'),
            'title' => __('admin.Title'),
            'description' => __('admin.Description'),
            'expires_at' => __('admin.Expires At'),
            'max_submissions' => __('admin.Submissions'),
            'user_form' => __('form_links_audit.user'),
        ];
    }
}
