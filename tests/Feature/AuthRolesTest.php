<?php

use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(RoleAndPermissionSeeder::class);
    app()[PermissionRegistrar::class]->forgetCachedPermissions();
});

test('admin role exists', function () {
    expect(Role::where('name', 'admin')->exists())->toBeTrue();
});

test('editor role exists', function () {
    expect(Role::where('name', 'editor')->exists())->toBeTrue();
});

test('super_admin role exists', function () {
    expect(Role::where('name', 'super_admin')->exists())->toBeTrue();
});

test('admin user has admin role', function () {
    $admin = User::factory()->create(['email' => 'admin@example.com']);
    $admin->assignRole('admin');

    expect($admin->hasRole('admin'))->toBeTrue();
});

test('editor user has editor role', function () {
    $editor = User::factory()->create(['email' => 'editor@example.com']);
    $editor->assignRole('editor');

    expect($editor->hasRole('editor'))->toBeTrue();
    expect($editor->hasRole('admin'))->toBeFalse();
});

test('admin has full permissions', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');
    app()[PermissionRegistrar::class]->forgetCachedPermissions();

    expect($admin->can('ViewAny:Skill'))->toBeTrue();
    expect($admin->can('ViewAny:Role'))->toBeTrue();
});

test('editor has content permissions but not user management', function () {
    $editor = User::factory()->create();
    $editor->assignRole('editor');
    app()[PermissionRegistrar::class]->forgetCachedPermissions();

    expect($editor->can('ViewAny:Skill'))->toBeTrue();
    expect($editor->can('ViewAny:Post'))->toBeTrue();
    expect($editor->can('ViewAny:Role'))->toBeFalse();
});

test('admin panel redirects to login without session', function () {
    $response = $this->get('/admin');
    $response->assertRedirect('/admin/login');
});
