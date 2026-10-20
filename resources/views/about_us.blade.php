{{-- Public team & contact page. Data: show_data@fetch_team ($teams, $PhDs, $masters, $admins, $interns, $partners, $contents). --}}
@extends('layouts.public')

@php
    $site = $contents->first();
    $groups = [
        'Senior researchers' => $teams, 'PhD students' => $PhDs, 'Masters students' => $masters,
        'Administration' => $admins, 'Interns' => $interns,
    ];
    $anyone = collect($groups)->contains(fn ($g) => $g->isNotEmpty());
    $contact = \App\Support\SiteContent::contacts($site);
@endphp

@section('title', 'Team & contact')

@section('content')
<section class="banner page-head"><div class="wrap">
    <span class="label">Team &amp; contact</span>
    <h1>Meet the team</h1>
</div></section>

<section class="section" id="researchers" aria-label="Team"><div class="wrap">
    @if (! $anyone)
        <p class="empty">Team members will appear here.</p>
        <x-admin-hint what="A team member" href="/register_team" />
    @endif
    @foreach ($groups as $heading => $members)
        @continue($members->isEmpty())
        <div style="display:grid; gap:20px">
            <h2 class="subhead">{{ $heading }}</h2>
            <div class="people">
                @foreach ($members as $member)
                    <article class="person">
                        @if ($member->photo)
                            <img src="{{ asset('images/team/'.rawurlencode($member->photo)) }}" alt="" loading="lazy">
                        @endif
                        <h3>{{ $member->fname }} {{ $member->lname }}</h3>
                        @if ($member->designation)<span class="role">{{ $member->designation }}</span>@endif
                        @if ($member->bio)
                            <details class="more"><summary>About {{ $member->fname }}</summary><div>{!! nl2br(e($member->bio)) !!}</div></details>
                        @endif
                    </article>
                @endforeach
            </div>
        </div>
    @endforeach
</div></section>

@if ($partners->isNotEmpty())
    <section class="section" id="partners" aria-labelledby="partners-h"><div class="wrap">
        <div class="section-head"><h2 id="partners-h">Partners</h2><a class="link-arrow" href="/partners">All partners</a></div>
        <div class="partners">
            @foreach ($partners as $partner)
                <div class="partner">
                    @if ($partner->logo)<img src="{{ asset('images/partners/'.rawurlencode($partner->logo)) }}" alt="">@endif
                    <h3>{{ $partner->name }}</h3>
                </div>
            @endforeach
        </div>
    </div></section>
@endif

<section class="section" id="contactus" aria-labelledby="contact-h"><div class="wrap">
    <div class="section-head"><h2 id="contact-h">Get in touch</h2></div>
    <div class="contact">
        <dl>
            @if ($contact['address'])<dt class="label">Address</dt><dd>{{ $contact['address'] }}</dd>@endif
            @if ($contact['email'])<dt class="label">Email</dt><dd><a href="mailto:{{ $contact['email'] }}">{{ $contact['email'] }}</a></dd>@endif
            @if ($contact['phone'])<dt class="label">Phone</dt><dd>{{ $contact['phone'] }}</dd>@endif
            @if (! array_filter($contact))
                <dd class="muted" style="grid-column: 1 / -1">Use the form to send the lab a message.</dd>
            @endif
        </dl>
        <form class="form" method="post" action="/insert_message">
            @csrf
            @if (session('message'))
                <p class="alert ok" role="status">{{ session('message') }} We'll get back to you.</p>
            @endif
            @if ($errors->any())
                <div class="alert error" role="alert"><ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
            @endif
            <div class="field"><label for="contact-name">Name</label><input id="contact-name" name="name" type="text" value="{{ old('name') }}" autocomplete="name" required></div>
            <div class="field"><label for="contact-email">Email</label><input id="contact-email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required></div>
            <div class="field"><label for="contact-phone">Phone (optional)</label><input id="contact-phone" name="phone" type="tel" value="{{ old('phone') }}" autocomplete="tel"></div>
            <div class="field"><label for="contact-message">Message</label><textarea id="contact-message" name="message" rows="4" maxlength="255" required>{{ old('message') }}</textarea></div>
            <div><button class="btn" type="submit">Send message</button></div>
        </form>
        @if ($errors->any() || session('message'))
            {{-- the redirect after sending lands at the top of the page; bring the visitor back to the form --}}
            <script>document.getElementById('contactus').scrollIntoView();</script>
        @endif
    </div>
</div></section>
@endsection
