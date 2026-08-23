<?php

use App\Models\Media;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('project show renders the gallery after the description and before technologies', function () {
    $this->seed();
    $project = Project::where('is_visible', true)->orderBy('sort_order')->first();
    expect($project->galleryImages)->not->toBeEmpty();

    $response = $this->get(route('projects.show', $project));
    $response->assertOk();
    $response->assertSeeInOrder([
        __('PROJECT GALLERY'),
        __('TECHNOLOGIES USED'),
    ]);
});

test('project show renders the gallery lightbox markup', function () {
    $this->seed();
    $project = Project::where('is_visible', true)->orderBy('sort_order')->first();

    $response = $this->get(route('projects.show', $project));
    $response->assertOk();
    $response->assertSee('role="dialog"', false);
    $response->assertSee('aria-modal="true"', false);
    $response->assertSee('x-ref="galleryData"', false);
    $response->assertSee(__('Close gallery'));
    $response->assertSee(__('Next image'));
    $response->assertSee(__('Previous image'));
});

test('gallery captions render in the active locale', function () {
    $this->seed();
    $project = Project::where('is_visible', true)->orderBy('sort_order')->first();
    $first = $project->galleryImages->first();
    expect($first->caption_es)->not->toBeNull();

    app()->setLocale('es');
    $this->get(route('projects.show', $project))->assertSee($first->caption_es, false);

    $this->get(route('projects.show', $project).'?lang=en')->assertSee($first->caption_en, false);
});

test('project show hides the gallery when the project has no images', function () {
    $this->seed();
    $project = Project::factory()->create([
        'is_visible' => true,
        'slug' => 'no-gallery-'.uniqid(),
        'title_en' => 'No Gallery Project',
        'title_es' => 'Proyecto Sin Galería',
    ]);

    $response = $this->get(route('projects.show', $project));
    $response->assertOk();
    $response->assertDontSee(__('PROJECT GALLERY'));
});

test('gallery items without media are not rendered', function () {
    $this->seed();
    $project = Project::factory()->create([
        'is_visible' => true,
        'slug' => 'gallery-orphan-'.uniqid(),
        'title_en' => 'Gallery Orphan',
        'title_es' => 'Galería Huérfana',
    ]);
    $kept = Media::factory()->create([
        'original_name' => 'kept-image.jpg',
        'path' => 'media/kept-image-'.uniqid().'.jpg',
    ]);
    $orphan = Media::factory()->create([
        'original_name' => 'orphan-image.jpg',
        'path' => 'media/orphan-gone-'.uniqid().'.jpg',
    ]);

    $project->galleryImages()->create(['media_id' => $kept->id, 'sort_order' => 1]);
    $project->galleryImages()->create(['media_id' => $orphan->id, 'sort_order' => 2]);
    $orphan->delete();

    $response = $this->get(route('projects.show', $project));
    $response->assertOk();
    $response->assertSee('kept-image-', false);
    $response->assertDontSee('orphan-gone-');
    $response->assertSee(__('PROJECT GALLERY'));
});
