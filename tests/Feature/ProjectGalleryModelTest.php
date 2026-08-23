<?php

use App\Models\Media;
use App\Models\Project;
use App\Models\ProjectGalleryImage;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('gallery images are ordered by sort order', function () {
    $project = Project::factory()->create();
    $mediaA = Media::factory()->create();
    $mediaB = Media::factory()->create();
    $mediaC = Media::factory()->create();

    $project->galleryImages()->create(['media_id' => $mediaC->id, 'sort_order' => 3]);
    $project->galleryImages()->create(['media_id' => $mediaA->id, 'sort_order' => 1]);
    $project->galleryImages()->create(['media_id' => $mediaB->id, 'sort_order' => 2]);

    expect($project->galleryImages->pluck('media_id')->all())->toBe([$mediaA->id, $mediaB->id, $mediaC->id]);
});

test('caption falls back from locale to english', function () {
    $image = ProjectGalleryImage::factory()->create([
        'caption_es' => 'Pie de foto ES',
        'caption_en' => 'Caption EN',
    ]);

    app()->setLocale('es');
    expect($image->caption)->toBe('Pie de foto ES');
    app()->setLocale('en');
    expect($image->caption)->toBe('Caption EN');
    app()->setLocale('fr');
    expect($image->caption)->toBe('Caption EN');
});

test('caption is null when both locales are empty', function () {
    $image = ProjectGalleryImage::factory()->create(['caption_es' => null, 'caption_en' => null]);

    expect($image->caption)->toBeNull();
});

test('deleting a project cascades to gallery images', function () {
    $project = Project::factory()->create();
    $image = ProjectGalleryImage::factory()->create(['project_id' => $project->id]);

    $project->delete();

    expect(ProjectGalleryImage::find($image->id))->toBeNull();
});

test('force deleting media cascades to gallery images', function () {
    $media = Media::factory()->create();
    $image = ProjectGalleryImage::factory()->create(['media_id' => $media->id]);

    $media->forceDelete();

    expect(ProjectGalleryImage::find($image->id))->toBeNull();
});

test('soft deleted media makes accessors null-safe', function () {
    $media = Media::factory()->create([
        'alt_es' => 'Alt ES',
        'alt_en' => 'Alt EN',
    ]);
    $image = ProjectGalleryImage::factory()->create([
        'media_id' => $media->id,
        'caption_es' => 'Pie ES',
        'caption_en' => 'Caption EN',
    ]);

    $media->delete();

    expect($image->media)->toBeNull();
    expect($image->url)->toBeNull();
    expect($image->relative_url)->toBeNull();

    app()->setLocale('es');
    expect($image->alt)->toBe('Pie ES');
    app()->setLocale('en');
    expect($image->alt)->toBe('Caption EN');
});

test('alt uses media alt by locale with caption fallback', function () {
    $media = Media::factory()->create(['alt_es' => 'Alt ES', 'alt_en' => 'Alt EN']);
    $image = ProjectGalleryImage::factory()->create(['media_id' => $media->id]);

    app()->setLocale('es');
    expect($image->alt)->toBe('Alt ES');
    app()->setLocale('en');
    expect($image->alt)->toBe('Alt EN');
});

test('url and relative url delegate to media', function () {
    $media = Media::factory()->create(['path' => 'media/gallery-one.jpg']);
    $image = ProjectGalleryImage::factory()->create(['media_id' => $media->id]);

    expect($image->relative_url)->toBe('storage/media/gallery-one.jpg');
    expect($image->url)->toBe($media->url);
});
