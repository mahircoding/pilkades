<?php

use App\Models\ElectionPeriod;
use App\Services\ElectionPeriodService;

beforeEach(function () {
    $this->service = new ElectionPeriodService;
});

it('creates period with draft status', function () {
    $period = $this->service->createPeriod(['name' => 'Pilkades 2026']);
    expect($period->status)->toBe('draft')
        ->and($period->is_active)->toBeFalse();
});

it('deactivates other periods when activating one', function () {
    $period1 = ElectionPeriod::factory()->active()->create();
    $period2 = ElectionPeriod::factory()->create();

    $this->service->updatePeriod($period2, [
        'name'      => 'Pilkades 2027',
        'status'    => 'active',
        'is_active' => true,
    ]);

    expect($period1->fresh()->is_active)->toBeFalse()
        ->and($period2->fresh()->is_active)->toBeTrue();
});
