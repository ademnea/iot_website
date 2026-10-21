{{-- Public project gallery. Data: PublicPageController@fetch_project_gallery ($projects grouped by project with photos), $contents. --}}
@extends('layouts.public')

@section('title', 'Project gallery')

@section('content')
<section class="banner page-head"><div class="wrap">
    <span class="label"><a href="/gallery">Gallery</a> · Projects</span>
    <h1>Project gallery</h1>
</div></section>

<section class="section"><div class="wrap">
    @php($items = $projects->filter(fn ($rows) => $rows->pluck('photo')->filter()->isNotEmpty()))
    @if ($items->isEmpty())
        <p class="empty">No project photos have been published yet.</p>
        <x-admin-hint what="A project" href="/register_projects" />
    @endif
    @foreach ($items as $rows)
        @php($item = $rows->first())
        <div style="display:grid; gap:14px">
            <div class="meta"><h2 class="subhead">{{ $item->name }}</h2>@if ($item->venue)<span class="label">{{ $item->venue }}</span>@endif</div>
            <div class="grid-photos">
                @foreach ($rows->pluck('photo')->filter()->unique() as $photo)
                    <figure><img src="{{ asset('images/projects/'.rawurlencode($photo)) }}" alt="" loading="lazy"></figure>
                @endforeach
            </div>
        </div>
    @endforeach
</div></section>
@endsection
