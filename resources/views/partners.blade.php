{{-- Public partners page. Data: show_data@fetch_partners ($partners, $contents). --}}
@extends('layouts.public')

@section('title', 'Partners')

@section('content')
<section class="banner page-head"><div class="wrap">
    <span class="label">Partners</span>
    <h1>We work with</h1>
</div></section>

<section class="section"><div class="wrap">
    @if ($partners->isEmpty())
        <p class="empty">Partners will appear here.</p>
        <x-admin-hint what="A partner" href="/register_home" />
    @else
        <div class="partners">
            @foreach ($partners as $partner)
                <article class="partner">
                    @if ($partner->logo)<img src="{{ asset('images/partners/'.rawurlencode($partner->logo)) }}" alt="">@endif
                    <h3>{{ $partner->name }}</h3>
                    @if ($partner->description)<p>{!! nl2br(e($partner->description)) !!}</p>@endif
                    @if ($link = \App\Support\Url::external($partner->website_link))
                        <a class="link-arrow" href="{{ $link }}" rel="noopener" target="_blank">Website</a>
                    @endif
                </article>
            @endforeach
        </div>
    @endif
</div></section>
@endsection
