{{-- Public research page. Data: PublicPageController@fetch_publications ($publications, $prototypes with photos, $contents). --}}
@extends('layouts.public')

@php
    $groups = [
        'reports' => ['Technical reports', $publications->filter(fn ($p) => strtolower((string) $p->type) !== 'dataset')],
        'datasets' => ['Datasets', $publications->filter(fn ($p) => strtolower((string) $p->type) === 'dataset')],
    ];
@endphp

@section('title', 'Research')

@section('content')
<section class="banner page-head"><div class="wrap">
    <span class="label">Research</span>
    <h1>Reports, datasets and prototypes</h1>
</div></section>

@foreach ($groups as $anchor => [$heading, $items])
    @continue($anchor === 'datasets' && $items->isEmpty())
    <section class="section" id="{{ $anchor }}" aria-labelledby="{{ $anchor }}-h"><div class="wrap">
        <div class="section-head"><h2 id="{{ $anchor }}-h">{{ $heading }}</h2></div>
        @if ($items->isEmpty())
            <p class="empty">No publications have been published yet.</p>
            <x-admin-hint what="A publication" href="/register_publications" />
        @else
            <div class="rows">
                @foreach ($items as $pub)
                    <article class="row">
                        @if ($pub->photo)
                            <img class="cover" src="{{ asset('images/publications/'.rawurlencode($pub->photo)) }}" alt="">
                        @else
                            <span class="cover" aria-hidden="true"></span>
                        @endif
                        <div style="display:grid; gap:4px; min-width:0">
                            <span class="label">{{ $pub->type }}@if ($pub->date_published) · {{ $pub->date_published->format('j M Y') }}@endif</span>
                            <h3>{{ $pub->title }}</h3>
                            @if ($pub->author)<span class="by">{{ $pub->author }}</span>@endif
                            @if ($pub->description)
                                <details class="more"><summary>Summary</summary><div>{!! nl2br(e($pub->description)) !!}</div></details>
                            @endif
                        </div>
                        @if ($pub->file_link)
                            <a class="link-arrow" href="{{ asset('documents/publications/'.rawurlencode($pub->file_link)) }}" download>Download</a>
                        @endif
                    </article>
                @endforeach
            </div>
        @endif
    </div></section>
@endforeach

<section class="section" id="products" aria-labelledby="products-h"><div class="wrap">
    <div class="section-head"><h2 id="products-h">Prototypes</h2><a class="link-arrow" href="/prototypes_gallery">Prototype gallery</a></div>
    @if ($prototypes->isEmpty())
        <p class="empty">No prototypes have been published yet.</p>
        <x-admin-hint what="A prototype" href="/registerprototype" />
    @else
        <div class="cards">
            @foreach ($prototypes as $prototype)
                <article class="card">
                    @if ($prototype->cover)<img class="thumb" src="{{ asset('images/prototypes/'.rawurlencode($prototype->cover)) }}" alt="">@endif
                    <h3>{{ $prototype->name }}</h3>
                    <p>{!! nl2br(e($prototype->description)) !!}</p>
                </article>
            @endforeach
        </div>
    @endif
</div></section>
@endsection
