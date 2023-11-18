
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>IoT | old team </title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
</head>

<body>

@include('sidebar')
   <div class="p-4 sm:ml-64">
   <div class="p-4 border-2 border-gray-200 border rounded-lg light:border-gray-700">


    <center>
        {{-- displaying an alert  --}}
        @if (session('success'))
            <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50" role="alert">
            <b> {{ session('success') }}</b>
            </div> 
        @endif
    </center>

<br>
<div style="
margin-left:5%;
margin-right:5%;
">
<h3 class="font-medium">
   meet people that served the lab and made impact. you can view all about them in the gallery. 
</h3>
<hr>
<br>
<br>

<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <table class="w-full text-sm text-left text-gray-500">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
            <tr>
                <th scope="col" class="px-6 py-3">
                   Full Name
                </th>
                {{-- <th scope="col" class="px-6 py-3">
                   Role
                </th> --}}
                <th scope="col" class="px-6 py-3">
                   Designation
                </th>
                <th scope="col" class="px-6 py-3">
                    Email
                </th>
                <th scope="col" class="px-6 py-3">
                    Phone
                </th>
                <th scope="col" class="px-6 py-3">
                    Actions
                </th>

            </tr>
        </thead>
        <tbody>
@if (isset($users))

        @foreach ($users as $user )

            <tr class="border-b bg-gray-50">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                    {{ $user->fname }}  {{ $user->lname }}
                </th>
                {{-- <td class="px-6 py-4">
                    {{ $user->role }}
                </td> --}}
                <td class="px-6 py-4">
                   {{ $user->designation }}
                </td>
                <td class="px-6 py-4">
                    {{ $user->email }}
                </td>
                <td class="px-6 py-4">
                    {{ $user->phone }}
                </td>
                <td class="px-6 py-4">
                    <a href="/editteam? id={{  $user->id }}&firstname={{ $user->fname }}&lastname={{ $user->lname}}&email={{ $user->email}}&phone={{ $user->phone}}&role={{ $user->role }}&designation={{ $user->designation }}&bio={{ $user->bio }}">
                    <div class="font-medium text-green-600 hover:underline">
                    Edit
                    </div>
                    </a>
                </td>
                <td class="px-6 py-4">

                    <a href="#" type="button" data-modal-target="{{ $user->id }}" data-modal-show="{{ $user->id }}">
                                            {{-- svg icon --}}
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" class="h-6 w-6 text-orange-500" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                      </svg>
                      
                    </a>

                </td>
            </tr>
                     
        @endforeach

        @foreach ($users as $user)
      
                        <!-- Delete user pop up modal-->
    <div id="{{ $user->id }}" tabindex="-1" class="fixed top-0 left-0 right-0 z-50 hidden p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
               
                <button type="button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-800 dark:hover:text-white" data-modal-hide="{{ $user->id}}">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    <span class="sr-only">Close modal</span>
                </button>
                <div class="p-6 text-center">
                    <svg aria-hidden="true" class="mx-auto mb-4 text-gray-400 w-14 h-14 dark:text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Are you sure you want to recover this team member?</h3>
                    <!-- added id to the button Yes, I'm sure-->
                    <form id="delete-user-form-{{ $user->id }}" method="POST" action="/restoreuser?user_id={{$user->id}}" accept-charset="UTF-8" style="display:inline">
                        {{ csrf_field() }}
                        <button id="delete-user-btn" data-modal-hide="{{ $user->id }}"  type="submit" class="text-white bg-green-600 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 dark:focus:ring-green-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">
                            Yes, I'm sure
                        </button>
                    </form>
                    <button id="cancel-button" data-modal-hide="{{ $user->id }}" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                        No, cancel
                    </button>
                </div>
            </div>
        </div>
    </div> 
            
        @endforeach
@endif
        
        </tbody>
    </table>
</div>

   </div>
</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.1.1/flowbite.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>