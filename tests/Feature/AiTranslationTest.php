<?php

use App\Filament\Resources\PostResource\Pages\CreatePost;
use App\Filament\Resources\ProjectResource\Pages\EditProject;
use App\Models\Post;
use App\Models\Project;
use App\Models\Setting;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
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

    Setting::set('openrouter_key', 'test-key');
});

it('translates the Spanish fields into English on the create post page without persisting', function () {
    Http::fake([
        'openrouter.ai/*' => Http::response([
            'choices' => [['message' => ['content' => 'Spanish title']]],
        ]),
    ]);

    $component = Livewire::actingAs($this->admin)
        ->test(CreatePost::class)
        ->fillForm(['title_es' => 'Título en español'])
        ->callAction('translateEsToEn')
        ->assertNotified(__('Section translated successfully'));

    expect($component->get('data.title_en'))->toBe('Spanish title');
    expect($component->get('data.title_es'))->toBe('Título en español');
    expect(Post::count())->toBe(0);

    Http::assertSent(fn ($request): bool => $request->url() === 'https://openrouter.ai/api/v1/chat/completions'
        && str_contains($request['messages'][1]['content'], 'from es to en'));
});

it('translates the English fields into Spanish on the edit project page', function () {
    Http::fake([
        'openrouter.ai/*' => Http::response([
            'choices' => [['message' => ['content' => 'Título del proyecto']]],
        ]),
    ]);

    $project = Project::factory()->create([
        'title_en' => 'Project title',
        'title_es' => '',
    ]);

    Livewire::actingAs($this->admin)
        ->test(EditProject::class, ['record' => $project->getKey()])
        ->callAction('translateEnToEs')
        ->assertSchemaStateSet(['title_es' => 'Título del proyecto'])
        ->assertNotified(__('Section translated successfully'));

    expect($project->refresh()->title_es)->toBe('');
});

it('shows a danger notification when the api key is missing', function () {
    Setting::set('openrouter_key', '');

    Livewire::actingAs($this->admin)
        ->test(CreatePost::class)
        ->fillForm(['title_es' => 'Título en español'])
        ->callAction('translateEsToEn')
        ->assertNotified(__('Translation failed'));

    Http::assertNothingSent();
});

it('shows a warning when there is nothing to translate', function () {
    Livewire::actingAs($this->admin)
        ->test(CreatePost::class)
        ->callAction('translateEsToEn')
        ->assertNotified(__('Nothing to translate'));
});

it('shows the translate action for editors', function () {
    Livewire::actingAs($this->editor)
        ->test(CreatePost::class)
        ->assertActionVisible('translateEsToEn');
});
