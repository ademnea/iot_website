
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <title>Edit lab contacts · {{ config('app.name') }}</title>
        <link rel="stylesheet" href="https://unpkg.com/tailwindcss@2.1.4/dist/tailwind.min.css">
        @include('partials.admin-theme')

    </head>
    <body>
    <br><br><br><br>
        <div class="flex items justify-center">
           <div class="w-full bg-white rounded-lg shadow light:border md:mt-0 sm:max-w-md xl:p-0 light:bg-gray-800 light:border-gray-700">
          <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
              <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl light:text-white">
                  Edit Lab Contacts
              </h1>
           <form class="space-y-4 md:space-y-6" action="/editlabinfo_route" method="post" id="form" enctype="multipart/form-data">

               @csrf

              <input type="hidden" name="id" value="{{ $form['id']}}">

                <label for="field-address">Physical Address:</label><br/>
                <input type="text" id="field-address" value="{{ $form['address'] }}" name="address" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 light:bg-gray-700 light:border-gray-600 light:placeholder-gray-400 light:text-white light:focus:ring-brand-500 light:focus:border-brand-500">
                <label class="flex items-center gap-2 mt-2 mb-4 text-sm text-gray-700">
                    <input type="checkbox" name="address_visible" value="1" @checked($form['address_visible']) class="w-4 h-4 rounded border-gray-300">
                    Show on the website <span class="text-gray-500">(only when there's a value)</span>
                </label>

                <label for="field-email">Email Address:</label><br/>
                <input type="text" id="field-email" value="{{ $form['email'] }}" name="email" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 light:bg-gray-700 light:border-gray-600 light:placeholder-gray-400 light:text-white light:focus:ring-brand-500 light:focus:border-brand-500">
                <label class="flex items-center gap-2 mt-2 mb-4 text-sm text-gray-700">
                    <input type="checkbox" name="email_visible" value="1" @checked($form['email_visible']) class="w-4 h-4 rounded border-gray-300">
                    Show on the website <span class="text-gray-500">(only when there's a value)</span>
                </label>
                
                <label for="field-phone">Telephone:</label><br/>
                <input type="text" id="field-phone" value="{{ $form['phone'] }}" name="phone" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 light:bg-gray-700 light:border-gray-600 light:placeholder-gray-400 light:text-white light:focus:ring-brand-500 light:focus:border-brand-500">
                <label class="flex items-center gap-2 mt-2 mb-4 text-sm text-gray-700">
                    <input type="checkbox" name="phone_visible" value="1" @checked($form['phone_visible']) class="w-4 h-4 rounded border-gray-300">
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