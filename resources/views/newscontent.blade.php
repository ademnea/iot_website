
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>IoT - news</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
</head>

<body>

@include('sidebar')

@include('sidebar')
   <div class="p-4 sm:ml-64">
   <div class="p-4 border-2 border-gray-200 border rounded-lg light:border-gray-700">

<center>

      {{-- displaying an alert after registering a category --}}
         @if (session('success'))
            <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50" role="alert">
            <b> {{ session('success') }}</b>
            </div> 

        @elseif (session('updated')) 
             <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50" role="alert">
            <b> {{ session('updated') }}</b>
              </div>
            @endif
{{-- start of the page content --}}

<button type="button" id="top" class="text-white bg-green-700 hover:bg-green-800 focus:outline-none focus:ring-4 focus:ring-green-300 font-medium rounded-full text-sm px-5 py-2.5 text-center mr-2 mb-2">
<a href="/register_news">New News Event</a>
</button>

</center>
<br>
<div style="
margin-left:10%;
margin-right:10%;
">
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

                <div class="bg-white rounded-lg overflow-hidden shadow-md" style="width:50%; height:30%">
                    <div class="overflow-hidden">
                        <img class="" src="images/events/{{ $onevent->photo }}" alt="">
                    </div>
                    <div class="flex border-b border-gray-200">
                        <small class="flex-1 text-center py-2 border-r border-gray-200"><i class="fas fa-map-marker-alt text-success me-2"></i>
                        {{ $onevent->venue }}
                        </small>
                        <small class="flex-1 text-center py-2 border-r border-gray-200"><i class="far fa-calendar-alt text-success me-2"></i>
                        3 weeks
                        </small>
                        <small class="flex-1 text-center py-2"><i class="fas fa-user text-success me-2"></i>
                        6 people
                        </small>
                    </div>
                    <div class="p-4">
                       <h1>{{ $onevent->title }}</h1>
                        <p>{{ $onevent->description }}</p>
                         <br><hr>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="col-span-1 flex justify-center items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" class="h-6 w-6 text-red-500" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                </svg>
                            </div>

                            <div class="col-span-1 flex justify-center items-center">
                            <a href="/editnews? id={{  $onevent->id }}&description={{ $onevent->description }}&venue={{ $onevent->venue }}&title={{ $onevent->title }}&startdate={{ $onevent->start_date }}&enddate={{ $onevent->end_date }}">
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

        @foreach ($pastevents as $pastvent)

                <div class="bg-white rounded-lg overflow-hidden shadow-md" style="width:50%; height:30%">
                    <div class="overflow-hidden">
                        <img class="" src="images/events/{{ $pastvent->photo }}" alt="">
                    </div>
                    <div class="flex border-b border-gray-200">
                        <small class="flex-1 text-center py-2 border-r border-gray-200"><i class="fas fa-map-marker-alt text-success me-2"></i>
                        {{ $pastvent->venue }}
                        </small>
                        <small class="flex-1 text-center py-2 border-r border-gray-200"><i class="far fa-calendar-alt text-success me-2"></i>
                        auto generated
                        </small>
                        <small class="flex-1 text-center py-2"><i class="fas fa-user text-success me-2"></i>
                        more content
                        </small>
                    </div>
                    <div class="p-4">
                       <h1>{{ $pastvent->title }}</h1>
                        <p>{{ $pastvent->description }}</p>
                         <br><hr>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="col-span-1 flex justify-center items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" class="h-6 w-6 text-red-500" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                </svg>
                            </div>

                            <div class="col-span-1 flex justify-center items-center">
                            <a href="/editnews? id={{  $pastevent->id }}&description={{ $pastevent->description }}&venue={{ $pastevent->venue }}&title={{ $pastevent->title }}&startdate={{ $pastevent->start_date }}&startdate={{ $pastevent->end_date }}">
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