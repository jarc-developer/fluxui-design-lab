<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    /**
     * Baseline roles for the design lab.
     *
     * The lab itself is not permission-gated, but having a real role/permission
     * model in place means prototype screens can demonstrate access control
     * without bolting a package on later.
     */
    public function run(): void
    {
        // Drop any cached registry so re-running the seeder is idempotent.
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = [
            'view catalog',
            'edit catalog',
            'manage theme',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        $admin = Role::findOrCreate('admin', 'web');
        $editor = Role::findOrCreate('editor', 'web');
        $viewer = Role::findOrCreate('viewer', 'web');

        $admin->syncPermissions($permissions);
        $editor->syncPermissions(['view catalog', 'edit catalog']);
        $viewer->syncPermissions(['view catalog']);
    }
}
