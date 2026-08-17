<?php

use App\Filament\Pages\SettingsPage;
use App\Models\Setting;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Livewire\Livewire;
use Spatie\Permission\PermissionRegistrar;

uses(RefreshDatabase::class);

beforeEach(function () {
    Cache::flush();
    $this->seed(RoleAndPermissionSeeder::class);
    app()[PermissionRegistrar::class]->forgetCachedPermissions();

    $this->admin = User::factory()->create();
    $this->admin->assignRole('admin', 'super_admin');
    app()[PermissionRegistrar::class]->forgetCachedPermissions();

    $this->editor = User::factory()->create();
    $this->editor->assignRole('editor');
    app()[PermissionRegistrar::class]->forgetCachedPermissions();
});

test('admin and super admin can open the settings page', function () {
    $this->actingAs($this->admin)
        ->get('/admin/settings')
        ->assertOk();
});

test('editor cannot open the settings page', function () {
    $this->actingAs($this->editor)
        ->get('/admin/settings')
        ->assertForbidden();
});

test('saving the form persists the model settings', function () {
    Livewire::actingAs($this->admin)
        ->test(SettingsPage::class)
        ->fillForm([
            'openrouter_translate_model' => 'model/translate',
            'openrouter_image_model' => 'model/images',
        ])
        ->call('save')
        ->assertHasNoFormErrors()
        ->assertNotified(__('Settings saved'));

    expect(Setting::get('openrouter_translate_model'))->toBe('model/translate');
    expect(Setting::get('openrouter_image_model'))->toBe('model/images');
});

test('saving a key stores ciphertext without plaintext in the database', function () {
    Livewire::actingAs($this->admin)
        ->test(SettingsPage::class)
        ->fillForm([
            'openrouter_key' => 'sk-new-secret',
            'openrouter_translate_model' => 'model/translate',
            'openrouter_image_model' => 'model/images',
        ])
        ->call('save');

    $row = Setting::query()->whereKey('openrouter_key')->first();

    expect($row->is_encrypted)->toBeTrue();
    expect($row->value)->not->toContain('sk-new-secret');
    expect(Crypt::decryptString($row->value))->toBe('sk-new-secret');
    expect(Setting::get('openrouter_key'))->toBe('sk-new-secret');
});

test('saving with an empty key keeps the stored key', function () {
    Setting::set('openrouter_key', 'existing-secret', true);

    Livewire::actingAs($this->admin)
        ->test(SettingsPage::class)
        ->fillForm([
            'openrouter_key' => '',
            'openrouter_translate_model' => 'model/translate',
            'openrouter_image_model' => 'model/images',
        ])
        ->call('save');

    expect(Setting::get('openrouter_key'))->toBe('existing-secret');
});

test('the stored api key is never rendered in the page html', function () {
    Setting::set('openrouter_key', 'do-not-leak-me', true);

    $this->actingAs($this->admin)
        ->get('/admin/settings')
        ->assertOk()
        ->assertSee(__('An API key is already saved. Leave this field empty to keep it.'))
        ->assertDontSee('do-not-leak-me');
});
