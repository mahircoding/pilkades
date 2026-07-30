<?php

namespace App\Http\Requests\Admin;

class UpdateElectionPeriodRequest extends StoreElectionPeriodRequest
{
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'status' => ['required', 'string', 'in:draft,active,closed'],
            'is_active' => ['boolean'],
        ]);
    }
}
