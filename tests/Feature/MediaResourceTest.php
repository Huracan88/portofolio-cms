<?php

use App\Filament\Resources\MediaResource\Pages\ListMedia;
use App\Models\Media;
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

    $this->service = app(MediaService::class);
});

function createStoredMedia(User $user, string $name = 'hero.jpg', int $width = 400, int $height = 300): Media
{
    return app(MediaService::class)->store(UploadedFile::fake()->image($name, $width, $height), $user->id, 'general');
}

test('admin can render the media library index page', function () {
    $this->actingAs($this->admin)
        ->get('/admin/media')
        ->assertSuccessful();
});

test('editor cannot render the media library index page', function () {
    $this->actingAs($this->editor)
        ->get('/admin/media')
        ->assertForbidden();
});

test('the upload header action creates a media record and writes the file', function () {
    Livewire::actingAs($this->admin)
        ->test(ListMedia::class)
        ->callAction('upload', ['files' => [UploadedFile::fake()->image('uploaded.jpg', 100, 80)]])
        ->assertHasNoFormErrors();

    $media = Media::where('original_name', 'uploaded.jpg')->first();

    expect($media)->not->toBeNull();
    expect($media->collection)->toBe('general');
    expect($media->user_id)->toBe($this->admin->id);
    expect(Storage::disk('public')->exists($media->path))->toBeTrue();
});

test('the edit table action updates media metadata', function () {
    $media = createStoredMedia($this->admin);

    Livewire::actingAs($this->admin)
        ->test(ListMedia::class)
        ->callAction(TestAction::make('edit')->table($media), data: [
            'alt_es' => 'Texto alternativo',
            'alt_en' => 'Alternative text',
            'collection' => 'covers',
        ])
        ->assertHasNoFormErrors();

    $media->refresh();

    expect($media->alt_es)->toBe('Texto alternativo');
    expect($media->alt_en)->toBe('Alternative text');
    expect($media->collection)->toBe('covers');
});

test('soft delete moves the media to the trash without removing the file', function () {
    $media = createStoredMedia($this->admin);

    Livewire::actingAs($this->admin)
        ->test(ListMedia::class)
        ->callAction(TestAction::make('delete')->table($media));

    expect($media->refresh()->trashed())->toBeTrue();
    expect(Storage::disk('public')->exists($media->path))->toBeTrue();
});

test('restore brings the media back from the trash', function () {
    $media = createStoredMedia($this->admin);
    $media->delete();

    Livewire::actingAs($this->admin)
        ->test(ListMedia::class)
        ->callAction(TestAction::make('restore')->table($media));

    expect($media->refresh()->trashed())->toBeFalse();
    expect(Storage::disk('public')->exists($media->path))->toBeTrue();
});

test('force delete removes the record and the physical file', function () {
    $media = createStoredMedia($this->admin);
    $media->delete();
    $path = $media->path;

    Livewire::actingAs($this->admin)
        ->test(ListMedia::class)
        ->callAction(TestAction::make('forceDelete')->table($media));

    expect(Media::find($media->id))->toBeNull();
    expect(Storage::disk('public')->exists($path))->toBeFalse();
});

test('bulk force delete removes the records and their physical files', function () {
    $mediaA = createStoredMedia($this->admin, 'bulk-a.jpg');
    $mediaB = createStoredMedia($this->admin, 'bulk-b.jpg');
    $pathA = $mediaA->path;
    $pathB = $mediaB->path;

    Livewire::actingAs($this->admin)
        ->test(ListMedia::class)
        ->filterTable('trashed', ['value' => 'with trashed'])
        ->selectTableRecords([$mediaA->id, $mediaB->id])
        ->callAction(TestAction::make('forceDelete')->table()->bulk());

    expect(Media::find($mediaA->id))->toBeNull();
    expect(Media::find($mediaB->id))->toBeNull();
    expect(Storage::disk('public')->exists($pathA))->toBeFalse();
    expect(Storage::disk('public')->exists($pathB))->toBeFalse();
});

test('applyCrop processes the image with the payload coordinates', function () {
    $media = createStoredMedia($this->admin, 'crop.jpg', 400, 300);
    $originalPath = $media->path;

    Livewire::actingAs($this->admin)
        ->test(ListMedia::class)
        ->call('applyCrop', $media->id, [
            'mode' => 'overwrite',
            'preset' => 'cover',
            'crop' => ['x' => 0, 'y' => 0, 'width' => 200, 'height' => 150],
            'maxWidth' => 2000,
            'quality' => 80,
            'asWebp' => false,
        ]);

    $media->refresh();

    expect($media->width)->toBe(200);
    expect($media->height)->toBe(150);
    expect($media->path)->toBe($originalPath);
    expect(Storage::disk('public')->exists($media->path))->toBeTrue();
});

test('applyCrop save as new writes a webp file and deletes the original', function () {
    $media = createStoredMedia($this->admin, 'crop.jpg', 400, 300);
    $originalPath = $media->path;

    Livewire::actingAs($this->admin)
        ->test(ListMedia::class)
        ->call('applyCrop', $media->id, [
            'mode' => 'saveAsNew',
            'preset' => 'free',
            'crop' => ['x' => 0, 'y' => 0, 'width' => 400, 'height' => 300],
            'maxWidth' => 2400,
            'quality' => 80,
            'asWebp' => true,
        ]);

    $media->refresh();

    expect($media->path)->not->toBe($originalPath);
    expect($media->mime_type)->toBe('image/webp');
    expect(Storage::disk('public')->exists($media->path))->toBeTrue();
    expect(Storage::disk('public')->exists($originalPath))->toBeFalse();
});

test('the process action opens the crop modal for processable media', function () {
    $media = createStoredMedia($this->admin);

    Livewire::actingAs($this->admin)
        ->test(ListMedia::class)
        ->mountAction(TestAction::make('process')->table($media))
        ->assertMountedActionModalSee(__('Quality'));
});
