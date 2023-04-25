
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <title>edit project content</title>
        <link rel="stylesheet" href="https://unpkg.com/tailwindcss@2.1.4/dist/tailwind.min.css">

    </head>
    <body>
    <br><br><br><br>
        <div class="flex items justify-center">
           <div class="w-full bg-white rounded-lg shadow light:border md:mt-0 sm:max-w-md xl:p-0 light:bg-gray-800 light:border-gray-700">
          <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
              <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl light:text-white">
                  Edit project Content
              </h1>
           <form class="space-y-4 md:space-y-6" action="/editmission" method="post" id="form">

               @csrf

                  <label for="projectName">Project Name:</label><br/>
                    <input type="text" value="{{ $_GET['name']}}" name="projectName" required class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 light:bg-gray-700 light:border-gray-600 light:placeholder-gray-400 light:text-white light:focus:ring-green-500 light:focus:border-green-500"><br>
                    
                    <label for="venue">Venue:</label><br/>
                    <input type="text" value="{{ $_GET['venue']}}" name="venue" required class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 light:bg-gray-700 light:border-gray-600 light:placeholder-gray-400 light:text-white light:focus:ring-green-500 light:focus:border-green-500"><br>
                    
                    <label for="duration">Duration:</label><br/>
                    <input type="text" value="{{ $_GET['duration']}}" name="duration" required class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 light:bg-gray-700 light:border-gray-600 light:placeholder-gray-400 light:text-white light:focus:ring-green-500 light:focus:border-green-500"><br>
                    
                    <label for="participants">Number of Participants:</label><br/>
                    <input type="number" value="{{ $_GET['participants']}}" name="participants" required class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 light:bg-gray-700 light:border-gray-600 light:placeholder-gray-400 light:text-white light:focus:ring-green-500 light:focus:border-green-500"><br>
                    
                    <label for="description">Description:</label><br/>
                    <textarea value="{{ $_GET['description']}}" name="description" rows="4" cols="50" required class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 light:bg-gray-700 light:border-gray-600 light:placeholder-gray-400 light:text-white light:focus:ring-green-500 light:focus:border-green-500"></textarea><br>
                    
                    <label for="image">Image:</label>
                    <input type="file" id="image" name="image" accept="" ><br><br/>

                  <div class="grid grid-cols-2 gap-4">
                  
                          <div class="flex items-center h-5">
                            <a href="projectscontent" class="bg-red-500 text-white font-bold py-2 px-4 rounded-full w-full">
                              Cancel
                            </a>   
                          </div>

                          <div class="flex items-center h-5">
                            <button class="bg-green-500 text-white font-bold py-2 px-4 rounded-full w-full">
                              Save Changes
                            </button>
                          </div>
                      </div>
              </form>

              </div>
              </div>
            </div>

<script src="https://unpkg.com/flowbite@1.4.7/dist/flowbite.js"></script>
</body>
</html>