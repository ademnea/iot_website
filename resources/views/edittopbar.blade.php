
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Edit social links · {{ config('app.name') }}</title>
        <link rel="stylesheet" href="https://unpkg.com/tailwindcss@2.1.4/dist/tailwind.min.css">
        @include('partials.admin-theme')

    </head>
    <body>
    <br><br><br><br>
        <div class="flex items justify-center">
           <div class="w-full bg-white rounded-lg shadow light:border md:mt-0 sm:max-w-md xl:p-0 light:bg-gray-800 light:border-gray-700">
          <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
              <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl light:text-white">
                  Edit Lab Social Links
              </h1>
           <form class="space-y-4 md:space-y-6" action="/edittopbar_route" method="post" id="form" enctype="multipart/form-data">

               @csrf
               
               <input type="hidden" value="{{ $form['id']}}" name="id" >

                <label for="field-twitter">X (Twitter):</label><br/>
                <input type="text" id="field-twitter" value="{{ $form['twitter'] }}" name="twitter" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 light:bg-gray-700 light:border-gray-600 light:placeholder-gray-400 light:text-white light:focus:ring-brand-500 light:focus:border-brand-500">
                <label class="flex items-center gap-2 mt-2 mb-4 text-sm text-gray-700">
                    <input type="checkbox" name="twitter_visible" value="1" @checked($form['twitter_visible']) class="w-4 h-4 rounded border-gray-300">
                    Show on the website <span class="text-gray-500">(only when there's a value)</span>
                </label>

                <label for="field-facebook">Facebook:</label><br/>
                <input type="text" id="field-facebook" value="{{ $form['facebook'] }}" name="facebook" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 light:bg-gray-700 light:border-gray-600 light:placeholder-gray-400 light:text-white light:focus:ring-brand-500 light:focus:border-brand-500">
                <label class="flex items-center gap-2 mt-2 mb-4 text-sm text-gray-700">
                    <input type="checkbox" name="facebook_visible" value="1" @checked($form['facebook_visible']) class="w-4 h-4 rounded border-gray-300">
                    Show on the website <span class="text-gray-500">(only when there's a value)</span>
                </label>
                
                <label for="field-instagram">Instagram:</label><br/>
                <input type="text" id="field-instagram" value="{{ $form['instagram'] }}" name="instagram" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 light:bg-gray-700 light:border-gray-600 light:placeholder-gray-400 light:text-white light:focus:ring-brand-500 light:focus:border-brand-500">
                <label class="flex items-center gap-2 mt-2 mb-4 text-sm text-gray-700">
                    <input type="checkbox" name="instagram_visible" value="1" @checked($form['instagram_visible']) class="w-4 h-4 rounded border-gray-300">
                    Show on the website <span class="text-gray-500">(only when there's a value)</span>
                </label>
                
                <label for="field-youtube">Youtube:</label><br/>
                <input type="text" id="field-youtube" value="{{ $form['youtube'] }}" name="youtube" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 light:bg-gray-700 light:border-gray-600 light:placeholder-gray-400 light:text-white light:focus:ring-brand-500 light:focus:border-brand-500">
                <label class="flex items-center gap-2 mt-2 mb-4 text-sm text-gray-700">
                    <input type="checkbox" name="youtube_visible" value="1" @checked($form['youtube_visible']) class="w-4 h-4 rounded border-gray-300">
                    Show on the website <span class="text-gray-500">(only when there's a value)</span>
                </label>

                <label for="field-linkedin">LinkedIn:</label><br/>
                <input type="text" id="field-linkedin" value="{{ $form['linkedin'] }}" name="linkedIn" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 light:bg-gray-700 light:border-gray-600 light:placeholder-gray-400 light:text-white light:focus:ring-brand-500 light:focus:border-brand-500">
                <label class="flex items-center gap-2 mt-2 mb-4 text-sm text-gray-700">
                    <input type="checkbox" name="linkedin_visible" value="1" @checked($form['linkedin_visible']) class="w-4 h-4 rounded border-gray-300">
                    Show on the website <span class="text-gray-500">(only when there's a value)</span>
                </label>
                 
                  <div class="grid grid-cols-2 gap-4">
                  
                          <div class="flex items-center h-5">
                            <a href="homecontent" class="bg-red-500 text-white font-bold py-2 px-4 rounded-full w-full">
                              Cancel
                            </a>   
                          </div>

                          <div class="flex items-center h-5">
                            <button class="bg-brand-500 text-white font-bold py-2 px-4 rounded-full w-full">
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