
<!DOCTYPE html>
<html >
    <head>
        <title>add-team</title>
        <link rel="stylesheet" href="https://unpkg.com/tailwindcss@2.1.4/dist/tailwind.min.css">
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
                  Register New Team Member
              </h1>
              <form class="space-y-4 md:space-y-6" action="/insert_team" method="post" id="form" enctype="multipart/form-data">

               @csrf
                
                {{-- capturing the admin who registers this user --}}
              
               <input type="hidden" name="admin" value="{{ Session('id') }} "> 

                  <div>
                      <label for="item name" class="block mb-2 text-sm font-medium text-gray-900 light:text-white">First Name</label>
                      <input type="text" name="firstname" id="fname" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 light:bg-gray-700 light:border-gray-600 light:placeholder-gray-400 light:text-white light:focus:ring-green-500 light:focus:border-green-500" placeholder="first name" required="true">
                  </div>

                  <div>
                      <label for="lname" class="block mb-2 text-sm font-medium text-gray-900 light:text-white">Last Name</label>
                      <input type="text" name="lastname" id="cost" placeholder="Last Name" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 light:bg-gray-700 light:border-gray-600 light:placeholder-gray-400 light:text-white light:focus:ring-green-500 light:focus:border-green-500" required="true">
                  </div>

                  <div>
                      <label for="email" class="block mb-2 text-sm font-medium text-gray-900 light:text-white">Email Address</label>
                      <input type="email" name="email" id="email" placeholder="email address" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 light:bg-gray-700 light:border-gray-600 light:placeholder-gray-400 light:text-white light:focus:ring-green-500 light:focus:border-green-500" required="true">
                  </div>

                  <div>
                      <label for="phone" class="block mb-2 text-sm font-medium text-gray-900 light:text-white">Tel Number</label>
                      <input type="phone" name="phone" id="phone" placeholder="phone number" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 light:bg-gray-700 light:border-gray-600 light:placeholder-gray-400 light:text-white light:focus:ring-green-500 light:focus:border-green-500" required="true">
                  </div>

                 <div>
                      <label for="lname" class="block mb-2 text-sm font-medium text-gray-900 light:text-white">sector / Work field</label>
                      <input type="text" name="designation" id="cost" placeholder="Designation" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 light:bg-gray-700 light:border-gray-600 light:placeholder-gray-400 light:text-white light:focus:ring-green-500 light:focus:border-green-500" required="true">
                  </div>

                  <div>

                <label for="lname" class="block mb-2 text-sm font-medium text-gray-900 light:text-white">Role</label>
                <div class="flex items-center">
                    <input type="radio" value="leader" name="role" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2">
                    <label for="default-radio-1" class="ml-2 text-sm font-medium text-gray-900">Team Leader</label>
                </div>

                <div class="flex items-center">
                    <input type="radio" value="phd_stdt" name="role" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2">
                    <label for="default-radio-2" class="ml-2 text-sm font-medium text-gray-900">PhD Student</label>
                </div>
                
                <div class="flex items-center">
                    <input type="radio" value="researcher" name="role" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2">
                    <label for="default-radio-2" class="ml-2 text-sm font-medium text-gray-900">Researcher</label>
                </div> 

                <div class="flex items-center">
                    <input type="radio" value="masters_stdt" name="role" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2">
                    <label for="default-radio-2" class="ml-2 text-sm font-medium text-gray-900">Masters Student</label>
                </div> 
            
                <div class="flex items-center">
                    <input type="radio" value="admin" name="role" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2">
                    <label for="default-radio-2" class="ml-2 text-sm font-medium text-gray-900">Admin or Employee</label>
                </div> 

                <div class="flex items-center">
                    <input type="radio" value="intern" name="role" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2">
                    <label for="default-radio-2" class="ml-2 text-sm font-medium text-gray-900">Intern</label>
                </div> 

                  </div>

                 <div>
                     <label for="username" class="block mb-2 text-sm font-medium text-gray-900 light:text-white">Picture</label>
                      <input type="file" name="image" placeholder="" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 light:bg-gray-700 light:border-gray-600 light:placeholder-gray-400 light:text-white light:focus:ring-green-500 light:focus:border-green-500" required="true">
                  </div>

                   <div>
                    <label for="description" class="block mb-2 text-sm font-medium text-gray-900">Bio</label>
                    <textarea name="bio"  placeholder="Write the bio content here..." rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500"></textarea>
                  </div>

                  <button class="bg-green-500 text-white font-bold py-2 px-4 rounded-full w-full">
                              Register
                  </button>
                  <p class="text-sm font-light text-gray-500 light:text-gray-400">
                   Back to <a href="#top" class="font-medium text-primary-600 hover:underline light:text-primary-500">Top</a>
                  </p>
              </form>
          </div>
    </div>   
  
   </div> 
</div>

<script src="https://unpkg.com/flowbite@1.4.7/dist/flowbite.js"></script>
</body>
</html>