<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $guard = 'web';

        $permissions = [
            'view customer',
            'create customer',
            'update customer',
            'delete customer',
        ];

        foreach ($permissions as $perm) {
            Permission::findOrCreate($perm, $guard);
        }

        $super = Role::findOrCreate('super-admin', $guard);
        $admin = Role::findOrCreate('admin', $guard);
        $marketing = Role::findOrCreate('marketing', $guard);
        $cs = Role::findOrCreate('cs', $guard);

        $super->givePermissionTo($permissions);
        $admin->givePermissionTo($permissions);

        $marketing->givePermissionTo(['create customer']);
        $cs->givePermissionTo(['view customer']);
    }
}

