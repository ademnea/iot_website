{{-- Public news page. Data: show_data@fetch_events ($onevents grouped by event with photos, $pastevents rows, $contents). --}}
@extends('layouts.public')

@php
    // Past events arrive as one row per photo (events joined to event_photos); group them per event.
    $past = $pastevents->groupBy('event_id');
    $when = function ($e) {
        $s = $e->start_date ? \Illuminate\Support\Carbon::parse($e->start_date) : null;
        $f = $e->end_date ? \Illuminate\Support\Carbon::parse($e->end_date) : null;
        if (! $s) return null;
        return ($f && ! $f->isSameDay($s)) ? $s->format('j M').' – '.$f->format('j M Y') : $s->format('j M Y');
    };
    $groups = ['upcoming' => ['Upcoming and ongoing', $onevents], 'past' => ['Past events', $past]];
@endphp

@section('title', 'News')

@section('content')
<section class="banner page-head"><div class="wrap">
    <span class="label">News</span>
    <h1>Latest from the lab</h1>
</div></section>

@foreach ($groups as $anchor => [$heading, $events])
    @continue($anchor === 'past' && $events->isEmpty())
    <section class="section" id="{{ $anchor }}" aria-labelledby="{{ $anchor }}-h"><div class="wrap">
        <div class="section-head"><h2 id="{{ $anchor }}-h">{{ $heading }}</h2></div>
        @if ($events->isEmpty())
            <p class="empty">No events have been published yet.</p>
            <x-admin-hint what="An event" href="/register_news" />
        @else
            <div class="cards">
                @foreach ($events as $rows)
                    @php($event = $rows->first())
                    @php($photos = $rows->pluck('photo')->filter()->unique())
                    <article class="card">
                        @if ($photos->isNotEmpty())
                            <img class="thumb" src="{{ asset('images/events/'.rawurlencode($photos->first())) }}" alt="">
                        @endif
                        @if ($date = $when($event))<span class="date">{{ $date }}</span>@endif
                        <h3>{{ $event->title }}</h3>
                        @if ($event->venue)<span class="label">{{ $event->venue }}</span>@endif
                        @if (\Illuminate\Support\Str::length($event->description) > 220)
                            <p>{{ \Illuminate\Support\Str::limit($event->description, 200) }}</p>
                            <details class="more"><summary>Read more</summary><div>{!! nl2br(e($event->description)) !!}</div></details>
                        @else
                            <p>{!! nl2br(e($event->description)) !!}</p>
                        @endif
                        @if ($photos->count() > 1)
                            <div class="photos">
                                @foreach ($photos->skip(1) as $photo)
                                    <img src="{{ asset('images/events/'.rawurlencode($photo)) }}" alt="" loading="lazy">
                                @endforeach
                            </div>
                        @endif
                        @if ($link = \App\Support\Url::external($event->article_link))
                            <a class="link-arrow" href="{{ $link }}" rel="noopener" target="_blank">Read the article</a>
                        @endif
                    </article>
                @endforeach
            </div>
        @endif
    </div></section>
@endforeach
@endsection
