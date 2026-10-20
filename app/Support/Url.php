<?php

namespace App\Support;

class Url
{
    // Admins type links with or without a scheme ("www.example.org", "https://…").
    // Returns a usable absolute URL, or null when nothing was entered.
    public static function external(?string $url): ?string
    {
        $url = trim((string) $url);
        if ($url === '') {
            return null;
        }

        return preg_match('~^https?://~i', $url) ? $url : 'https://'.ltrim($url, '/');
    }
}
