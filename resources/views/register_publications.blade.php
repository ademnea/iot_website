
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <title>add publications content</title>
        <link rel="stylesheet" href="https://unpkg.com/tailwindcss@2.1.4/dist/tailwind.min.css">
        <link rel="stylesheet" type="text/css" href="{{ asset('styles/home.css') }}">
        <script src="https://cdn.tailwindcss.com"></script>

        <link href="//cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" rel="stylesheet">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
       <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>

    </head>
    <body>
   @include('sidebar')

<div class="p-4 sm:ml-64">
   <div class="p-4 border-2 border-gray-200 border rounded-lg light:border-gray-700">

<div style="
margin-left:10%;
margin-right:10%;
">
   <div class="relative overflow-x-auto">
          <div class="p-6 space-y-4 md:space-y-6 sm:p-8">

              <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl light:text-white">
                  Add New Publications Content
              </h1>

              
 
<br><hr>
              <h3 class="font-medium">
                  Add New Technical Reports and Datasets
              </h3>

              <h3 class="" style="margin-left:60%;">
              
                        <p class="text-gray-500">Add New Prototypes instead?<a href="registerprototype" class="inline-flex items-center font-medium text-blue-600 hover:underline">
                        go
                        <svg aria-hidden="true" class="w-5 h-5 ml-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                        </a></p>

              </h3>
              <form class="space-y-4 md:space-y-6" action="/insert_publication" method="post" id="form" enctype="multipart/form-data">

               @csrf


              <input type="hidden" name="admin" value="{{ Session::get('id') }}">
              
                <div class="flex items-center mb-4">
                    <input type="radio" value="report" name="type" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2">
                    <label for="default-radio-1" class="ml-2 text-sm font-medium text-gray-900">Technical Report</label>
                </div>
                <div class="flex items-center">
                    <input type="radio" value="dataset" name="type" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2">
                    <label for="default-radio-2" class="ml-2 text-sm font-medium text-gray-900">Dataset</label>
                </div>
                
                 <div>
                     <label for="username" class="block mb-2 text-sm font-medium text-gray-900 light:text-white">Title</label>
                      <input type="text" name="title" placeholder="title" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 light:bg-gray-700 light:border-gray-600 light:placeholder-gray-400 light:text-white light:focus:ring-green-500 light:focus:border-green-500" required="true">
                  </div>

                  <div>
                     <label for="username" class="block mb-2 text-sm font-medium text-gray-900 light:text-white">Author</label>
                      <input type="text" name="author" placeholder="full name" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 light:bg-gray-700 light:border-gray-600 light:placeholder-gray-400 light:text-white light:focus:ring-green-500 light:focus:border-green-500" required="true">
                  </div>

                   <div>
                    <label for="description" class="block mb-2 text-sm font-medium text-gray-900">Description</label>
                    <textarea name="description"  placeholder="Write your Description here..." rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500"></textarea>
                  </div>

                  <div>
                     <label for="username" class="block mb-2 text-sm font-medium text-gray-900 light:text-white">Attach File</label>
                      <input type="file" name="file" placeholder="" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 light:bg-gray-700 light:border-gray-600 light:placeholder-gray-400 light:text-white light:focus:ring-green-500 light:focus:border-green-500" required="true">
                  </div>
    
                  <button class="bg-green-500 text-white font-bold py-2 px-4 rounded-full w-full">
                              Submit
                  </button>
              </form>

    </div>
    </div>
  
   </div> 
</div>

<script src="https://unpkg.com/flowbite@1.4.7/dist/flowbite.js"></script>
</body>
</html>