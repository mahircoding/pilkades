<?php

use App\Models\Candidate;
use App\Models\ElectionPeriod;
use App\Models\User;
use App\Services\NikService;
use function Pest\Laravel\actingAs;

beforeEach(function () {
    (new \Database\Seeders\RolePermissionSeeder)->run();
    $this->user = User::factory()->create();
    $this->user->assignRole('super-admin');
    $this->period = ElectionPeriod::factory()->active()->create();
    $this->nikService = app(NikService::class);
});

it('can create candidate', function () {
    $rawNik = '3201111111111111';

    actingAs($this->user)->post('/admin/calon', [
        'election_period_id' => $this->period->id,
        'name'               => 'Budi',
        'nik'                => $rawNik,
        'ballot_number'      => 1,
    ])->assertRedirect();

    $candidate = Candidate::first();
    expect($candidate->name)->toBe('Budi')
        ->and($candidate->nik_hash)->toBe($this->nikService->hash($rawNik));
});

it('prevents duplicate ballot number in same period', function () {
    Candidate::factory()->create([
        'election_period_id' => $this->period->id,
        'ballot_number'      => 1,
    ]);

    actingAs($this->user)->post('/admin/calon', [
        'election_period_id' => $this->period->id,
        'name'               => 'Andi',
        'nik'                => '3201111111111112',
        'ballot_number'      => 1,
    ])->assertSessionHasErrors('ballot_number');
});

it('can verify candidate', function () {
    $candidate = Candidate::factory()->create(['election_period_id' => $this->period->id]);

    actingAs($this->user)->post("/admin/calon/{$candidate->id}/verifikasi", [
        'verification_status' => 'verified',
    ])->assertRedirect();

    expect($candidate->fresh()->verification_status)->toBe('verified');
});

it('does not expose raw nik on candidates index', function () {
    $rawNik = '3201111111111111';
    Candidate::factory()->create([
        'election_period_id' => $this->period->id,
        'nik_hash'           => $this->nikService->hash($rawNik),
        'nik_masked'         => $this->nikService->mask($rawNik),
    ]);

    actingAs($this->user)->get('/admin/calon')
        ->assertDontSee($rawNik);
});
