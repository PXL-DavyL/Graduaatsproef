<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // individual permissions
        Permission::create(['name' => 'create blog']);
        Permission::create(['name' => 'edit blog']); // blog title, author and content
        Permission::create(['name' => 'delete blog']);
        Permission::create(['name' => 'manage blog']); // comments and reactions
        
        // admin role
        $role = Role::create(['name' => 'admin']);
        $role->givePermissionTo(Permission::all());
    }
}