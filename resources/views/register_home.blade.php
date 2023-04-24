
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <title>add home content</title>
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
                  Add New Home Content
              </h1>
              <form class="space-y-4 md:space-y-6" action="/insertmission" method="post" id="form">

               @csrf


                <div class="flex items-center mb-4">
                    <input type="radio" value="mission" name="homecontent" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="default-radio-1" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Mission</label>
                </div>

                <div class="flex items-center">
                    <input type="radio" value="objectives" name="homecontent" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="default-radio-2" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Objectives</label>
                </div>

                <div class="flex items-center">
                    <input type="radio" value="team_leader_word" name="homecontent" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="default-radio-2" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Word From Team Leader</label>
                </div>

                  <div>
                    <label for="description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Content</label>
                    <textarea name="content"  placeholder="Write your content here..." rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"></textarea>
                  </div>
    
                  <button class="bg-green-500 text-white font-bold py-2 px-4 rounded-full w-full">
                              Submit
                  </button>
              </form>
          </div>


<br>
                <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl light:text-white">
                  Add New Addresses And Contact Information
               </h1>
            <form class="space-y-4 md:space-y-6" action="/insertcontacts" method="post" id="form" enctype="multipart/form-data">

               @csrf

                 <div>
                     <label for="username" class="block mb-2 text-sm font-medium text-gray-900 light:text-white">Company Logo</label>
                      <input type="file" name="logo" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 light:bg-gray-700 light:border-gray-600 light:placeholder-gray-400 light:text-white light:focus:ring-green-500 light:focus:border-green-500">
                  </div>
                
                  <div>
                     <label for="username" class="block mb-2 text-sm font-medium text-gray-900 light:text-white">Physical Address</label>
                      <input type="text" name="address" placeholder="physical Address" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 light:bg-gray-700 light:border-gray-600 light:placeholder-gray-400 light:text-white light:focus:ring-green-500 light:focus:border-green-500">
                  </div>

                    <div>
                     <label for="username" class="block mb-2 text-sm font-medium text-gray-900 light:text-white">Lab Telephone</label>
                      <input type="text" name="telephone" placeholder="telephone" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 light:bg-gray-700 light:border-gray-600 light:placeholder-gray-400 light:text-white light:focus:ring-green-500 light:focus:border-green-500">
                  </div>

                    <div>
                     <label for="username" class="block mb-2 text-sm font-medium text-gray-900 light:text-white">Lab Email</label>
                      <input type="email" name="email" placeholder="email address" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 light:bg-gray-700 light:border-gray-600 light:placeholder-gray-400 light:text-white light:focus:ring-green-500 light:focus:border-green-500">
                  </div>

                    <div>
                     <label for="username" class="block mb-2 text-sm font-medium text-gray-900 light:text-white">Twitter link</label>
                      <input type="text" name="twitter" placeholder="twitter" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 light:bg-gray-700 light:border-gray-600 light:placeholder-gray-400 light:text-white light:focus:ring-green-500 light:focus:border-green-500">
                  </div>

                    <div>
                     <label for="username" class="block mb-2 text-sm font-medium text-gray-900 light:text-white">Facebook link</label>
                      <input type="text" name="facebook" placeholder="facebook" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 light:bg-gray-700 light:border-gray-600 light:placeholder-gray-400 light:text-white light:focus:ring-green-500 light:focus:border-green-500">
                  </div>

                    <div>
                     <label for="username" class="block mb-2 text-sm font-medium text-gray-900 light:text-white">LinkedIn Link</label>
                      <input type="text" name="linkedin" placeholder="linkedIn" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 light:bg-gray-700 light:border-gray-600 light:placeholder-gray-400 light:text-white light:focus:ring-green-500 light:focus:border-green-500">
                  </div>

                    <div>
                     <label for="username" class="block mb-2 text-sm font-medium text-gray-900 light:text-white">YouTube Link</label>
                      <input type="text" name="youtube" placeholder="youtube" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 light:bg-gray-700 light:border-gray-600 light:placeholder-gray-400 light:text-white light:focus:ring-green-500 light:focus:border-green-500">
                  </div>

                    <div>
                     <label for="username" class="block mb-2 text-sm font-medium text-gray-900 light:text-white">Instagram Link</label>
                      <input type="text" name="instagram" placeholder="instagram" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 light:bg-gray-700 light:border-gray-600 light:placeholder-gray-400 light:text-white light:focus:ring-green-500 light:focus:border-green-500">
                  </div>
                  <button class="bg-green-500 text-white font-bold py-2 px-4 rounded-full w-full">
                              Submit
                  </button>

          </form>

 <br>

              
        {{-- beginning of the second form --}}

<br><hr>
             <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl light:text-white" id="partners">
                Partners
              </h1>
              <form class="space-y-4 md:space-y-6" action="/insertpartner" method="post" id="form" enctype="multipart/form-data">

               @csrf
                
                  <div>
                     <label for="username" class="block mb-2 text-sm font-medium text-gray-900 light:text-white">Partner Name</label>
                      <input type="text" name="name" placeholder="full name" required class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 light:bg-gray-700 light:border-gray-600 light:placeholder-gray-400 light:text-white light:focus:ring-green-500 light:focus:border-green-500">
                  </div>

                  <div>
                     <label for="username" class="block mb-2 text-sm font-medium text-gray-900 light:text-white">Description</label>
                      <textarea name="description"  placeholder="Write description here..." rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"></textarea>
                  </div>

                  <div>
                     <label for="username" class="block mb-2 text-sm font-medium text-gray-900 light:text-white">Logo</label>
                      <input type="file" name="image" placeholder="" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 light:bg-gray-700 light:border-gray-600 light:placeholder-gray-400 light:text-white light:focus:ring-green-500 light:focus:border-green-500">
                  </div>

                  <div>
                     <label for="username" class="block mb-2 text-sm font-medium text-gray-900 light:text-white">Website</label>
                       <input type="text" name="website" placeholder="website link" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 light:bg-gray-700 light:border-gray-600 light:placeholder-gray-400 light:text-white light:focus:ring-green-500 light:focus:border-green-500">
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