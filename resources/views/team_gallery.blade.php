{{-- Public team gallery. Data: show_data@fetch_team_gallery ($teams = all members, $contents). --}}
@extends('layouts.public')

@section('title', 'Team gallery')

@section('content')
<section class="banner page-head"><div class="wrap">
    <span class="label"><a href="/gallery">Gallery</a> · Team</span>
    <h1>Team gallery</h1>
</div></section>

<section class="section"><div class="wrap">
    @php($people = $teams->filter(fn ($m) => $m->photo))
    @if ($people->isEmpty())
        <p class="empty">No team photos have been published yet.</p>
        <x-admin-hint what="A team member" href="/register_team" />
    @else
        <div class="grid-photos">
            @foreach ($people as $member)
                <figure>
                    <img src="{{ asset('images/team/'.rawurlencode($member->photo)) }}" alt="" loading="lazy">
                    <figcaption>{{ $member->fname }} {{ $member->lname }}@if ($member->designation) · {{ $member->designation }}@endif</figcaption>
                </figure>
            @endforeach
        </div>
    @endif
</div></section>
@endsection
