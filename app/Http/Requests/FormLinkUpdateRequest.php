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
        return [
            'title.required' => 'Judul form harus diisi.',
            'title.max' => 'Judul form maksimal 255 karakter.',
            'max_submissions.min' => 'Maksimal submission minimal 1.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'title' => 'judul',
            'description' => 'deskripsi',
            'expires_at' => 'tanggal kadaluarsa',
            'max_submissions' => 'maksimal submission',
        ];
    }
}