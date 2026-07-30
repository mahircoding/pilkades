<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreElectionPeriodRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasPermissionTo('manage election periods') ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'election_date' => ['nullable', 'date'],
            'registration_starts_at' => ['nullable', 'date'],
            'registration_ends_at' => ['nullable', 'date', 'after_or_equal:registration_starts_at'],
            'dps_starts_at' => ['nullable', 'date'],
            'dps_ends_at' => ['nullable', 'date', 'after_or_equal:dps_starts_at'],
        ];
    }
}
