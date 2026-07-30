<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

pest()->extend(TestCase::class)
    ->use(RefreshDatabase::class)
    ->in('Feature');

pest()->extend(TestCase::class)
    ->use(RefreshDatabase::class)
    ->in('Unit');

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

function createSuperAdmin(): \App\Models\User
{
    (new \Database\Seeders\RolePermissionSeeder)->run();
    $user = \App\Models\User::factory()->create();
    $user->assignRole('super-admin');
    return $user;
}
