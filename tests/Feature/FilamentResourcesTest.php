<?php

use App\Filament\Resources\CategoryResource;
use App\Filament\Resources\ContactMessageResource;
use App\Filament\Resources\ExperienceResource;
use App\Filament\Resources\PostResource;
use App\Filament\Resources\ProjectResource;
use App\Filament\Resources\SkillResource;
use App\Filament\Resources\SkillResource\Pages\CreateSkill;
use App\Filament\Resources\TagResource;
use App\Models\Category;
use App\Models\ContactMessage;
use App\Models\Experience;
use App\Models\Post;
use App\Models\Project;
use App\Models\Skill;
use App\Models\Tag;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\PermissionRegistrar;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(RoleAndPermissionSeeder::class);
    app()[PermissionRegistrar::class]->forgetCachedPermissions();

    $this->admin = User::factory()->create();
    $this->admin->assignRole('admin');
    app()[PermissionRegistrar::class]->forgetCachedPermissions();
});

test('contact message resource has no create action', function () {
    $pages = ContactMessageResource::getPages();
    expect($pages)->not->toHaveKey('create');
});

test('admin can render every resource index page', function (string $url) {
    $this->admin->assignRole('super_admin');
    app()[PermissionRegistrar::class]->forgetCachedPermissions();

    $this->actingAs($this->admin)
        ->get($url)
        ->assertOk();
})->with([
    'categories' => ['/admin/categories'],
    'contact-messages' => ['/admin/contact-messages'],
    'experiences' => ['/admin/experiences'],
    'posts' => ['/admin/posts'],
    'projects' => ['/admin/projects'],
    'skills' => ['/admin/skills'],
    'tags' => ['/admin/tags'],
]);

test('skill resource exists and has correct model', function () {
    $resource = app(SkillResource::class);
    expect($resource::getModel())->toBe(Skill::class);
});

test('skill resource form includes the group field', function () {
    Livewire::actingAs($this->admin)
        ->test(CreateSkill::class)
        ->assertFormFieldExists('group');
});

test('skill group is persisted when creating a skill via the resource form', function () {
    Livewire::actingAs($this->admin)
        ->test(CreateSkill::class)
        ->fillForm([
            'name_en' => 'GraphQL',
            'name_es' => 'GraphQL',
            'level' => 4,
            'sort_order' => 50,
            'is_visible' => true,
            'group' => 'devops',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $skill = Skill::where('name_en', 'GraphQL')->first();

    expect($skill)->not->toBeNull();
    expect($skill->group)->toBe('devops');
});

test('post resource exists and has correct model', function () {
    $resource = app(PostResource::class);
    expect($resource::getModel())->toBe(Post::class);
});

test('experience resource exists and has correct model', function () {
    $resource = app(ExperienceResource::class);
    expect($resource::getModel())->toBe(Experience::class);
});

test('project resource exists and has correct model', function () {
    $resource = app(ProjectResource::class);
    expect($resource::getModel())->toBe(Project::class);
});

test('category resource exists and has correct model', function () {
    $resource = app(CategoryResource::class);
    expect($resource::getModel())->toBe(Category::class);
});

test('tag resource exists and has correct model', function () {
    $resource = app(TagResource::class);
    expect($resource::getModel())->toBe(Tag::class);
});

test('contact message resource exists and has correct model', function () {
    $resource = app(ContactMessageResource::class);
    expect($resource::getModel())->toBe(ContactMessage::class);
});
