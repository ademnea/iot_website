
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>projects - IoT</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
</head>

<body>


@include('sidebar')

   <div class="p-4 sm:ml-64">
   <div class="p-4 border-2 border-gray-200 border rounded-lg light:border-gray-700">

<center>

      {{-- displaying an alert after registering a category --}}
         @if (session('success'))
            <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
            <b> {{ session('success') }}</b>
            </div> 

        @elseif (session('updated')) 
             <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
            <b> {{ session('updated') }}</b>
              </div>
            @endif
{{-- start of the page content --}}

<button type="button" id="top" class="text-white bg-green-700 hover:bg-green-800 focus:outline-none focus:ring-4 focus:ring-green-300 font-medium rounded-full text-sm px-5 py-2.5 text-center mr-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
<a href="/register_projects">Add New Project</a>
</button>

</center>
<br>
<div style="
margin-left:10%;
margin-right:10%;
">
<h1 class="font-medium">
   Projects Content 
</h1>
<hr>
<br>
<br>
<div class="container-xxl py-5">
    <div class="container">


            <h1 class="font-medium"> Ongoing projects</h1>
    <br>
                <div class="bg-white rounded-lg overflow-hidden shadow-md" style="width:50%; height:30%">
                    <div class="overflow-hidden">
                        <img class="" src="img/package-1.jpg" alt="">
                    </div>
                    <div class="flex border-b border-gray-200">
                        <small class="flex-1 text-center py-2 border-r border-gray-200"><i class="fas fa-map-marker-alt text-success me-2"></i>Makerere University IOT lab</small>
                        <small class="flex-1 text-center py-2 border-r border-gray-200"><i class="far fa-calendar-alt text-success me-2"></i>3 weeks</small>
                        <small class="flex-1 text-center py-2"><i class="fas fa-user text-success me-2"></i>6 people</small>
                    </div>
                    <div class="p-4">
                        <p>Anything about the projects</p>
                        <div class="flex justify-center">
                            <a href="#" class="bg-success text-white text-sm py-2 px-4 rounded-full inline-block">Read More</a>
                        </div>
                    </div>
                </div>

   <br><br>
            <h1 class="font-medium"> Past projects</h1>
            <br>
                <div class="bg-white rounded-lg overflow-hidden shadow-md" style="width:50%; height:30%">
                    <div class="overflow-hidden">
                        <img class="" src="img/package-1.jpg" alt="">
                    </div>
                    <div class="flex border-b border-gray-200">
                        <small class="flex-1 text-center py-2 border-r border-gray-200"><i class="fas fa-map-marker-alt text-success me-2"></i>Makerere University IOT lab</small>
                        <small class="flex-1 text-center py-2 border-r border-gray-200"><i class="far fa-calendar-alt text-success me-2"></i>3 weeks</small>
                        <small class="flex-1 text-center py-2"><i class="fas fa-user text-success me-2"></i>6 people</small>
                    </div>
                    <div class="p-4">
                        <p>Anything about the projects</p>
                        <div class="flex justify-center">
                            <a href="#" class="bg-success text-white text-sm py-2 px-4 rounded-full inline-block">Read More</a>
                        </div>
                    </div>
                </div>
        

   </div>
</div>
</body>
</html>