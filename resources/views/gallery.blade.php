{{-- Public gallery index. Data: show_data@fetch_gallery ($contents, $randomPhotos = six random members). --}}
@extends('layouts.public')

@section('title', 'Gallery')

@section('content')
<section class="banner page-head"><div class="wrap">
    <span class="label">Gallery</span>
    <h1>Photos of the team and our work</h1>
</div></section>

<section class="section"><div class="wrap">
    <div class="tiles">
        <a class="tile" href="/team_gallery"><b>Team</b><span class="muted">The people of the lab</span></a>
        <a class="tile" href="/project_gallery"><b>Projects</b><span class="muted">Photos from each project</span></a>
        <a class="tile" href="/prototypes_gallery"><b>Prototypes</b><span class="muted">What we've built</span></a>
        <a class="tile" href="/events_gallery"><b>Events</b><span class="muted">Moments from lab events</span></a>
    </div>
    @php($photos = $randomPhotos->filter(fn ($m) => $m->photo))
    @if ($photos->isNotEmpty())
        <div class="grid-photos">
            @foreach ($photos as $member)
                <figure><img src="{{ asset('images/team/'.rawurlencode($member->photo)) }}" alt="{{ $member->fname }} {{ $member->lname }}" loading="lazy"></figure>
            @endforeach
        </div>
    @endif
</div></section>
@endsection
