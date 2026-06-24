<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StorePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check(); // Only allow authenticated users to create posts
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'content' => ['required', 'string', 'max:255', 'min:5'],
        ];
    }

    public function messages(): array
    {
        return [
            'content.required' => 'Sila masukkan kandungan pos.',
            'content.string' => 'Kandungan pos mesti berupa teks.',
            'content.max' => 'Kandungan pos tidak boleh melebihi 255 aksara.',
            'content.min' => 'Kandungan pos mesti sekurang-kurangnya 5 aksara.',
        ];
    }
}
