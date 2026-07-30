<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'manage users',
            'manage desa profile',
            'manage election periods',
            'manage candidates',
            'verify candidates',
            'manage voters',
            'import voters',
            'view activity logs',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $superAdmin = Role::firstOrCreate(['name' => 'super-admin']);
        $superAdmin->syncPermissions(Permission::all());

        $panitia = Role::firstOrCreate(['name' => 'panitia']);
        $panitia->syncPermissions([
            'manage desa profile',
            'manage election periods',
            'manage candidates',
            'manage voters',
            'import voters',
        ]);

        $verifikator = Role::firstOrCreate(['name' => 'verifikator']);
        $verifikator->syncPermissions([
            'verify candidates',
        ]);

        $pengawas = Role::firstOrCreate(['name' => 'pengawas']);
        $pengawas->syncPermissions([
            'view activity logs',
        ]);
    }
}
