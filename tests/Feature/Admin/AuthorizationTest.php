<?php

use App\Models\User;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

beforeEach(function () {
    (new \Database\Seeders\RolePermissionSeeder)->run();
});

it('redirects guest from admin routes', function () {
    get('/admin/profil-desa')->assertRedirect('/login');
});

it('forbids access to user without permission', function () {
    $user = User::factory()->create();
    actingAs($user)->get('/admin/profil-desa')->assertForbidden();
});

it('allows access to super admin', function () {
    $user = User::factory()->create();
    $user->assignRole('super-admin');
    actingAs($user)->get('/admin/profil-desa')->assertOk();
});
