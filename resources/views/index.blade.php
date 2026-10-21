{{-- Public home page. Data: show_data@fetch_home ($contents, $leader, $partners, $randomPhotos). --}}
@extends('layouts.public')

@php
    $site = $contents->first();
    $lead = $leader->first();
    // Where the "add it" hints point: the edit form once home content exists, the add form before.
    $homeForm = $site ? '/edithome?id='.$site->id : '/register_home';
    $objectives = collect(preg_split('/\r\n|\r|\n/', (string) $site?->objectives))->map(fn ($l) => trim($l))->filter();
@endphp

@section('title', 'Home')

@section('content')
<section class="banner hero"><div class="wrap">
    <div class="hero-copy">
        @if ($site?->Labname)<span class="label">{{ $site->Labname }}</span>@endif
        @if ($site?->motto)
            <h1>{{ $site->motto }}</h1>
        @else
            <h1>{{ $site?->Labname ?: config('app.name') }}</h1>
        @endif
        @if (! $site?->motto)<x-admin-hint what="The lab's motto" href="{{ $homeForm }}" />@endif
        @if ($site?->vision)<p class="lead">{{ $site->vision }}</p>@endif
        <div class="actions">
            <a class="btn" href="/projects">See our projects</a>
            <a class="link-arrow" href="/about">Meet the team</a>
        </div>
    </div>
    <figure class="figure">
        @include('public.mesh')
    </figure>
</div></section>

<section class="section" id="about" aria-labelledby="about-h"><div class="wrap">
    <div class="section-head"><h2 id="about-h">Who we are</h2></div>
    @if ($site?->vision || $site?->mission || $objectives->isNotEmpty())
        <div class="pillars">
            @if ($site?->vision)
                <div class="pillar" id="vision"><h3>Vision</h3><p>{!! nl2br(e($site->vision)) !!}</p></div>
            @endif
            @if ($site?->mission)
                <div class="pillar" id="mission"><h3>Mission</h3><p>{!! nl2br(e($site->mission)) !!}</p></div>
            @endif
            @if ($objectives->isNotEmpty())
                <div class="pillar" id="objectives"><h3>Objectives</h3>
                    <ul>@foreach ($objectives as $objective)<li>{{ $objective }}</li>@endforeach</ul>
                </div>
            @endif
        </div>
    @else
        <p class="empty">The lab's vision, mission and objectives will appear here.</p>
    @endif
    @if (! $site?->vision)<x-admin-hint what="Vision" href="{{ $homeForm }}" />@endif
    @if (! $site?->mission)<x-admin-hint what="Mission" href="{{ $homeForm }}" />@endif
    @if ($objectives->isEmpty())<x-admin-hint what="Objectives" href="{{ $homeForm }}" />@endif
</div></section>

@if ($lead && $site?->team_leader_word)
    <section class="section" id="word" aria-labelledby="word-h"><div class="wrap">
        <div class="section-head"><h2 id="word-h">Word from the team leader</h2></div>
        <div class="word">
            @if ($lead->photo)<img src="{{ asset('images/team/'.rawurlencode($lead->photo)) }}" alt="{{ $lead->fname }} {{ $lead->lname }}">@endif
            <blockquote>
                <p>{!! nl2br(e($site->team_leader_word)) !!}</p>
                <span class="label">{{ $lead->fname }} {{ $lead->lname }}@if ($lead->designation) · {{ $lead->designation }}@endif</span>
            </blockquote>
        </div>
    </div></section>
@else
    @auth
        <section class="section" id="word"><div class="wrap">
            @if (! $lead)
                <x-admin-hint what="A team leader" href="/register_team" />
            @else
                <x-admin-hint what="The team leader's message" href="{{ $site ? '/editleader?id='.$site->id : '/register_home' }}" />
            @endif
        </div></section>
    @endauth
@endif

<section class="section" aria-labelledby="explore-h"><div class="wrap">
    <div class="section-head"><h2 id="explore-h">Our work</h2></div>
    <div class="tiles">
        <a class="tile" href="/projects"><b>Projects</b><span class="muted">What we're building, in the field and in the lab</span></a>
        <a class="tile" href="/publications"><b>Research</b><span class="muted">Reports, datasets and prototypes</span></a>
        <a class="tile" href="/news"><b>News</b><span class="muted">Events and updates from the lab</span></a>
        <a class="tile" href="/gallery"><b>Gallery</b><span class="muted">Photos of the team and our work</span></a>
    </div>
</div></section>

@if ($partners->isNotEmpty())
    <section class="section" id="partners" aria-labelledby="partners-h"><div class="wrap">
        <div class="section-head"><h2 id="partners-h">We work with</h2><a class="link-arrow" href="/partners">All partners</a></div>
        <div class="partners">
            @foreach ($partners as $partner)
                <div class="partner">
                    @if ($partner->logo)<img src="{{ asset('images/partners/'.rawurlencode($partner->logo)) }}" alt="">@endif
                    <h3>{{ $partner->name }}</h3>
                </div>
            @endforeach
        </div>
    </div></section>
@else
    @auth<section class="section"><div class="wrap"><x-admin-hint what="Partners" href="/register_home" /></div></section>@endauth
@endif
@endsection
