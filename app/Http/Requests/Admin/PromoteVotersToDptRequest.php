<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class PromoteVotersToDptRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasPermissionTo('manage voters') ?? false;
    }

    public function rules(): array
    {
        return [
            'election_period_id' => ['required', 'exists:election_periods,id'],
        ];
    }
}
