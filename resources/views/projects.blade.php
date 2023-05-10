 <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>projects - IoT</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Nunito:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/tailwindcss@2.1.4/dist/tailwind.min.css">
    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">

    <style>

        /*** Hero Header ***/
.hero-header {
    background: linear-gradient(rgba(20, 20, 31, .7), rgba(20, 20, 31, .7)), url(../images/banner/{{$content->banner}});
    background-position: center center;
    background-repeat: no-repeat;
    background-size: cover;
}

    </style>
    
</head>

<body>
    <!-- Spinner Start -->
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <!-- Spinner End -->

    @foreach ($contents as $content)
    <!-- Topbar Start -->
       @include('topbar')
    <!-- Topbar End -->

    <!-- Navbar & Hero Start -->
    <div class="container-fluid position-relative p-0">
         @include('navbar')
    <div class="container-fluid bg-primary py-5 mb-5 hero-header">
    
        </div> 
    </div>
    <!-- Navbar & Hero End -->


<!-- Project Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                <h6 class="section-title bg-white text-center text-success px-3">Projects</h6>
                <h1 class="mb-5">Projects</h1>
            </div>
            <div class="row g-4" >
                <h1 class=" text-left mb-5" id="ongoing"> Ongoing projects</h1>
                
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">

        @foreach ($onprojects as $onproject)

                <div class="bg-white rounded-lg overflow-hidden shadow-md" style="width:50%; height:30%">
                    <div class="overflow-hidden">
                        <img class="" src="images/projects/{{ $onproject->photo }}" >
                    </div>
                    <div class="flex border-b border-gray-200">
                        <small class="flex-1 text-center py-2 border-r border-gray-200"><i class="fas fa-map-marker-alt text-success me-2"></i>
                        {{ $onproject->venue }}
                        </small>
                        <small class="flex-1 text-center py-2 border-r border-gray-200"><i class="far fa-calendar-alt text-success me-2"></i>
                        {{ $onproject->duration }} weeks
                        </small>
                        <small class="flex-1 text-center py-2"><i class="fas fa-user text-success me-2"></i>
                        {{-- {{ $onproject->participants }} people --}}
                        </small>
                    </div>
                    <div class="p-4">
                        <p>{{ $onproject->name }}</p>
                        <br><hr>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="col-span-1 flex justify-center items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" class="h-6 w-6 text-red-500" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                </svg>
                            </div>

                            <div class="col-span-1 flex justify-center items-center">
                            <a href="/editproject? id={{  $onproject->id }}&name={{ $onproject->name }}&venue={{ $onproject->venue }}&duration={{ $onproject->duration }}&description={{ $onproject->description }}&participants={{ $onproject->participants }} ">
                               <svg xmlns="http://www.w3.org/2000/svg" fill="none"  class="h-6 w-6 text-blue-500" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                </svg>
                            </a>
                            </div>
                            </div>
                    </div>
                </div>
   @endforeach
      </div></div>

     <h1 class="font-medium" id="past"> Past projects</h1>

   @foreach ($pastprojects as $pastproject)
    
                 <div class="bg-white rounded-lg overflow-hidden shadow-md" style="width:50%; height:30%">
                    <div class="overflow-hidden">
                        <img class="" src="images/projects/{{ $pastproject->photo }}" >
                    </div>
                    <div class="flex border-b border-gray-200">
                        <small class="flex-1 text-center py-2 border-r border-gray-200"><i class="fas fa-map-marker-alt text-success me-2"></i>
                        {{ $pastproject->venue }}
                        </small>
                        <small class="flex-1 text-center py-2 border-r border-gray-200"><i class="far fa-calendar-alt text-success me-2"></i>
                        {{ $pastproject->duration }} weeks
                        </small>
                        <small class="flex-1 text-center py-2"><i class="fas fa-user text-success me-2"></i>
                        {{-- {{ $pastproject->participants }} people --}}
                        </small>
                    </div>
                    <div class="p-4">
                        <p>{{ $pastproject->description }}</p>
                        <br><hr>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="col-span-1 flex justify-center items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" class="h-6 w-6 text-red-500" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                </svg>
                            </div>

                            <div class="col-span-1 flex justify-center items-center">
                            <a href="/editproject? id={{  $pastproject->id }}&name={{ $pastproject->name }}&venue={{ $pastproject->venue }}&duration={{ $pastproject->duration }}&description={{ $pastproject->description }}&participants={{ $pastproject->participants }} ">
                               <svg xmlns="http://www.w3.org/2000/svg" fill="none"  class="h-6 w-6 text-blue-500" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                </svg>
                            </a>
                            </div>
                            </div>
                    </div>
                </div>
         @endforeach 
    
        </div>  
    </div>


    <!-- Footer Start -->
    @include('footer')
    <!-- Footer End -->
 @endforeach

    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>


    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/tempusdominus/js/moment.min.js"></script>
    <script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>

</html>