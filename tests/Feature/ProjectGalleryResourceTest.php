<?php

use App\Filament\Resources\ProjectResource\Pages\CreateProject;
use App\Filament\Resources\ProjectResource\Pages\EditProject;
use App\Models\Project;
use App\Models\User;
use App\Services\MediaService;
use Database\Seeders\RoleAndPermissionSeeder;
use Filament\Forms\Components\Repeater;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Spatie\Permission\PermissionRegistrar;

uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('public');
    $this->seed(RoleAndPermissionSeeder::class);
    app()[PermissionRegistrar::class]->forgetCachedPermissions();

    $this->admin = User::factory()->create();
    $this->admin->assignRole('admin', 'super_admin');
    app()[PermissionRegistrar::class]->forgetCachedPermissions();
});

test('project resource form includes the gallery repeater', function () {
    Livewire::actingAs($this->admin)
        ->test(CreateProject::class)
        ->assertFormFieldExists('galleryImages');
});

test('dispatching gallery-media-selected appends a new image to the create page', function () {
    $media = app(MediaService::class)->store(UploadedFile::fake()->image('gallery.jpg'), $this->admin->id, 'general');
    $undo = Repeater::fake();

    Livewire::actingAs($this->admin)
        ->test(CreateProject::class)
        ->dispatch('gallery-media-selected', url: $media->url, id: $media->id)
        ->assertSchemaStateSet(function (array $state) use ($media) {
            expect($state['galleryImages'])
                ->toHaveCount(1)
                ->and(collect($state['galleryImages'])->first()['media_id'])->toBe($media->id);
        });

    $undo();
});

test('dispatching the same media twice does not duplicate the item', function () {
    $media = app(MediaService::class)->store(UploadedFile::fake()->image('gallery.jpg'), $this->admin->id, 'general');
    $undo = Repeater::fake();

    Livewire::actingAs($this->admin)
        ->test(CreateProject::class)
        ->dispatch('gallery-media-selected', url: $media->url, id: $media->id)
        ->dispatch('gallery-media-selected', url: $media->url, id: $media->id)
        ->assertSchemaStateSet(fn (array $state) => expect($state['galleryImages'])->toHaveCount(1))
        ->assertNotified(__('Image already in gallery'));

    $undo();
});

test('creating a project persists gallery images', function () {
    $mediaA = app(MediaService::class)->store(UploadedFile::fake()->image('one.jpg'), $this->admin->id, 'general');
    $mediaB = app(MediaService::class)->store(UploadedFile::fake()->image('two.jpg'), $this->admin->id, 'general');
    $undo = Repeater::fake();

    Livewire::actingAs($this->admin)
        ->test(CreateProject::class)
        ->fillForm([
            'title_en' => 'Gallery Demo',
            'title_es' => 'Demo Galería',
            'slug' => 'gallery-demo',
            'galleryImages' => [
                ['media_id' => $mediaA->id, 'caption_es' => 'Uno', 'caption_en' => 'One'],
                ['media_id' => $mediaB->id, 'caption_es' => 'Dos', 'caption_en' => 'Two'],
            ],
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $project = Project::where('slug', 'gallery-demo')->first();
    expect($project)->not->toBeNull();
    expect($project->galleryImages)->toHaveCount(2);
    expect($project->galleryImages->pluck('media_id')->all())->toBe([$mediaA->id, $mediaB->id]);
    expect($project->galleryImages->first()->caption_es)->toBe('Uno');

    $undo();
});

test('editing a project reorders gallery images', function () {
    $mediaA = app(MediaService::class)->store(UploadedFile::fake()->image('one.jpg'), $this->admin->id, 'general');
    $mediaB = app(MediaService::class)->store(UploadedFile::fake()->image('two.jpg'), $this->admin->id, 'general');
    $project = Project::factory()->create();
    $project->galleryImages()->create(['media_id' => $mediaA->id, 'sort_order' => 1]);
    $project->galleryImages()->create(['media_id' => $mediaB->id, 'sort_order' => 2]);

    $component = Livewire::actingAs($this->admin)
        ->test(EditProject::class, ['record' => $project->getRouteKey()]);

    $initialState = $component->get('data.galleryImages');
    expect(array_keys($initialState))->toBe(['record-1', 'record-2']);

    $component
        ->set('data.galleryImages', array_reverse($initialState, true))
        ->call('save')
        ->assertHasNoFormErrors();

    $project->refresh();
    expect($project->galleryImages->pluck('media_id')->all())->toBe([$mediaB->id, $mediaA->id]);
});
