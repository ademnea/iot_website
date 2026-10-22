@php
    // The single website_content row the controllers pass to every public page.
    $site = ($contents ?? collect())->first();
    // The lab's name as admins entered it; until then the app name (APP_NAME) is used.
    $labName = $site?->Labname ?: config('app.name');
    // Only details that have a value and are switched on ("Show on the website" in the admin).
    $contact = \App\Support\SiteContent::contacts($site);
    $socials = \App\Support\SiteContent::socials($site);
    $bannerPhoto = $site?->banner ? asset('images/banner/'.rawurlencode($site->banner)) : null;
    $nav = [
        '/' => 'Home', '/projects' => 'Projects', '/publications' => 'Research',
        '/news' => 'News', '/about' => 'Team & contact',
    ];
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') · {{ $labName }}</title>
    @if ($site?->vision)
        <meta name="description" content="{{ \Illuminate\Support\Str::limit($site->vision, 155) }}">
    @endif
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="icon" type="image/png" href="{{ asset('img/icon-192.png') }}" sizes="192x192">
    <link rel="apple-touch-icon" href="{{ asset('img/apple-touch-icon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700&family=Heebo:wght@400;500&family=IBM+Plex+Mono:wght@500&display=swap">
    @vite('resources/css/public.css')
</head>
<body @if ($bannerPhoto) style="--banner-photo: url('{{ $bannerPhoto }}')" @endif>
<a class="skip" href="#main">Skip to content</a>

@if (array_filter($contact) || $socials->isNotEmpty())
    <div class="band"><div class="wrap">
        <div class="band-contacts">
            @if ($contact['address'])<span>{{ $contact['address'] }}</span>@endif
            @if ($contact['phone'])<a href="tel:{{ preg_replace('/[^0-9+]/', '', $contact['phone']) }}">{{ $contact['phone'] }}</a>@endif
            @if ($contact['email'])<a href="mailto:{{ $contact['email'] }}">{{ $contact['email'] }}</a>@endif
        </div>
        @if ($socials->isNotEmpty())
            <nav class="socials" aria-label="Social media">
                @foreach ($socials as $social)
                    <a href="{{ $social['url'] }}" rel="noopener" target="_blank" aria-label="{{ $social['label'] }}" title="{{ $social['label'] }}">@include('public.social-icon', ['network' => $social['icon']])</a>
                @endforeach
            </nav>
        @endif
    </div></div>
@endif

<header class="site-header"><div class="wrap">
    <a class="logo" href="/" aria-label="{{ $labName }}, home">
        {{-- An uploaded logo wins; until one is uploaded, the lab's IoT-ra logo from the repository is shown. --}}
        <img src="{{ $site?->logo ? asset('images/logo/'.rawurlencode($site->logo)) : asset('img/logo-iot-ra.png') }}" alt="{{ $labName }}">
    </a>
    {{-- Phones and tablets: the menu folds behind this button (the script below turns it on, so without JS the links stay visible). --}}
    <button class="menu-toggle" type="button" aria-controls="site-nav" aria-expanded="false" hidden>
        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
        <span>Menu</span>
    </button>
    <nav class="nav" id="site-nav" aria-label="Main">
        @foreach ($nav as $href => $label)
            <a href="{{ $href }}" @if (request()->is(ltrim($href, '/') ?: '/')) aria-current="page" @endif>{{ $label }}</a>
        @endforeach
        <a class="admin" href="/login">Admin login</a>
    </nav>
</div></header>

<main id="main">
    @yield('content')
</main>

<footer>
    <div class="wrap">
        <div style="display:grid; gap:12px">
            <span class="foot-mark">{{ $labName }}</span>
            @if ($site?->motto)<p>{{ $site->motto }}</p>@endif
        </div>
        <div>
            <h2>Explore</h2>
            <ul>
                <li><a href="/projects">Projects</a></li>
                <li><a href="/publications">Research</a></li>
                <li><a href="/news">News</a></li>
                <li><a href="/partners">Partners</a></li>
                <li><a href="/gallery">Gallery</a></li>
                <li><a href="/about#contactus">Contact us</a></li>
            </ul>
        </div>
        <div>
            <h2>Contact</h2>
            <ul>
                @if ($contact['address'])<li>{{ $contact['address'] }}</li>@endif
                @if ($contact['phone'])<li>{{ $contact['phone'] }}</li>@endif
                @if ($contact['email'])<li><a href="mailto:{{ $contact['email'] }}">{{ $contact['email'] }}</a></li>@endif
                @if (! array_filter($contact))
                    <li><a href="/about#contactus">Send us a message</a></li>
                @endif
            </ul>
        </div>
    </div>
    <div class="foot-base"><div class="wrap">
        <span>© {{ date('Y') }} {{ $labName }}</span>
        @if ($socials->isNotEmpty())
            <nav class="socials" aria-label="Social media">
                @foreach ($socials as $social)
                    <a href="{{ $social['url'] }}" rel="noopener" target="_blank" aria-label="{{ $social['label'] }}" title="{{ $social['label'] }}">@include('public.social-icon', ['network' => $social['icon']])</a>
                @endforeach
            </nav>
        @endif
    </div></div>
</footer>
<script>
    (() => {
        const header = document.querySelector('.site-header');
        const toggle = header.querySelector('.menu-toggle');
        const setOpen = open => { header.classList.toggle('open', open); toggle.setAttribute('aria-expanded', open); };
        toggle.hidden = false;
        header.classList.add('has-toggle');
        toggle.addEventListener('click', () => setOpen(! header.classList.contains('open')));
        document.addEventListener('keydown', e => { if (e.key === 'Escape' && header.classList.contains('open')) { setOpen(false); toggle.focus(); } });
    })();
</script>
</body>
</html>
