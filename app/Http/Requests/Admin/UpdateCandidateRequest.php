<?php

namespace App\Http\Requests\Admin;

use App\Models\Candidate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCandidateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasPermissionTo('manage candidates') ?? false;
    }

    public function rules(): array
    {
        $candidate = $this->route('candidate');
        $periodId  = $candidate?->election_period_id;

        return [
            'name'        => ['required', 'string', 'max:255'],
            'nik'         => ['required', 'digits:16'],
            'birth_place' => ['nullable', 'string', 'max:255'],
            'birth_date'  => ['nullable', 'date'],
            'gender'      => ['nullable', 'in:Laki-laki,Perempuan'],
            'address'     => ['nullable', 'string', 'max:500'],
            'vision'      => ['nullable', 'string'],
            'mission'     => ['nullable', 'string'],
            'ballot_number' => [
                'nullable',
                'integer',
                'min:1',
                'max:99',
                Rule::unique('candidates', 'ballot_number')
                    ->where('election_period_id', $periodId)
                    ->ignore($candidate?->id),
            ],
            'photo' => ['nullable', 'image', 'max:2048'],
        ];
    }
}
