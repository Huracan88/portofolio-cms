<?php

use App\Livewire\Pages\Blog\Index as BlogIndex;
use App\Livewire\Pages\Blog\Show as BlogShow;
use App\Livewire\Pages\Contact;
use App\Livewire\Pages\Home;
use App\Livewire\Pages\Projects\Index as ProjectsIndex;
use App\Livewire\Pages\Projects\Show as ProjectsShow;
use App\Models\Post;
use App\Models\Project;
use Illuminate\Support\Facades\Route;

Route::get('/', Home::class)->name('home');
Route::get('/projects', ProjectsIndex::class)->name('projects.index');
Route::get('/projects/{project:slug}', ProjectsShow::class)->name('projects.show');
Route::get('/blog', BlogIndex::class)->name('blog.index');
Route::get('/blog/{post:slug}', BlogShow::class)->name('blog.show');
Route::get('/contact', Contact::class)->name('contact');

Route::get('/locale/{locale}', function (string $locale) {
    if (! in_array($locale, ['es', 'en'])) {
        $locale = 'es';
    }

    session()->put('locale', $locale);
    cookie()->queue(cookie()->forever('locale', $locale));

    return redirect()->back();
})->name('locale.switch');

Route::get('/robots.txt', function () {
    return response("User-agent: *\nAllow: /\nSitemap: ".url('/sitemap.xml'))
        ->header('Content-Type', 'text/plain; charset=utf-8');
});

Route::get('/sitemap.xml', function () {
    $projects = Project::where('is_visible', true)->get();
    $posts = Post::query()
        ->where('is_published', true)
        ->where('published_at', '<=', now())
        ->get();

    return response()->view('sitemap', [
        'projects' => $projects,
        'posts' => $posts,
    ])->header('Content-Type', 'application/xml; charset=utf-8');
});
