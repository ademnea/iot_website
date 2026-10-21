{{-- Public projects page. Data: PublicPageController@fetch_projects ($onprojects = all projects with their photo, $contents). --}}
@extends('layouts.public')

@php
    $groups = [
        'ongoing' => ['Ongoing projects', $onprojects->where('status', 'Ongoing')],
        'past' => ['Past projects', $onprojects->where('status', 'Past')],
    ];
    $fmt = fn ($d) => $d ? \Illuminate\Support\Carbon::parse($d)->format('M Y') : null;
@endphp

@section('title', 'Projects')

@section('content')
<section class="banner page-head"><div class="wrap">
    <span class="label">Projects</span>
    <h1>What we're building</h1>
</div></section>

@foreach ($groups as $anchor => [$heading, $projects])
    @continue($anchor === 'past' && $projects->isEmpty())
    <section class="section" id="{{ $anchor }}" aria-labelledby="{{ $anchor }}-h"><div class="wrap">
        <div class="section-head"><h2 id="{{ $anchor }}-h">{{ $heading }}</h2></div>
        @if ($projects->isEmpty())
            <p class="empty">No projects have been published yet.</p>
            <x-admin-hint what="A project" href="/register_projects" />
        @else
            <div class="bus">
                @foreach ($projects as $project)
                    <article class="node-card">
                        @if ($project->photo)
                            <img class="thumb" src="{{ asset('images/projects/'.rawurlencode($project->photo)) }}" alt="">
                        @endif
                        <div class="meta">
                            <span class="pill {{ $project->status === 'Past' ? 'past' : '' }}">{{ $project->status }}</span>
                            @if ($project->venue)<span class="label">{{ $project->venue }}</span>@endif
                        </div>
                        <h3>{{ $project->name }}</h3>
                        @php($dates = collect([$fmt($project->start_date), $fmt($project->end_date)])->filter()->implode(' – '))
                        @if ($dates || $project->participants)
                            <span class="date">{{ $dates }}@if ($dates && $project->participants) · @endif @if ($project->participants)Participants: {{ $project->participants }}@endif</span>
                        @endif
                        @if (\Illuminate\Support\Str::length($project->description) > 220)
                            <p>{{ \Illuminate\Support\Str::limit($project->description, 200) }}</p>
                            <details class="more"><summary>Read more</summary><div>{!! nl2br(e($project->description)) !!}</div></details>
                        @else
                            <p>{!! nl2br(e($project->description)) !!}</p>
                        @endif
                        @if ($link = \App\Support\Url::external($project->website))
                            <a class="link-arrow" href="{{ $link }}" rel="noopener" target="_blank">Project website</a>
                        @endif
                    </article>
                @endforeach
            </div>
        @endif
    </div></section>
@endforeach
@endsection
