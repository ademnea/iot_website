
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Dashboard · {{ config('app.name') }}</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
</head>

<body>

@include('sidebar')

<div class="p-4 sm:ml-64">
   <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700">

             <div class="p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50" role="alert">
            <b> Hello {{ auth()->user()->name }}</b>
              </div>

@php
    // Shortcuts to the admin forms. Settings edit the single home-content record; before it
    // exists they go to "Add New Home Content".
    $siteId = \App\Models\website_content::query()->value('id');
    $edit = fn (string $page) => $siteId ? "/{$page}?id={$siteId}" : '/register_home';
    $add = [
        ['/register_projects', 'Add a project', 'Shown on the Projects page'],
        ['/register_news', 'Add a news item or event', 'Shown on the News page'],
        ['/register_publications', 'Add a publication', 'Report or dataset, shown under Research'],
        ['/registerprototype', 'Add a prototype', 'Shown under Research'],
        ['/register_team', 'Add a team member', 'Shown on Team & contact'],
        ['/register_home#partners', 'Add a partner', 'Shown on Home and Partners'],
    ];
    $settings = [
        [$edit('edithome'), 'Lab information', 'Name, motto, vision, mission, objectives'],
        [$edit('editlogo'), 'Logo', 'Header logo'],
        [$edit('editbanner'), 'Banner photo', 'Behind the page banners'],
        [$edit('editcontacts'), 'Contacts', 'Address, email, phone'],
        [$edit('edittopbar'), 'Social links', 'Links and show/hide switches'],
        [$edit('editleader'), "Leader's message", 'Word from the team leader'],
        ['/register', 'Add another admin', 'Someone who can edit the site'],
        ['/oldteam', 'Past team', 'Restore former members'],
        [route('profile.show'), 'Profile & security', 'Your password and two-factor login'],
    ];
    $card = 'flex flex-col items-center justify-center gap-1 h-28 px-3 text-center rounded-lg bg-gray-50 border border-transparent '
          .'hover:bg-brand-50 hover:border-brand-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-500';
@endphp

      @unless ($siteId)
         <div class="p-4 mb-4 text-sm text-gray-700 rounded-lg bg-gray-50 border border-dashed border-brand-300">
            The home page has no content yet. Start with <a class="font-medium text-brand-700 underline" href="/register_home">Add New Home Content</a>.
         </div>
      @endunless

      <h2 class="mb-3 text-sm font-semibold tracking-wide text-gray-500 uppercase">Add content</h2>
      <div class="grid grid-cols-1 gap-4 mb-8 sm:grid-cols-2 lg:grid-cols-3">
         @foreach ($add as [$href, $label, $hint])
            <a href="{{ $href }}" class="{{ $card }}">
               <span class="text-3xl leading-none text-brand-700" aria-hidden="true">+</span>
               <span class="font-medium text-gray-900">{{ $label }}</span>
               <span class="text-xs text-gray-500">{{ $hint }}</span>
            </a>
         @endforeach
      </div>

      <h2 class="mb-3 text-sm font-semibold tracking-wide text-gray-500 uppercase">Site settings</h2>
      <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
         @foreach ($settings as [$href, $label, $hint])
            <a href="{{ $href }}" class="{{ $card }}">
               <span class="font-medium text-gray-900">{{ $label }}</span>
               <span class="text-xs text-gray-500">{{ $hint }}</span>
            </a>
         @endforeach
      </div>

      </div>
      </div>

</body>
</html>