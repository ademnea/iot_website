
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>News · {{ config('app.name') }}</title>
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
<a href="/register_news">New News Event</a>
</button>

</center>
<br>
<div class="sm:mx-[10%]">
<h1 class="font-medium">
   News Content 
</h1>
<hr>
<br>
<br>

<div class="container-xxl py-5">
    <div class="container">

    <h1 class="font-medium"> Upcoming Events</h1>
    <br>
        @foreach ($onevents as $onevent)

                <div class="bg-white rounded-lg overflow-hidden shadow-md w-full sm:w-1/2">
                    <div class="overflow-hidden">
                        <img class="" src="images/events/{{ $onevent->cover }}" alt="">
                    </div>
                    <div class="flex border-b border-gray-200">
                        <small class="flex-1 text-center py-2 border-r border-gray-200"><i class="fas fa-map-marker-alt text-brand-700 me-2"></i>
                        {{ $onevent->venue }}
                        </small>
                        <small class="flex-1 text-center py-2 border-r border-gray-200"><i class="far fa-calendar-alt text-brand-700 me-2"></i>
                        3 weeks
                        </small>
                        <small class="flex-1 text-center py-2"><i class="fas fa-user text-brand-700 me-2"></i>
                        6 people
                        </small>
                    </div>
                    <div class="p-4">
                       <h1>{{ $onevent->title }}</h1>
                        <p>{{ $onevent->description }}</p>
                         <br><hr>
                        
                         <div class="grid grid-cols-2 gap-4">
                            <div class="col-span-1 flex justify-center items-center">
                                <x-trash-button action="/deletenews" :confirm="'Delete “'.$onevent->title.'”?'" :fields="['id' => $onevent->id]" />
                            </div>

                            <div class="col-span-1 flex justify-center items-center">
                            <a href="/editnews?id={{ $onevent->id }}">
                               <svg xmlns="http://www.w3.org/2000/svg" fill="none"  class="h-6 w-6 text-blue-500" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                </svg>
                            </a>
                            </div>
                            </div>
                    </div>
                </div>

             </div>
          </div> 
              
        @endforeach


   <br><br>
            <h1 class="font-medium"> Past Events</h1>
            <br>

        @foreach ($pastevents as $pastevent)

                <div class="bg-white rounded-lg overflow-hidden shadow-md w-full sm:w-1/2">
                    <div class="overflow-hidden">
                        <img class="" src="images/events/{{ $pastevent->cover }}" alt="">
                    </div>
                    <div class="flex border-b border-gray-200">
                        <small class="flex-1 text-center py-2 border-r border-gray-200"><i class="fas fa-map-marker-alt text-brand-700 me-2"></i>
                        {{ $pastevent->venue }}
                        </small>
                        <small class="flex-1 text-center py-2 border-r border-gray-200"><i class="far fa-calendar-alt text-brand-700 me-2"></i>
                        auto generated
                        </small>
                        <small class="flex-1 text-center py-2"><i class="fas fa-user text-brand-700 me-2"></i>
                        more content
                        </small>
                    </div>
                    <div class="p-4">
                       <h1>{{ $pastevent->title }}</h1>
                        <p>{{ $pastevent->description }}</p>
                         <br><hr>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="col-span-1 flex justify-center items-center">
                                <x-trash-button action="/deletenews" :confirm="'Delete “'.$pastevent->title.'”?'" :fields="['id' => $pastevent->id]" />
                            </div>

                            <div class="col-span-1 flex justify-center items-center">
                            <a href="/editnews?id={{ $pastevent->id }}">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"  class="h-6 w-6 text-blue-500" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                </svg>
                            </a>
                            </div>
                            </div>
                    </div>
                </div>

             </div>
          </div> 
              
        @endforeach

   </div>
</div>

</body>
</html>