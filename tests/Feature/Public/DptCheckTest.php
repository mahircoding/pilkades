<?php

use App\Models\ElectionPeriod;
use App\Models\Voter;
use function Pest\Laravel\post;

beforeEach(function () {
    $this->period = ElectionPeriod::factory()->active()->create();
});

it('finds dpt voter with masked nik', function () {
    $voter = Voter::factory()->dpt()->create([
        'election_period_id' => $this->period->id,
        'nik_hash'           => hash('sha256', '3201111111111111'),
        'nik_masked'         => '3201****1111',
    ]);

    post('/cek-dpt', ['nik' => '3201111111111111'])
        ->assertOk()
        ->assertSee('TERDAFTAR DI DPT')
        ->assertSee($voter->name)
        ->assertSee('3201****1111')
        ->assertDontSee('3201111111111111');
});

it('does not find dps voter', function () {
    Voter::factory()->create([
        'election_period_id' => $this->period->id,
        'list_status'        => 'dps',
        'nik_hash'           => hash('sha256', '3201111111111111'),
    ]);

    post('/cek-dpt', ['nik' => '3201111111111111'])
        ->assertOk()
        ->assertSee('Tidak Terdaftar', false);
});

it('returns not found for unknown nik', function () {
    post('/cek-dpt', ['nik' => '9999999999999999'])
        ->assertOk()
        ->assertSee('Tidak Terdaftar', false);
});

it('validates nik must be 16 digits', function () {
    post('/cek-dpt', ['nik' => '123'])
        ->assertSessionHasErrors('nik');
});
