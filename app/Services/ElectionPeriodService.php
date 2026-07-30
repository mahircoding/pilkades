<?php

namespace App\Services;

use App\Models\ElectionPeriod;

class ElectionPeriodService
{
    public function createPeriod(array $data): ElectionPeriod
    {
        return ElectionPeriod::create(array_merge($data, [
            'status' => ElectionPeriod::STATUS_DRAFT,
            'is_active' => false,
        ]));
    }

    public function updatePeriod(ElectionPeriod $period, array $data): ElectionPeriod
    {
        $wasActive = $period->is_active;
        $nowActive = $data['is_active'] ?? false;

        if (! $wasActive && $nowActive) {
            // Deactivate others
            ElectionPeriod::where('id', '!=', $period->id)
                ->where('is_active', true)
                ->update(['is_active' => false]);
            
            $data['status'] = ElectionPeriod::STATUS_ACTIVE;
        } elseif ($wasActive && ! $nowActive && $period->status === ElectionPeriod::STATUS_ACTIVE) {
            $data['status'] = ElectionPeriod::STATUS_CLOSED;
        }

        $period->update($data);

        return $period;
    }
}
