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
        return [
            'form_type.required' => 'Tipe form harus dipilih.',
            'form_type.in' => 'Tipe form harus vendor atau customer.',
            'title.required' => 'Judul form harus diisi.',
            'title.max' => 'Judul form maksimal 255 karakter.',
            'expires_at.after' => 'Tanggal kadaluarsa harus setelah hari ini.',
            'max_submissions.min' => 'Maksimal submission minimal 1.',
            'user_form.exists' => 'User yang dipilih tidak valid.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'form_type' => 'tipe form',
            'title' => 'judul',
            'description' => 'deskripsi',
            'expires_at' => 'tanggal kadaluarsa',
            'max_submissions' => 'maksimal submission',
            'user_form' => 'user',
        ];
    }
}