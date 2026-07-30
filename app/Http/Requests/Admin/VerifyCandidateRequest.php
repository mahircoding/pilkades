<?php

namespace App\Http\Requests\Admin;

use App\Models\Candidate;
use Illuminate\Foundation\Http\FormRequest;

class VerifyCandidateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasPermissionTo('verify candidates') ?? false;
    }

    public function rules(): array
    {
        return [
            'verification_status' => ['required', 'in:verified,rejected'],
            'verification_notes'  => ['nullable', 'string', 'max:1000'],
        ];
    }
}
