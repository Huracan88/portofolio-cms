<?php

use App\Filament\Widgets\TopCountriesWidget;
use App\Filament\Widgets\TopPagesWidget;
use App\Filament\Widgets\VisitsChartWidget;
use App\Filament\Widgets\VisitsOverviewWidget;
use App\Models\User;
use App\Models\Visit;
use App\Services\VisitorTrackerService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('visitor tracker service detects local ips', function () {
    $service = app(VisitorTrackerService::class);
    $request = Request::create('/', 'GET');
    $request->server->set('REMOTE_ADDR', '127.0.0.1');

    $country = $service->resolveCountry($request, '127.0.0.1');

    expect($country['code'])->toBe('LOCAL')
        ->and($country['name'])->toBe('Entorno Local');
});

test('visitor tracker service detects cloudflare country header', function () {
    $service = app(VisitorTrackerService::class);
    $request = Request::create('/', 'GET');
    $request->headers->set('CF-IPCountry', 'ES');

    $country = $service->resolveCountry($request, '8.8.8.8');

    expect($country['code'])->toBe('ES')
        ->and($country['name'])->toBe('España');
});

test('visitor tracker service ignores bots and crawlers', function () {
    $service = app(VisitorTrackerService::class);

    expect($service->isBot('Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)'))->toBeTrue()
        ->and($service->isBot('curl/7.68.0'))->toBeTrue()
        ->and($service->isBot('Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36'))->toBeFalse();
});

test('visitor tracker service parses user agent correctly', function () {
    $service = app(VisitorTrackerService::class);

    $desktop = $service->parseUserAgent('Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36');
    expect($desktop['device_type'])->toBe('desktop')
        ->and($desktop['browser'])->toBe('Chrome')
        ->and($desktop['platform'])->toBe('Windows');

    $mobile = $service->parseUserAgent('Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.0 Mobile/15E148 Safari/604.1');
    expect($mobile['device_type'])->toBe('mobile')
        ->and($mobile['platform'])->toBe('iOS')
        ->and($mobile['browser'])->toBe('Safari');
});

test('visiting home page records a visit in the database', function () {
    expect(Visit::count())->toBe(0);

    $response = $this->withServerVariables(['REMOTE_ADDR' => '127.0.0.1'])
        ->withHeaders(['User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'])
        ->get('/');

    $response->assertOk();

    expect(Visit::count())->toBe(1);

    $visit = Visit::first();
    expect($visit->path)->toBe('/')
        ->and($visit->country_code)->toBe('LOCAL')
        ->and($visit->ip_hash)->not->toBeEmpty();
});

test('visiting with cloudflare header records visitor country', function () {
    $response = $this->withServerVariables(['REMOTE_ADDR' => '198.51.100.1'])
        ->withHeaders([
            'CF-IPCountry' => 'CO',
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
        ])
        ->get('/');

    $response->assertOk();

    $visit = Visit::latest('id')->first();
    expect($visit)->not->toBeNull()
        ->and($visit->country_code)->toBe('CO')
        ->and($visit->country_name)->toBe('Colombia');
});

test('admin routes are not recorded as visits', function () {
    $response = $this->get('/admin/login');

    expect(Visit::where('path', 'like', '/admin%')->count())->toBe(0);
});

test('authenticated admin user browsing the site is not recorded', function () {
    $this->seed();

    $admin = User::where('email', 'admin@example.com')->first();
    $initialCount = Visit::count();

    $response = $this->actingAs($admin)->get('/');
    $response->assertOk();

    expect(Visit::count())->toBe($initialCount);
});

test('filament analytics widgets are restricted to admin and super_admin', function () {
    $this->seed();

    // Unauthenticated
    expect(VisitsOverviewWidget::canView())->toBeFalse()
        ->and(VisitsChartWidget::canView())->toBeFalse()
        ->and(TopPagesWidget::canView())->toBeFalse()
        ->and(TopCountriesWidget::canView())->toBeFalse();

    // Editor (non-admin)
    $editor = User::where('email', 'editor@example.com')->first();
    $this->actingAs($editor);

    expect(VisitsOverviewWidget::canView())->toBeFalse()
        ->and(VisitsChartWidget::canView())->toBeFalse()
        ->and(TopPagesWidget::canView())->toBeFalse()
        ->and(TopCountriesWidget::canView())->toBeFalse();

    // Admin / super_admin
    $admin = User::where('email', 'admin@example.com')->first();
    $this->actingAs($admin);

    expect(VisitsOverviewWidget::canView())->toBeTrue()
        ->and(VisitsChartWidget::canView())->toBeTrue()
        ->and(TopPagesWidget::canView())->toBeTrue()
        ->and(TopCountriesWidget::canView())->toBeTrue();
});

test('filament analytics widgets render successfully for admin', function () {
    $this->seed();

    $admin = User::where('email', 'admin@example.com')->first();
    $this->actingAs($admin);

    Visit::factory()->count(10)->create([
        'country_code' => 'ES',
        'country_name' => 'España',
        'path' => '/projects',
        'visited_at' => now(),
    ]);

    Livewire::test(VisitsOverviewWidget::class)
        ->assertSuccessful()
        ->assertSee('Visitas Hoy')
        ->assertSee('Visitantes Únicos');

    Livewire::test(VisitsChartWidget::class)
        ->assertSuccessful()
        ->assertSee('Evolución de Visitas');

    Livewire::test(TopPagesWidget::class)
        ->assertSuccessful()
        ->assertSee('/projects');

    Livewire::test(TopCountriesWidget::class)
        ->assertSuccessful()
        ->assertSee('España');
});
