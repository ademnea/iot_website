
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>IoT - Home</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <link rel="stylesheet" href="https://unpkg.com/tailwindcss@2.1.4/dist/tailwind.min.css">
        <script src="https://cdn.tailwindcss.com"></script>
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

        @elseif (session('error')) 
             <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
            <b> {{ session('error') }}</b>
              </div>
        @endif
{{-- start of the page content --}}

<button type="button" id="top" class="text-white bg-green-700 hover:bg-green-800 focus:outline-none focus:ring-4 focus:ring-green-300 font-medium rounded-full text-sm px-5 py-2.5 text-center mr-2 mb-2">
<a href="/register_home">Add New Home Content</a>
</button>

</center>
<br>
<div style="
margin-left:10%;
margin-right:10%;
">
<h1 class="font-medium">
   Home Content 
</h1>
<hr>
<br>
<br>


 
@foreach ($contents as $content)
    
{{-- Beginning of the company Logo. --}}

 <div class="grid grid-cols-2 gap-4">
            <div class="col-span-1 flex justify-center items-center">
      <img src="../images/logo/{{ $content->logo }}" alt="logo" >
       <div style="margin-left:15%;"> <h4 class="text-lg font-medium mt-4">Logo</h4> </div><br>
            </div>

            <div class="col-span-1 flex justify-center items-center">
      <div class="grid grid-cols-2 gap-4">
            <div class="col-span-1 flex justify-center items-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" class="h-6 w-6 text-red-500" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                </svg>
            </div>
            <div class="col-span-1 flex justify-center items-center">
                         {{-- action to edit, rewriting this url --}}
              <a href="/editlogo? id={{ $content->id }}">
                 <svg xmlns="http://www.w3.org/2000/svg" fill="none"  class="h-6 w-6 text-blue-500" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                 <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                </svg>
            </a>
            </div>
            </div>
            <br>
            </div>
            </div>
 <br><hr>

   {{-- banner beginning --}}

   <div class="grid grid-cols-2 gap-4">
    <div class="col-span-1 flex justify-center items-center">
<img src="../images/banner/{{ $content->banner }}" alt="logo">
<div style="margin-left:15%;"> <h4 class="text-lg font-medium mt-4">Banner</h4> </div><br>
    </div>

    <div class="col-span-1 flex justify-center items-center">
<div class="grid grid-cols-2 gap-4">
    <div class="col-span-1 flex justify-center items-center">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" class="h-6 w-6 text-red-500" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
        </svg>
    </div>
    <div class="col-span-1 flex justify-center items-center">
                 {{-- action to edit, rewriting this url --}}
      <a href="/editbanner? id={{ $content->id }}">
         <svg xmlns="http://www.w3.org/2000/svg" fill="none"  class="h-6 w-6 text-blue-500" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
         <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
        </svg>
    </a>
    </div>
    </div>
    <br>
    </div>
    </div>
<br><hr>

            
  {{-- beginning of topbar information. --}}
<h6 class="font-medium">Topbar Information</h6>

         <div class="grid grid-cols-2 gap-4">
            <div class="col-span-1 flex justify-center items-center">
<ol class="max-w-md space-y-1 text-gray-500 list-decimal list-inside">
    <li>
        <span class="font-semibold text-gray-900">Twitter:</span> {{ $content->twitter }}
    </li>
    <li>
        <span class="font-semibold text-gray-900">Facebook:</span> {{ $content->facebook }}
    </li>
    <li>
        <span class="font-semibold text-gray-900">LinkedIn:</span> {{ $content->linkedin }}
    <li>
        <span class="font-semibold text-gray-900">Instagram:</span> {{ $content->instagram }} 
    </li>
    <li>
        <span class="font-semibold text-gray-900">Youtube:</span> {{ $content->youtube }}
    </li>
   </ol>
            </div>
  {{-- editing action for the social links --}}
            <div class="col-span-1 flex justify-center items-center">
            <a href="/edittopbar? id={{  $content->id }}&facebook={{ $content->facebook }}&twitter={{ $content->twitter }}&linkedin={{ $content->linkedin }}&instagram={{ $content->instagram }}&youtube={{ $content->youtube }}">
                 <svg xmlns="http://www.w3.org/2000/svg" fill="none"  class="h-6 w-6 text-blue-500" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                 <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                </svg>
            </a>
            </div>
            </div>

<br>
<h2 class="mb-2 text-lg font-semibold text-gray-900">Lab contacts and Information:</h2>

   <div class="grid grid-cols-2 gap-4">

    <div class="col-span-1 flex justify-center items-center">
<ol class="max-w-md space-y-1 text-gray-500 list-decimal list-inside">
    <li>
        <span class="font-semibold text-gray-900">Email</span> {{ $content->company_email }}
    </li>
    <li>
        <span class="font-semibold text-gray-900">Telephone</span> {{ $content->company_telephone }}
    </li>
    <li>
        <span class="font-semibold text-gray-900">Physical Address</span> {{ $content->physical_address }}
    </li>
</ol>
    </div>
  {{-- editing action for the social links --}}
            <div class="col-span-1 flex justify-center items-center">
            <a href="/editcontacts? id={{ $content->id }}&email={{ $content->company_email }}&phone={{ $content->company_telephone }}&address={{ $content->physical_address }}">
                 <svg xmlns="http://www.w3.org/2000/svg" fill="none"  class="h-6 w-6 text-blue-500" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                 <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                </svg>
            </a>
            </div>
            </div>

<br>
{{-- mission statement start --}}
    <div class ="container" id="mission">
      <h6 class="font-medium">Home content</h6>
      <div class="wow fadeInUp" data-wow-delay="0.1s">
       <p> <br>

        <h1 class="font-medium">Lab Name</h1>
        {{ $content->Labname}}  
       </p> <br>
       <hr>

            <div class="grid grid-cols-2 gap-4">
            <div class="col-span-1 flex justify-center items-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" class="h-6 w-6 text-red-500" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                </svg>
            </div>

            <div class="col-span-1 flex justify-center items-center">
            <a href="/edithome? id={{  $content->id }}&vision={{ $content->vision }}&labname={{ $content->Labname }}&motto={{ $content->motto }}&objectives={{ $content->objectives }}&mission={{ $content->mission }}">
                 <svg xmlns="http://www.w3.org/2000/svg" fill="none"  class="h-6 w-6 text-blue-500" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                 <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                </svg>
            </a>
            </div>
            </div>
       <br>

        <h1 class="font-medium">Vision</h1>
        {{ $content->vision}}  
       </p> <br>
       <hr>

            <div class="grid grid-cols-2 gap-4">
            <div class="col-span-1 flex justify-center items-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" class="h-6 w-6 text-red-500" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                </svg>
            </div>

            <div class="col-span-1 flex justify-center items-center">
            <a href="/edithome? id={{  $content->id }}&vision={{ $content->vision }}&labname={{ $content->Labname }}&motto={{ $content->motto }}&objectives={{ $content->objectives }}&mission={{ $content->mission }}">
                 <svg xmlns="http://www.w3.org/2000/svg" fill="none"  class="h-6 w-6 text-blue-500" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                 <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                </svg>
            </a>
            </div>
            </div>
       <br>

   <h1 class="font-medium">Motto</h1>
        {{ $content->motto}}  
       </p> <br>
       <hr>

            <div class="grid grid-cols-2 gap-4">
            <div class="col-span-1 flex justify-center items-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" class="h-6 w-6 text-red-500" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                </svg>
            </div>

            <div class="col-span-1 flex justify-center items-center">
            <a href="/edithome? id={{  $content->id }}&vision={{ $content->vision }}&labname={{ $content->Labname }}&motto={{ $content->motto }}&objectives={{ $content->objectives }}&mission={{ $content->mission }}">
                 <svg xmlns="http://www.w3.org/2000/svg" fill="none"  class="h-6 w-6 text-blue-500" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                 <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                </svg>
            </a>
            </div>
            </div>
       <br>


       <h1 class="font-medium">Mission</h1>
        {{ $content->mission }}  
       </p><br>
    <hr>
            <div class="grid grid-cols-2 gap-4">
            <div class="col-span-1 flex justify-center items-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" class="h-6 w-6 text-red-500" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                </svg>
            </div>

            <div class="col-span-1 flex justify-center items-center">
            <a href="/edithome? id={{  $content->id }}&motto={{ $content->motto }}&labname={{ $content->Labname }}&objectives={{ $content->objectives }}&mission={{ $content->mission }}&vision={{ $content->vision }}">
                 <svg xmlns="http://www.w3.org/2000/svg" fill="none"  class="h-6 w-6 text-blue-500" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                 <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                </svg>
            </a>
            </div>
            </div>
     <br><br>
     <p>
    <h1 class="font-medium">Objectives</h1>
        {{ $content->objectives }}  
       </p> <br>
       <hr>

            <div class="grid grid-cols-2 gap-4">
            <div class="col-span-1 flex justify-center items-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" class="h-6 w-6 text-red-500" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                </svg>
            </div>

            <div class="col-span-1 flex justify-center items-center">
            <a href="/edithome? id={{  $content->id }}&motto={{ $content->motto }}&labname={{ $content->Labname }}&objectives={{ $content->objectives }}&vision={{ $content->vision }}&mission={{ $content->mission }}">
                 <svg xmlns="http://www.w3.org/2000/svg" fill="none"  class="h-6 w-6 text-blue-500" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                 <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                </svg>
            </a>
            </div>
            </div>

        </div>
{{--  start of the word from the team leader --}}

 @if ($leader->isNotEmpty())
<br>
     <h1 class="font-medium">Word from the team leader</h1>

    <div class="flex flex-row items-center justify-center">
    <div class="w-1/3"> <br><br>

 @foreach ($leader as $leader)

        <img src="../images/team/{{ $leader->photo}}"  class="h-48 w-48 rounded-full">
       <div style="margin-left:15%;"> <h4 class="text-lg font-medium mt-4">Dr {{ $leader->fname}} {{ $leader->lname }}</h4> </div>
    </div>
    <div class="w-2/3">
        <p class="mt-4">
       {{ $content->team_leader_word }}
        </p><br>
        <hr>
   @endforeach

    @else
        <img src="../img/team-4.jpg"  class="h-48 w-48 rounded-full">
       <div style="margin-left:15%;"> <h4 class="text-lg font-medium mt-4">Dr John Doe</h4></div>
    </div>
    <div class="w-2/3">
        <p class="mt-4">
       here are the words from the team leader. these are default before you enter your own.
        </p><br>
        <hr>
     @endif
            <div class="grid grid-cols-2 gap-4">
            <div class="col-span-1 flex justify-center items-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" class="h-6 w-6 text-red-500" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                </svg>
            </div>

            <div class="col-span-1 flex justify-center items-center">
                         {{-- action to edit, rewriting this url --}}
              <a href="/editleader? id={{  $content->id }}&word={{ $content->team_leader_word }}&photo={{ $content->photo }}">
                 <svg xmlns="http://www.w3.org/2000/svg" fill="none"  class="h-6 w-6 text-blue-500" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                 <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                </svg>
            </a>
            </div>
            </div>
    </div>
    </div>
<hr>
<br>

@endforeach

@foreach ($partners as $partner)

    <!-- Partners Start -->
    <div class="container-xxl py-5 wow fadeInUp" data-wow-delay="0.1s">
        <div class="container">
            <div class="text-center" id="partners">
                <h1 class="font-medium">Partners</h1>
                <br>

            <div class="grid grid-cols-2 gap-4">
            <div class="col-span-1 flex justify-center items-center">
                 <h1 class="mb-5">Our partners</h1>
            </div>


            <div class="col-span-1 flex justify-center items-center">
            <a href="register_home#partners">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" class="h-6 w-6 text-green-500" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z" />
              </svg>
            </a>
            </div>
            </div>

            </div>
 
            <div class="owl-carousel testimonial-carousel position-relative">
            <br>
                <div class="testimonial-item bg-white text-center border p-4">
                    <img class="bg-white rounded-circle shadow p-1 mx-auto mb-3" src="images/partners/{{ $partner->logo }}" style="width: 80px; height: 80px;">
                 
          {{-- Actions to edit the partner --}}
            <div class="grid grid-cols-2 gap-4">
        
            <div class="col-span-1 flex justify-center items-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" class="h-6 w-6 text-red-500" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                </svg>
            </div>

            <div class="col-span-1 flex justify-center items-center">

            {{-- action to edit, rewriting this url --}}
            <a href="/editpartner? id={{  $partner->id }}&name={{ $partner->name }}">
               <svg xmlns="http://www.w3.org/2000/svg" fill="none"  class="h-6 w-6 text-blue-500" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                </svg>
            </a>
            </div>
            </div>
                    <h5 class="mb-0">{{ $partner->name }}</h5>
                    {{-- <p>New York, USA</p> --}}
                    <p class="mb-0">
                    {{ $partner->description }}
                    </p>
                </div>
                <br>

         @endforeach
           {{-- end of partner --}}
</div>

   </div>
</div>

</body>
</html>