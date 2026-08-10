<?php

use App\Models\Category;
use App\Models\ContactMessage;
use App\Models\Education;
use App\Models\Experience;
use App\Models\Language;
use App\Models\Post;
use App\Models\Profile;
use App\Models\Project;
use App\Models\Skill;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;

uses(RefreshDatabase::class);

test('skill has correct casts', function () {
    $skill = Skill::factory()->create();
    expect($skill->level)->toBeInt();
    expect($skill->is_visible)->toBeBool();
});

test('skill has bilingual name accessor', function () {
    $skill = Skill::factory()->create(['name_en' => 'PHP', 'name_es' => 'PHP avanzado']);
    app()->setLocale('es');
    expect($skill->name)->toBe('PHP avanzado');
    app()->setLocale('en');
    expect($skill->name)->toBe('PHP');
});

test('skill has projects relationship', function () {
    $skill = Skill::factory()->create();
    $project = Project::factory()->create();
    $project->skills()->sync([$skill->id]);
    expect($skill->projects)->toHaveCount(1);
});

test('experience has correct casts', function () {
    $exp = Experience::factory()->create();
    expect($exp->started_at)->toBeInstanceOf(Carbon::class);
    expect($exp->is_current)->toBeBool();
});

test('experience has bilingual accessors', function () {
    $exp = Experience::factory()->create([
        'company_en' => 'TechCorp',
        'company_es' => 'TechCorp ES',
        'position_en' => 'Developer',
        'position_es' => 'Desarrollador',
        'started_at' => now(),
    ]);
    app()->setLocale('es');
    expect($exp->company)->toBe('TechCorp ES');
    expect($exp->position)->toBe('Desarrollador');
    app()->setLocale('en');
    expect($exp->company)->toBe('TechCorp');
    expect($exp->position)->toBe('Developer');
});

test('category generates slug on creation', function () {
    $cat = Category::factory()->create(['name_en' => 'Test Category', 'slug' => '']);
    expect($cat->slug)->toBe('test-category');
});

test('category has posts relationship', function () {
    $cat = Category::factory()->create();
    expect($cat->posts)->toHaveCount(0);
});

test('tag generates slug on creation', function () {
    $tag = Tag::factory()->create(['name_en' => 'My Tag', 'slug' => '']);
    expect($tag->slug)->toBe('my-tag');
});

test('tag has posts relationship', function () {
    $tag = Tag::factory()->create();
    expect($tag->posts)->toHaveCount(0);
});

test('project has bilingual accessors and relationships', function () {
    $project = Project::factory()->create();
    $skill = Skill::factory()->create();
    $project->skills()->attach($skill);

    expect($project->title_en)->not->toBeEmpty();
    expect($project->title_es)->not->toBeEmpty();
    expect($project->skills)->toHaveCount(1);
});

test('project generates slug from english title', function () {
    $project = Project::factory()->create(['title_en' => 'My Cool Project', 'slug' => '']);
    expect($project->slug)->toBe('my-cool-project');
});

test('post has author category and tags relationships', function () {
    Tag::factory(3)->create();
    $post = Post::factory()->create();
    expect($post->author)->toBeInstanceOf(User::class);
    expect($post->category)->toBeInstanceOf(Category::class);
    expect($post->tags)->not->toBeEmpty();
});

test('post has bilingual accessors', function () {
    $post = Post::factory()->create([
        'title_en' => 'English Title',
        'title_es' => 'Título Español',
    ]);
    app()->setLocale('es');
    expect($post->title)->toBe('Título Español');
    app()->setLocale('en');
    expect($post->title)->toBe('English Title');
});

test('post has correct casts', function () {
    $post = Post::factory()->create(['is_published' => true, 'is_featured' => false]);
    expect($post->is_published)->toBeTrue();
    expect($post->is_featured)->toBeFalse();
});

test('contact message has correct fillable and casts', function () {
    $msg = ContactMessage::factory()->create();
    expect($msg->is_read)->toBeBool();
});

test('user has posts relationship', function () {
    $user = User::factory()->create();
    Post::factory()->create(['author_id' => $user->id]);
    expect($user->posts)->toHaveCount(1);
});

test('profile saves and retrieves full name correctly', function () {
    $profile = Profile::factory()->create();
    expect($profile->full_name)->toBe('Andrés Adrián Pinto Cámara');
    expect($profile->title)->toBe('Fullstack Developer & Software Engineer');
    expect($profile->email)->toBe('andrespintocamara@gmail.com');
    expect($profile->phone)->toBe('+52 983 135 4120');
    expect($profile->license_number)->toBe('8566262');
});

test('profile has social links as array', function () {
    $profile = Profile::factory()->create();
    expect($profile->social_links)->toBeArray();
    expect($profile->social_links)->toHaveKeys(['github', 'linkedin']);
});

test('profile has bilingual bio accessor', function () {
    $profile = Profile::factory()->create([
        'bio_es' => 'Bio en español',
        'bio_en' => 'Bio in English',
    ]);
    app()->setLocale('es');
    expect($profile->bio)->toBe('Bio en español');
    app()->setLocale('en');
    expect($profile->bio)->toBe('Bio in English');
    app()->setLocale('fr');
    expect($profile->bio)->toBe('Bio en español');
});

test('profile getSingleton returns first profile', function () {
    $profile = Profile::factory()->create();
    $singleton = Profile::getSingleton();
    expect($singleton)->not->toBeNull();
    expect($singleton->id)->toBe($profile->id);
});

test('education has bilingual accessors', function () {
    Education::factory()->create([
        'institution_es' => 'Univ ES',
        'institution_en' => 'Univ EN',
        'degree_es' => 'Grado ES',
        'degree_en' => 'Degree EN',
    ]);
    app()->setLocale('es');
    expect(Education::first()->institution)->toBe('Univ ES');
    expect(Education::first()->degree)->toBe('Grado ES');
    app()->setLocale('en');
    expect(Education::first()->institution)->toBe('Univ EN');
    expect(Education::first()->degree)->toBe('Degree EN');
});

test('education has correct casts', function () {
    Education::factory()->create();
    $edu = Education::first();
    expect($edu->started_at)->toBeInt();
    expect($edu->sort_order)->toBeInt();
});

test('language has bilingual accessors', function () {
    Language::factory()->create([
        'language_es' => 'Español',
        'language_en' => 'Spanish',
        'proficiency_es' => 'Nativo',
        'proficiency_en' => 'Native',
    ]);
    app()->setLocale('es');
    expect(Language::first()->language)->toBe('Español');
    expect(Language::first()->proficiency)->toBe('Nativo');
    app()->setLocale('en');
    expect(Language::first()->language)->toBe('Spanish');
    expect(Language::first()->proficiency)->toBe('Native');
});

test('language has correct casts', function () {
    Language::factory()->create();
    expect(Language::first()->sort_order)->toBeInt();
});

test('skill has icon and group fields', function () {
    $skill = Skill::factory()->create(['icon' => 'php', 'group' => 'backend']);
    expect($skill->icon)->toBe('php');
    expect($skill->group)->toBe('backend');
});

test('skill factory creates with icon and group', function () {
    $skill = Skill::factory()->create();
    expect($skill->icon)->not->toBeNull();
    expect($skill->group)->not->toBeNull();
});

test('project has sector field', function () {
    $project = Project::factory()->create(['sector' => 'government']);
    expect($project->sector)->toBe('government');
});

test('project sector can be null', function () {
    $project = Project::factory()->create(['sector' => null]);
    expect($project->sector)->toBeNull();
});
