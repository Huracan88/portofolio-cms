<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $resourcePermissions = ['viewAny', 'view', 'create', 'update', 'delete', 'deleteAny', 'restore', 'forceDelete', 'restoreAny', 'forceDeleteAny', 'replicate', 'reorder'];
        $models = ['Skill', 'Experience', 'Category', 'Tag', 'Project', 'Post', 'ContactMessage'];

        $permissions = [];
        foreach ($models as $model) {
            foreach ($resourcePermissions as $action) {
                $name = ucfirst($action).':'.$model;
                $permissions[] = Permission::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
            }
        }

        $shieldPermissions = ['ViewAny:Role', 'View:Role', 'Create:Role', 'Update:Role', 'Delete:Role', 'DeleteAny:Role'];
        foreach ($shieldPermissions as $name) {
            $permissions[] = Permission::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
        }

        $superAdmin = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        $superAdmin->syncPermissions(Permission::all());

        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $admin->syncPermissions(Permission::all());

        $editor = Role::firstOrCreate(['name' => 'editor', 'guard_name' => 'web']);
        $editorContentModels = ['Skill', 'Experience', 'Category', 'Tag', 'Project', 'Post'];
        $editorPermissions = [];
        foreach ($editorContentModels as $model) {
            foreach ($resourcePermissions as $action) {
                $editorPermissions[] = ucfirst($action).':'.$model;
            }
        }
        $editor->syncPermissions($editorPermissions);
    }
}
