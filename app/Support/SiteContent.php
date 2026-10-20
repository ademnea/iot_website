<?php

namespace App\Support;

use Illuminate\Support\Collection;

// What the public pages may show from the website_content row: a contact detail or social link
// appears only when it has a value AND its "Show on the website" switch is on.
class SiteContent
{
    public const SOCIALS = [
        'linkedin' => 'LinkedIn', 'youtube' => 'YouTube', 'twitter' => 'X',
        'facebook' => 'Facebook', 'instagram' => 'Instagram',
    ];

    // Icon name in resources/views/public/social-icon.blade.php for each column.
    private const ICONS = ['twitter' => 'x'];

    /** @return array{address: ?string, email: ?string, phone: ?string} */
    public static function contacts(?object $site): array
    {
        $show = fn (string $value, string $toggle) => self::visible($site, $toggle) && filled($site?->{$value})
            ? trim($site->{$value}) : null;

        return [
            'address' => $show('physical_address', 'address_visible'),
            'email' => $show('company_email', 'email_visible'),
            'phone' => $show('company_telephone', 'phone_visible'),
        ];
    }

    /** @return Collection<int, array{label: string, url: string, icon: string}> */
    public static function socials(?object $site): Collection
    {
        return collect(self::SOCIALS)
            ->filter(fn ($label, $column) => self::visible($site, $column.'_visible'))
            ->map(fn ($label, $column) => [
                'label' => $label,
                'url' => Url::external($site?->{$column}),
                'icon' => self::ICONS[$column] ?? $column,
            ])
            ->filter(fn ($social) => $social['url'] !== null)
            ->values();
    }

    // Rows saved before the switches existed have no value yet; treat them as visible.
    private static function visible(?object $site, string $toggle): bool
    {
        return (bool) ($site?->{$toggle} ?? true);
    }
}
