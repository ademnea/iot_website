{{-- Public prototype gallery. Data: PublicPageController@fetch_prototype_gallery ($prototypes with their photos), $contents. --}}
@extends('layouts.public')

@section('title', 'Prototype gallery')

@section('content')
<section class="banner page-head"><div class="wrap">
    <span class="label"><a href="/gallery">Gallery</a> · Prototypes</span>
    <h1>Prototype gallery</h1>
</div></section>

<section class="section"><div class="wrap">
    @php($items = $prototypes->filter(fn ($item) => $item->photos->pluck('photo')->filter()->isNotEmpty()))
    @if ($items->isEmpty())
        <p class="empty">No prototype photos have been published yet.</p>
        <x-admin-hint what="A prototype" href="/registerprototype" />
    @endif
    @foreach ($items as $item)
        <div style="display:grid; gap:14px">
            <div class="meta"><h2 class="subhead">{{ $item->name }}</h2></div>
            <div class="grid-photos">
                @foreach ($item->photos->pluck('photo')->filter()->unique() as $photo)
                    <figure><img src="{{ asset('images/prototypes/'.rawurlencode($photo)) }}" alt="" loading="lazy"></figure>
                @endforeach
            </div>
        </div>
    @endforeach
</div></section>
@endsection
