<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Illuminate\Http\Request;

class TrustProxies extends Middleware
{
    /**
     * The trusted proxies for this application.
     *
     * @var array<int, string>|string|null
     */
    protected $proxies;

    // Read from config/app.php (TRUSTED_PROXIES) so it works with a cached config.
    protected function proxies()
    {
        $proxies = config('app.trusted_proxies');

        if ($proxies === null || $proxies === '' || $proxies === '*') {
            return $proxies ?: $this->proxies;
        }

        return array_map('trim', explode(',', $proxies));
    }

    /**
     * The headers that should be used to detect proxies.
     *
     * @var int
     */
    protected $headers =
        Request::HEADER_X_FORWARDED_FOR |
        Request::HEADER_X_FORWARDED_HOST |
        Request::HEADER_X_FORWARDED_PORT |
        Request::HEADER_X_FORWARDED_PROTO |
        Request::HEADER_X_FORWARDED_AWS_ELB;
}
