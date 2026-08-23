<?php

use App\Filament\Resources\PostResource\Pages\CreatePost;
use App\Filament\Resources\PostResource\Pages\EditPost;
use App\Filament\Resources\ProjectResource\Pages\CreateProject;
use App\Filament\Resources\ProjectResource\Pages\EditProject;
use App\Livewire\MediaPicker;
use App\Models\Post;
use App\Models\Project;
use App\Models\User;
use App\Services\MediaService;
use Database\Seeders\RoleAndPermissionSeeder;
use Filament\Actions\Testing\TestAction;
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

    $this->editor = User::factory()->create();
    $this->editor->assignRole('editor');
    app()[PermissionRegistrar::class]->forgetCachedPermissions();
});

test('the media picker renders stored media and dispatches the selection event', function () {
    $media = app(MediaService::class)->store(UploadedFile::fake()->image('gallery.jpg'), $this->admin->id, 'general');

    Livewire::actingAs($this->admin)
        ->test(MediaPicker::class, ['collection' => 'general'])
        ->assertSee('gallery.jpg')
        ->call('pick', $media->id)
        ->assertDispatched('media-selected', url: $media->url);
});

test('the media picker is forbidden for editor users', function () {
    Livewire::actingAs($this->editor)
        ->test(MediaPicker::class)
        ->assertForbidden();
});

test('the media picker is forbidden for guests', function () {
    Livewire::test(MediaPicker::class)
        ->assertForbidden();
});

test('the media picker filters by collection and search', function () {
    app(MediaService::class)->store(UploadedFile::fake()->image('cover-a.jpg'), $this->admin->id, 'covers');
    app(MediaService::class)->store(UploadedFile::fake()->image('other.jpg'), $this->admin->id, 'general');

    Livewire::actingAs($this->admin)
        ->test(MediaPicker::class, ['collection' => 'covers'])
        ->assertSee('cover-a.jpg')
        ->assertDontSee('other.jpg')
        ->set('search', 'cover')
        ->assertSee('cover-a.jpg')
        ->assertDontSee('other.jpg');
});

test('selecting media fills cover_image_url on the create post page', function () {
    Livewire::actingAs($this->admin)
        ->test(CreatePost::class)
        ->dispatch('media-selected', url: 'storage/media/demo.jpg')
        ->assertSet('data.cover_image_url', 'storage/media/demo.jpg');
});

test('selecting media fills cover_image_url on the edit post page', function () {
    $post = Post::factory()->create();

    Livewire::actingAs($this->admin)
        ->test(EditPost::class, ['record' => $post->getRouteKey()])
        ->dispatch('media-selected', url: 'storage/media/demo.jpg')
        ->assertSet('data.cover_image_url', 'storage/media/demo.jpg');
});

test('selecting media fills image_url on the create project page', function () {
    Livewire::actingAs($this->admin)
        ->test(CreateProject::class)
        ->dispatch('media-selected', url: 'storage/media/demo.jpg')
        ->assertSet('data.image_url', 'storage/media/demo.jpg');
});

test('selecting media fills image_url on the edit project page', function () {
    $project = Project::factory()->create();

    Livewire::actingAs($this->admin)
        ->test(EditProject::class, ['record' => $project->getRouteKey()])
        ->dispatch('media-selected', url: 'storage/media/demo.jpg')
        ->assertSet('data.image_url', 'storage/media/demo.jpg');
});

test('the media picker modal renders inside the create post page', function () {
    app(MediaService::class)->store(UploadedFile::fake()->image('modal-pick.jpg'), $this->admin->id, 'covers');

    Livewire::actingAs($this->admin)
        ->test(CreatePost::class)
        ->mountAction(TestAction::make('openMediaPicker')->schemaComponent('cover_image_url', schema: 'form'))
        ->assertMountedActionModalSee('modal-pick.jpg');
});

test('the media picker dispatches a custom event name with the media id', function () {
    $media = app(MediaService::class)->store(UploadedFile::fake()->image('gallery.jpg'), $this->admin->id, 'general');

    Livewire::actingAs($this->admin)
        ->test(MediaPicker::class, ['collection' => 'general', 'event' => 'gallery-media-selected'])
        ->call('pick', $media->id)
        ->assertDispatched('gallery-media-selected', url: $media->url, id: $media->id);
});

test('the media picker keeps dispatching the default event when no event prop is given', function () {
    $media = app(MediaService::class)->store(UploadedFile::fake()->image('cover.jpg'), $this->admin->id, 'covers');

    Livewire::actingAs($this->admin)
        ->test(MediaPicker::class, ['collection' => 'covers'])
        ->call('pick', $media->id)
        ->assertDispatched('media-selected', url: $media->url, id: $media->id);
});
