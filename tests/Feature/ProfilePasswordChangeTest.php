<?php

use App\Filament\Pages\Auth\EditProfile;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Spatie\Permission\PermissionRegistrar;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(RoleAndPermissionSeeder::class);
    app()[PermissionRegistrar::class]->forgetCachedPermissions();

    $this->user = User::factory()->create([
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => Hash::make('old-password'),
    ]);
    $this->user->assignRole('admin');
    app()[PermissionRegistrar::class]->forgetCachedPermissions();
});

test('unauthenticated user cannot visit profile page', function () {
    $this->get('/admin/profile')
        ->assertRedirect('/admin/login');
});

test('authenticated user can visit profile page', function () {
    $this->actingAs($this->user)
        ->get('/admin/profile')
        ->assertOk();
});

test('user can successfully change their password', function () {
    Livewire::actingAs($this->user)
        ->test(EditProfile::class)
        ->set('data.password', 'new-secure-password')
        ->set('data.passwordConfirmation', 'new-secure-password')
        ->set('data.currentPassword', 'old-password')
        ->call('save')
        ->assertHasNoFormErrors();

    $this->user->refresh();

    expect(Hash::check('new-secure-password', $this->user->password))->toBeTrue();
    expect(Hash::check('old-password', $this->user->password))->toBeFalse();
});

test('user cannot change password with incorrect current password', function () {
    Livewire::actingAs($this->user)
        ->test(EditProfile::class)
        ->set('data.password', 'new-secure-password')
        ->set('data.passwordConfirmation', 'new-secure-password')
        ->set('data.currentPassword', 'wrong-current-password')
        ->call('save')
        ->assertHasFormErrors(['currentPassword']);

    $this->user->refresh();

    expect(Hash::check('old-password', $this->user->password))->toBeTrue();
});

test('user cannot change password if confirmation does not match', function () {
    Livewire::actingAs($this->user)
        ->test(EditProfile::class)
        ->set('data.password', 'new-secure-password')
        ->set('data.passwordConfirmation', 'different-password')
        ->set('data.currentPassword', 'old-password')
        ->call('save')
        ->assertHasFormErrors(['password']);

    $this->user->refresh();

    expect(Hash::check('old-password', $this->user->password))->toBeTrue();
});

test('user menu includes change password action', function () {
    $menuItems = filament()->getUserMenuItems();

    expect($menuItems)->toHaveKey('change_password');
    expect($menuItems['change_password']->getLabel())->toBe(__('Change password'));
    expect($menuItems['change_password']->getUrl())->toBe(filament()->getProfileUrl());
});
