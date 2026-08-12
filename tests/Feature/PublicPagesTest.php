<?php

use App\Livewire\Pages\Contact;
use App\Models\ContactMessage;
use App\Models\Post;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('home page renders successfully', function () {
    $this->seed();
    $response = $this->get(route('home'));
    $response->assertOk();
    $response->assertSee('Andrés');
    $response->assertSee('Fullstack');
});

test('home page displays skills grouped', function () {
    $this->seed();
    $response = $this->get(route('home'));
    $response->assertOk();
    $response->assertSee('backend');
    $response->assertSee('PHP');
});

test('home page displays featured projects', function () {
    $this->seed();
    $response = $this->get(route('home'));
    $response->assertOk();
    $featuredCount = Project::where('is_visible', true)->where('is_featured', true)->count();
    if ($featuredCount > 0) {
        $response->assertSee('Catastro');
    }
});

test('home page displays experience timeline', function () {
    $this->seed();
    $response = $this->get(route('home'));
    $response->assertOk();
    $response->assertSee('SESA');
});

test('home-2 arcade page renders successfully', function () {
    $this->seed();
    $response = $this->get(route('home-2'));
    $response->assertOk();
    $response->assertSee('Andrés');
    $response->assertSee(__('PRESS START'));
    $response->assertSee(__('QUEST LOG'));
});

test('home-2 arcade page displays featured projects and skills', function () {
    $this->seed();
    $response = $this->get(route('home-2'));
    $response->assertOk();
    $featuredCount = Project::where('is_visible', true)->where('is_featured', true)->count();
    if ($featuredCount > 0) {
        $response->assertSee(__('INVENTORY'));
    }
    $response->assertSee(__('CHARACTER STATUS'));
});

test('home-3 neo-brutalist page renders successfully', function () {
    $this->seed();
    $response = $this->get(route('home-3'));
    $response->assertOk();
    $response->assertSee('Andrés');
    $response->assertSee(__('STATUS: READY FOR PRODUCTION'));
    $response->assertSee(__('DEVELOPER'));
    $response->assertSee(__('HIRE ME'));
});

test('home-3 neo-brutalist page displays featured projects and skills', function () {
    $this->seed();
    $response = $this->get(route('home-3'));
    $response->assertOk();
    $featuredCount = Project::where('is_visible', true)->where('is_featured', true)->count();
    if ($featuredCount > 0) {
        $response->assertSee(__('POWER-UPS'));
    }
    $response->assertSee(__('ATTRIBUTES'));
    $response->assertSee(__('TECH & SKILLS'));
});

test('projects index renders successfully', function () {
    $this->seed();
    $response = $this->get(route('projects.index'));
    $response->assertOk();
    $response->assertSee('Catastro');
});

test('projects index filters by sector', function () {
    $this->seed();
    $response = $this->get(route('projects.index', ['sector' => 'government']));
    $response->assertOk();
    expect(Project::where('sector', 'government')->where('is_visible', true)->count())->toBeGreaterThan(0);
});

test('project show renders successfully', function () {
    $this->seed();
    $project = Project::where('is_visible', true)->first();
    expect($project)->not->toBeNull();
    $response = $this->get(route('projects.show', $project));
    $response->assertOk();
    $response->assertSee($project->title);
});

test('project show returns 404 for hidden project', function () {
    $this->seed();
    $project = Project::factory()->create([
        'is_visible' => false,
        'slug' => 'hidden-project-test-'.uniqid(),
        'title_en' => 'Hidden Project Test',
    ]);
    $response = $this->get(route('projects.show', $project));
    $response->assertNotFound();
});

test('blog index renders successfully', function () {
    $this->seed();
    $response = $this->get(route('blog.index'));
    $response->assertOk();
    $response->assertSee('Eloquent');
});

test('blog show renders successfully for published post', function () {
    $this->seed();
    $post = Post::where('is_published', true)->whereNotNull('published_at')->first();
    expect($post)->not->toBeNull();
    $response = $this->get(route('blog.show', $post));
    $response->assertOk();
    $response->assertSee($post->title);
});

test('blog show returns 404 for unpublished post', function () {
    $this->seed();
    $post = Post::where('is_published', false)->first();
    expect($post)->not->toBeNull();
    $response = $this->get(route('blog.show', $post));
    $response->assertNotFound();
});

test('contact page renders successfully', function () {
    $this->seed();
    $response = $this->get(route('contact'));
    $response->assertOk();
    $response->assertSee('Contact');
});

test('contact form submits and saves to database', function () {
    $this->seed();
    Livewire::test(Contact::class)
        ->set('name', 'John Doe')
        ->set('email', 'john@example.com')
        ->set('subject', 'Test Subject')
        ->set('message', 'This is a test message with enough length to pass validation.')
        ->call('submit')
        ->assertSet('sent', true);
    expect(ContactMessage::where('email', 'john@example.com')->count())->toBe(1);
});

test('contact form validates required fields', function () {
    $this->seed();
    Livewire::test(Contact::class)
        ->set('name', '')
        ->set('email', '')
        ->set('message', '')
        ->call('submit')
        ->assertHasErrors(['name', 'email', 'message']);
});

test('contact form honeypot rejects silently', function () {
    $this->seed();
    Livewire::test(Contact::class)
        ->set('name', 'Bot')
        ->set('email', 'bot@example.com')
        ->set('message', 'This is a spam message that should be caught by the honeypot.')
        ->set('website', 'https://spam.com')
        ->call('submit')
        ->assertSet('sent', true);
    expect(ContactMessage::where('email', 'bot@example.com')->count())->toBe(0);
});

test('locale switch changes app locale', function () {
    $this->seed();
    $this->get(route('locale.switch', ['locale' => 'en']));
    $this->get(route('home'));
    expect(app()->getLocale())->toBe('en');
    $this->get(route('locale.switch', ['locale' => 'es']));
    $this->get(route('home'));
    expect(app()->getLocale())->toBe('es');
});

test('locale switch rejects invalid locale', function () {
    $this->seed();
    $this->get(route('locale.switch', ['locale' => 'fr']));
    $this->get(route('home'));
    expect(app()->getLocale())->toBe('es');
});

test('robots.txt returns allowed content with sitemap reference', function () {
    $response = $this->get('/robots.txt');
    $response->assertOk();
    $response->assertHeader('Content-Type', 'text/plain; charset=utf-8');
    $response->assertSee('User-agent: *');
    $response->assertSee('Allow: /');
    $response->assertSee('Sitemap: ');
    $response->assertSee('/sitemap.xml');
});

test('sitemap.xml returns valid xml with correct content type', function () {
    $this->seed();
    $response = $this->get('/sitemap.xml');
    $response->assertOk();
    $response->assertHeader('Content-Type', 'application/xml; charset=utf-8');
    $response->assertSee('<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">', false);
    $response->assertSee(route('home'));
    $response->assertSee(route('projects.index'));
    $response->assertSee(route('blog.index'));
    $response->assertSee(route('contact'));
});

test('sitemap.xml includes visible projects', function () {
    $this->seed();
    $response = $this->get('/sitemap.xml');
    $visibleProjects = Project::where('is_visible', true)->get();
    foreach ($visibleProjects as $project) {
        $response->assertSee(route('projects.show', $project));
    }
});

test('sitemap.xml includes published posts', function () {
    $this->seed();
    $response = $this->get('/sitemap.xml');
    $publishedPosts = Post::where('is_published', true)
        ->where('published_at', '<=', now())
        ->get();
    foreach ($publishedPosts as $post) {
        $response->assertSee(route('blog.show', $post));
    }
});

test('home page has og meta tags', function () {
    $this->seed();
    $response = $this->get(route('home'));
    $response->assertOk();
    $response->assertSee('<meta property="og:title"', false);
    $response->assertSee('<meta property="og:description"', false);
    $response->assertSee('<meta property="og:type" content="profile"', false);
    $response->assertSee('<meta name="twitter:card" content="summary_large_image"', false);
});

test('project show has og meta tags with image', function () {
    $this->seed();
    $project = Project::where('is_visible', true)->first();
    expect($project)->not->toBeNull();
    $response = $this->get(route('projects.show', $project));
    $response->assertOk();
    $response->assertSee('<meta property="og:title"', false);
    $response->assertSee('<link rel="canonical"', false);
});

test('blog show has article og type', function () {
    $this->seed();
    $post = Post::where('is_published', true)->whereNotNull('published_at')->first();
    expect($post)->not->toBeNull();
    $response = $this->get(route('blog.show', $post));
    $response->assertOk();
    $response->assertSee('<meta property="og:type" content="article"', false);
});
