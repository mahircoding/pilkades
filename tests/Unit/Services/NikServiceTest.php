<?php

use App\Services\NikService;

beforeEach(function () {
    $this->service = new NikService;
});

it('validates nik of exactly 16 digits', function () {
    expect($this->service->isValid('3201111111111111'))->toBeTrue()
        ->and($this->service->isValid('320111111111'))->toBeFalse()
        ->and($this->service->isValid('32011111111111AB'))->toBeFalse();
});

it('masks nik correctly', function () {
    expect($this->service->mask('3201111111111234'))->toBe('3201****1234');
});

it('builds consistent hash', function () {
    $h1 = $this->service->hash('3201111111111111');
    $h2 = $this->service->hash('3201111111111111');
    expect($h1)->toBe($h2)->toHaveLength(64);
});

it('never exposes full nik in mask output', function () {
    $nik = '3201111111111234';
    expect($this->service->mask($nik))->not->toBe($nik);
});

it('normalizes nik by stripping non digits', function () {
    expect($this->service->normalize('3201-1111-1111-1111'))->toBe('3201111111111111');
});
