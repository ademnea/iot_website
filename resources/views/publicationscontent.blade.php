
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>IoT - publications</title>
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
<a href="/register_publications">Add New Publication</a>
</button>

</center>
<br>
<div style="
margin-left:10%;
margin-right:10%;
">
<h1 class="font-medium">
  Publications Content 
</h1>
<hr>
<br>
<div class ="container">
 <h1 class="font-medium">Prototypes</h1>
<br>
  <div class="flex flex-wrap">
  
  @foreach ($prototypes as $prototype)
      
<div class="max-w-sm bg-white border border-gray-200 rounded-lg shadow">
    <a href="#">
        <img class="rounded-t-lg" src="images/prototypes/{{ $prototype->photo }}" alt="" />
    </a>
    <div class="p-5">
        <a href="#">
            <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900">
           {{ $prototype->name }}
            </h5>
        </a>
        <p class="mb-3 font-normal text-gray-700">
        {{ $prototype->description }}
        </p>


            <div class="grid grid-cols-2 gap-4">
        <a href="#" class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-green-700 rounded-lg hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300">
            Read more
        <svg aria-hidden="true" class="w-4 h-4 ml-2 -mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
          <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd">
          </path>
        </svg>
        </a>
   <div class="col-span-1 flex justify-center items-center">
         <a href="/editprototype? id={{  $prototype->id }}&name={{ $prototype->name }}&description={{ $prototype->description }} ">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none"  class="h-6 w-6 text-blue-500" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
             <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
            </svg>
         </a>
    </div>
                            
    </div>
</div>

    </div>
  </div>

    @endforeach



  <div class="w-full md:w-1/3 p-2">
    <div class="bg-white rounded-lg shadow-lg">
      <!-- Card content goes here -->
    </div>
  </div>

  <div class="w-full md:w-1/3 p-2">
    <div class="bg-white rounded-lg shadow-lg">
      <!-- Card content goes here -->
    </div>
  </div>
</div>


 

<br><br>
 <h1 class="font-medium">Data sets and Technical Reports</h1>
<br>
<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <table class="w-full text-sm text-left text-gray-500">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
      
            <tr>
                <th scope="col" class="px-6 py-3">
                    Title
                </th>
                <th scope="col" class="px-6 py-3">
                    Type
                </th>
                <th scope="col" class="px-6 py-3">
                    Author
                </th>
                <th scope="col" class="px-6 py-3">
                    File Path
                </th>
                <th scope="col" class="px-6 py-3">
                    Actions
                </th>
                <th scope="col" class="px-6 py-3">
                </th>
            </tr>
        </thead>
        <tbody>

        @foreach ($publications as $pub)

            <tr class="bg-white border-b">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                   {{ $pub->title}}
                </th>
                <td class="px-6 py-4">
                    {{ $pub->type }}
                </td>
                <td class="px-6 py-4">
                    {{ $pub->author }}
                </td>
                <td class="px-6 py-4">
                   documents/{{ $pub->file_link }}
                </td>
                <td class="px-6 py-4">
                    <a href="/editpublications? id={{  $pub->id }}&title={{ $pub->title }}&type={{ $pub->type }}&author={{ $pub->author }}&description={{ $pub->description }}" class="font-medium text-blue-600 hover:underline">Edit</a>
                </td>
                <td class="px-6 py-4">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" class="h-6 w-6 text-red-500" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                    </svg>
                </td>
            </tr>
              @endforeach

        </tbody>
    </table>
</div>

   </div>
</div>

</body>
</html>