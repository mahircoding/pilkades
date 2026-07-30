<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PublicDptCheckRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nik' => ['required', 'digits:16'],
        ];
    }

    public function messages(): array
    {
        return [
            'nik.required' => 'NIK wajib diisi.',
            'nik.digits'   => 'NIK harus tepat 16 digit angka.',
        ];
    }
}
