<?php

namespace App\Services;

use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class VisitorTrackerService
{
    /**
     * Map of common ISO 3166-1 alpha-2 country codes to friendly names.
     *
     * @var array<string, string>
     */
    protected static array $countryNames = [
        'ES' => 'España',
        'US' => 'Estados Unidos',
        'CO' => 'Colombia',
        'MX' => 'México',
        'AR' => 'Argentina',
        'CL' => 'Chile',
        'PE' => 'Perú',
        'EC' => 'Ecuador',
        'VE' => 'Venezuela',
        'BR' => 'Brasil',
        'CA' => 'Canadá',
        'DE' => 'Alemania',
        'FR' => 'Francia',
        'GB' => 'Reino Unido',
        'IT' => 'Italia',
        'PT' => 'Portugal',
        'NL' => 'Países Bajos',
        'CH' => 'Suiza',
        'SE' => 'Suecia',
        'UY' => 'Uruguay',
        'CR' => 'Costa Rica',
        'PA' => 'Panamá',
        'DO' => 'República Dominicana',
        'LOCAL' => 'Entorno Local',
        'UNKNOWN' => 'Desconocido',
    ];

    /**
     * Determine if the incoming request should be tracked.
     */
    public function shouldTrack(Request $request): bool
    {
        // Only track GET / HEAD requests
        if (! in_array($request->method(), ['GET', 'HEAD'])) {
            return false;
        }

        $path = trim($request->path(), '/');

        // Ignored path prefixes
        $ignoredPrefixes = [
            'admin',
            'livewire',
            'storage',
            '_boost',
            'filament',
            'up',
        ];

        foreach ($ignoredPrefixes as $prefix) {
            if ($path === $prefix || str_starts_with($path, $prefix.'/')) {
                return false;
            }
        }

        // Ignored exact static paths
        if (in_array($path, ['robots.txt', 'sitemap.xml', 'favicon.ico'])) {
            return false;
        }

        // Do not track authenticated admin users browsing the site
        $user = $request->user();
        if ($user && ($user->hasRole('admin') || $user->hasRole('super_admin'))) {
            return false;
        }

        // Filter out automated crawlers, bots and scrapers
        $userAgent = (string) $request->userAgent();
        if ($this->isBot($userAgent)) {
            return false;
        }

        return true;
    }

    /**
     * Record a visit from the incoming request.
     */
    public function record(Request $request): ?Visit
    {
        if (! $this->shouldTrack($request)) {
            return null;
        }

        try {
            $ip = (string) ($request->ip() ?? '127.0.0.1');
            $salt = (string) config('app.key', 'tracker-salt');
            $ipHash = hash('sha256', $ip.'-'.now()->toDateString().'-'.$salt);

            $userAgent = (string) $request->userAgent();
            $country = $this->resolveCountry($request, $ip);

            $referer = $request->headers->get('referer');
            $refererHost = null;
            if ($referer) {
                $parsedHost = parse_url($referer, PHP_URL_HOST);
                if (is_string($parsedHost) && $parsedHost !== $request->getHost()) {
                    $refererHost = strtolower($parsedHost);
                } else {
                    // Internal referer (navigating within the site)
                    $referer = null;
                }
            }

            $deviceInfo = $this->parseUserAgent($userAgent);

            return Visit::create([
                'ip_hash' => $ipHash,
                'path' => '/'.ltrim($request->path(), '/'),
                'route_name' => $request->route()?->getName(),
                'method' => $request->method(),
                'referer' => $referer ? substr($referer, 0, 1000) : null,
                'referer_host' => $refererHost,
                'country_code' => $country['code'],
                'country_name' => $country['name'],
                'device_type' => $deviceInfo['device_type'],
                'browser' => $deviceInfo['browser'],
                'platform' => $deviceInfo['platform'],
                'locale' => app()->getLocale(),
                'visited_at' => now(),
            ]);
        } catch (Throwable $e) {
            Log::warning('Error recording visit: '.$e->getMessage());

            return null;
        }
    }

    /**
     * Resolve country code and friendly name using hybrid strategy.
     *
     * @return array{code: string, name: string}
     */
    public function resolveCountry(Request $request, string $ip): array
    {
        // 1. Local or private IP detection
        if ($this->isLocalOrPrivateIp($ip)) {
            return [
                'code' => 'LOCAL',
                'name' => 'Entorno Local',
            ];
        }

        // 2. Cloudflare CF-IPCountry header
        $cfCountry = $request->header('CF-IPCountry');
        if (is_string($cfCountry) && strlen($cfCountry) === 2 && ctype_alpha($cfCountry)) {
            $code = strtoupper($cfCountry);

            return [
                'code' => $code,
                'name' => self::$countryNames[$code] ?? $code,
            ];
        }

        // 3. Fallback: Unknown or unresolvable
        return [
            'code' => 'UNKNOWN',
            'name' => 'Desconocido',
        ];
    }

    /**
     * Check if an IP is local, loopback or private.
     */
    public function isLocalOrPrivateIp(string $ip): bool
    {
        if (in_array($ip, ['127.0.0.1', '::1', 'localhost'])) {
            return true;
        }

        return filter_var(
            $ip,
            FILTER_VALIDATE_IP,
            FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
        ) === false;
    }

    /**
     * Check if user agent matches bot patterns.
     */
    public function isBot(string $userAgent): bool
    {
        if (empty($userAgent)) {
            return true;
        }

        $botPatterns = [
            'bot', 'crawl', 'spider', 'slurp', 'facebookexternalhit',
            'whatsapp', 'curl', 'wget', 'python', 'postman', 'insomnia',
            'googlebot', 'bingbot', 'yandex', 'duckduckbot', 'baiduspider',
            'semrush', 'ahrefs', 'mj12bot', 'headless', 'lighthouse',
        ];

        $pattern = '/('.implode('|', $botPatterns).')/i';

        return (bool) preg_match($pattern, $userAgent);
    }

    /**
     * Parse lightweight device, browser, and platform info from user agent.
     *
     * @return array{device_type: string, browser: string, platform: string}
     */
    public function parseUserAgent(string $userAgent): array
    {
        $deviceType = 'desktop';
        if (preg_match('/(tablet|ipad|playbook|silk)|(android(?!.*mobi))/i', $userAgent)) {
            $deviceType = 'tablet';
        } elseif (preg_match('/(mobile|iphone|ipod|blackberry|opera mini|iemobile|wpdesktop)/i', $userAgent)) {
            $deviceType = 'mobile';
        }

        $platform = 'Unknown';
        if (preg_match('/windows nt/i', $userAgent)) {
            $platform = 'Windows';
        } elseif (preg_match('/iphone|ipad|ipod/i', $userAgent)) {
            $platform = 'iOS';
        } elseif (preg_match('/macintosh|mac os x/i', $userAgent)) {
            $platform = 'macOS';
        } elseif (preg_match('/android/i', $userAgent)) {
            $platform = 'Android';
        } elseif (preg_match('/linux/i', $userAgent)) {
            $platform = 'Linux';
        }

        $browser = 'Unknown';
        if (preg_match('/edg/i', $userAgent)) {
            $browser = 'Edge';
        } elseif (preg_match('/chrome|crios/i', $userAgent) && ! preg_match('/opr|opera/i', $userAgent)) {
            $browser = 'Chrome';
        } elseif (preg_match('/firefox|fxios/i', $userAgent)) {
            $browser = 'Firefox';
        } elseif (preg_match('/safari/i', $userAgent) && ! preg_match('/chrome|crios/i', $userAgent)) {
            $browser = 'Safari';
        } elseif (preg_match('/opr|opera/i', $userAgent)) {
            $browser = 'Opera';
        }

        return [
            'device_type' => $deviceType,
            'browser' => $browser,
            'platform' => $platform,
        ];
    }
}
