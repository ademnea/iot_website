@props(['what', 'href' => '/homecontent'])
{{-- A placeholder only signed-in admins see, pointing to where the missing content is added. --}}
@auth
    <p class="admin-hint"><b>{{ $what }}</b> hasn't been added yet. <a href="{{ $href }}">Add it in the admin area</a>.</p>
@endauth
