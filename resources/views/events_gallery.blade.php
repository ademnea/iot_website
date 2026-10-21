{{-- Public event gallery. Data: PublicPageController@fetch_events_gallery ($events grouped by event with photos), $contents. --}}
@extends('layouts.public')

@section('title', 'Event gallery')

@section('content')
<section class="banner page-head"><div class="wrap">
    <span class="label"><a href="/gallery">Gallery</a> · Events</span>
    <h1>Event gallery</h1>
</div></section>

<section class="section"><div class="wrap">
    @php($items = $events->filter(fn ($rows) => $rows->pluck('photo')->filter()->isNotEmpty()))
    @if ($items->isEmpty())
        <p class="empty">No event photos have been published yet.</p>
        <x-admin-hint what="An event" href="/register_news" />
    @endif
    @foreach ($items as $rows)
        @php($item = $rows->first())
        <div style="display:grid; gap:14px">
            <div class="meta"><h2 class="subhead">{{ $item->title }}</h2>@if ($item->venue)<span class="label">{{ $item->venue }}</span>@endif</div>
            <div class="grid-photos">
                @foreach ($rows->pluck('photo')->filter()->unique() as $photo)
                    <figure><img src="{{ asset('images/events/'.rawurlencode($photo)) }}" alt="" loading="lazy"></figure>
                @endforeach
            </div>
        </div>
    @endforeach
</div></section>
@endsection
