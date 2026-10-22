
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Projects · {{ config('app.name') }}</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
</head>

<body>


@include('sidebar')

   <div class="p-4 sm:ml-64">
   <div class="p-4 border-2 border-gray-200 border rounded-lg light:border-gray-700">

<center>

      {{-- displaying an alert after registering a category --}}
         @if (session('success'))
            <div class="p-4 mb-4 text-sm text-brand-800 rounded-lg bg-brand-50" role="alert">
            <b> {{ session('success') }}</b>
            </div> 

        @elseif (session('updated')) 
             <div class="p-4 mb-4 text-sm text-brand-800 rounded-lg bg-brand-50" role="alert">
            <b> {{ session('updated') }}</b>
              </div>
            @endif
{{-- start of the page content --}}

<button type="button" id="top" class="text-white bg-brand-700 hover:bg-brand-800 focus:outline-none focus:ring-4 focus:ring-brand-300 font-medium rounded-full text-sm px-5 py-2.5 text-center mr-2 mb-2">
<a href="/register_projects">Add New Project</a>
</button>

</center>
<br>
<div class="sm:mx-[10%]">
<h1 class="font-medium">
   Projects Content 
</h1>
<hr>
<br>
<br>
<div class="container-xxl py-5">
    <div class="container">

   @foreach ($onprojects as $onproject)
       
            <h1 class="font-medium"> Ongoing projects</h1>
    <br>
                <div class="bg-white rounded-lg overflow-hidden shadow-md w-full sm:w-1/2">
                    <div class="overflow-hidden">
                        <img class="" src="images/projects/{{ $onproject->cover }}" >
                    </div>
                    <div class="flex border-b border-gray-200">
                        <small class="flex-1 text-center py-2 border-r border-gray-200"><i class="fas fa-map-marker-alt text-brand-700 me-2"></i>
                        {{ $onproject->venue }}
                        </small>
                        <small class="flex-1 text-center py-2 border-r border-gray-200"><i class="far fa-calendar-alt text-brand-700 me-2"></i>
                        {{ $onproject->duration }} weeks
                        </small>
                        <small class="flex-1 text-center py-2"><i class="fas fa-user text-brand-700 me-2"></i>
                        {{-- {{ $onproject->participants }} people --}}
                        </small>
                    </div>
                    <div class="p-4">
                        <p>{{ $onproject->name }}</p>
                        <br><hr>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="col-span-1 flex justify-center items-center">
                                <x-trash-button action="/deleteproject" :confirm="'Delete the project “'.$onproject->name.'”'.($onproject->prototypes_count ? ' and its '.$onproject->prototypes_count.' '.Str::plural('prototype', $onproject->prototypes_count) : '').'?'" :fields="['id' => $onproject->id]" />
                            </div>

                            <div class="col-span-1 flex justify-center items-center">
                            <a href="/editproject?id={{ $onproject->id }}">
                               <svg xmlns="http://www.w3.org/2000/svg" fill="none"  class="h-6 w-6 text-blue-500" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                </svg>
                            </a>
                            </div>
                            </div>
                    </div>
                </div>
   @endforeach

   @foreach ($pastprojects as $pastproject)

   <br><br>
            <h1 class="font-medium"> Past projects</h1>
            <br>
                            <div class="bg-white rounded-lg overflow-hidden shadow-md w-full sm:w-1/2">
                    <div class="overflow-hidden">
                        <img class="" src="images/projects/{{ $pastproject->cover }}" >
                    </div>
                    <div class="flex border-b border-gray-200">
                        <small class="flex-1 text-center py-2 border-r border-gray-200"><i class="fas fa-map-marker-alt text-brand-700 me-2"></i>
                        {{ $pastproject->venue }}
                        </small>
                        <small class="flex-1 text-center py-2 border-r border-gray-200"><i class="far fa-calendar-alt text-brand-700 me-2"></i>
                        {{ $pastproject->duration }} weeks
                        </small>
                        <small class="flex-1 text-center py-2"><i class="fas fa-user text-brand-700 me-2"></i>
                        {{-- {{ $pastproject->participants }} people --}}
                        </small>
                    </div>
                    <div class="p-4">
                        <p>{{ $pastproject->description }}</p>
                        <br><hr>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="col-span-1 flex justify-center items-center">
                                <x-trash-button action="/deleteproject" :confirm="'Delete the project “'.$pastproject->name.'”'.($pastproject->prototypes_count ? ' and its '.$pastproject->prototypes_count.' '.Str::plural('prototype', $pastproject->prototypes_count) : '').'?'" :fields="['id' => $pastproject->id]" />
                            </div>

                            <div class="col-span-1 flex justify-center items-center">
                            <a href="/editproject?id={{ $pastproject->id }}">
                               <svg xmlns="http://www.w3.org/2000/svg" fill="none"  class="h-6 w-6 text-blue-500" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                </svg>
                            </a>
                            </div>
                            </div>
                    </div>
                </div>
         @endforeach 

   </div>
</div>
</body>
</html>