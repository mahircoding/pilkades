<?php

use App\Models\ElectionPeriod;
use App\Models\User;
use function Pest\Laravel\actingAs;

beforeEach(function () {
    (new \Database\Seeders\RolePermissionSeeder)->run();
    $this->user = User::factory()->create();
    $this->user->assignRole('super-admin');
});

it('can create election period', function () {
    actingAs($this->user)->post('/admin/periode-pemilihan', [
        'name' => 'Pilkades 2026',
    ])->assertRedirect();

    expect(ElectionPeriod::count())->toBe(1);
    expect(ElectionPeriod::first()->status)->toBe('draft');
});

it('ensures only one active period', function () {
    $period1 = ElectionPeriod::factory()->active()->create();
    $period2 = ElectionPeriod::factory()->create();

    $response = actingAs($this->user)->put("/admin/periode-pemilihan/{$period2->id}", [
        'name'      => 'Pilkades 2027',
        'status'    => 'active',
        'is_active' => true,
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect();

    expect($period1->fresh()->is_active)->toBeFalse();
    expect($period2->fresh()->is_active)->toBeTrue();
});
