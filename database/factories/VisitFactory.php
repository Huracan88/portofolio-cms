<?php

namespace Database\Factories;

use App\Models\Visit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Visit>
 */
class VisitFactory extends Factory
{
    protected $model = Visit::class;

    public function definition(): array
    {
        $countries = [
            ['code' => 'ES', 'name' => 'Spain'],
            ['code' => 'US', 'name' => 'United States'],
            ['code' => 'CO', 'name' => 'Colombia'],
            ['code' => 'MX', 'name' => 'Mexico'],
            ['code' => 'DE', 'name' => 'Germany'],
            ['code' => 'GB', 'name' => 'United Kingdom'],
            ['code' => 'AR', 'name' => 'Argentina'],
            ['code' => 'LOCAL', 'name' => 'Entorno Local'],
        ];

        $paths = [
            '/',
            '/projects',
            '/blog',
            '/contact',
            '/projects/ecommerce-platform',
            '/projects/saas-dashboard',
            '/blog/optimizing-laravel-queries',
        ];

        $referers = [
            'https://www.google.com/',
            'https://www.linkedin.com/in/andres',
            'https://github.com/huracan88',
            'https://twitter.com/',
            null,
        ];

        $devices = ['desktop', 'mobile', 'tablet'];
        $browsers = ['Chrome', 'Firefox', 'Safari', 'Edge'];
        $platforms = ['Windows', 'macOS', 'Linux', 'iOS', 'Android'];

        $country = fake()->randomElement($countries);
        $referer = fake()->randomElement($referers);
        $refererHost = $referer ? parse_url($referer, PHP_URL_HOST) : null;

        return [
            'ip_hash' => hash('sha256', fake()->ipv4().'-'.fake()->date().'-salt'),
            'path' => fake()->randomElement($paths),
            'route_name' => 'page',
            'method' => 'GET',
            'referer' => $referer,
            'referer_host' => $refererHost,
            'country_code' => $country['code'],
            'country_name' => $country['name'],
            'device_type' => fake()->randomElement($devices),
            'browser' => fake()->randomElement($browsers),
            'platform' => fake()->randomElement($platforms),
            'locale' => fake()->randomElement(['es', 'en']),
            'visited_at' => fake()->dateTimeBetween('-30 days', 'now'),
        ];
    }
}
