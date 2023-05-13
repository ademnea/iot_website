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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">

    @foreach ($contents as $content)

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
        <div class="spinner-border text-success" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <!-- Spinner End -->

    <!-- Topbar Start -->
       @include('topbar')
    <!-- Topbar End -->

    <!-- Navbar & Hero Start -->
    <div class="container-fluid position-relative p-0">
         @include('navbar')
    <div class="container-fluid bg-success py-5 mb-5 hero-header">
    </div> 
    </div>
    <!-- Navbar & Hero End -->


<!-- Project Start -->
 
        <div class="container-fluid">

            <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                <h2 class="section-title bg-white text-center text-success px-3">Projects</h2>
            </div>

                <div class="container-xxl py-5">
                    <div class="container">
                        <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                            <h2 class=" text-left mb-5" id="ongoing"> Ongoing projects</h2>
                        </div>

                        <div class="row g-4">
                        @foreach ($onprojects as $onproject)

                       
                        
                        
                            <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.1s">
                                <div class="rounded pt-3">

                                    <div class="m-2 card">

                                     <a href="https://{{$onproject->website}}">
                                        <img src="images/projects/{{ $onproject->photo }}" class="card-img-top" alt="project photo" style="height:180px">
                                     </a>
                                        <div class="card-body">
                                            <h5 class="card-title">{{ $onproject->name }}</h5>
                                            <p class="card-text">
                                                {{ $onproject->description }}
                                            </p>
                                         </div>
                                    </div>
                                </div>
                            </div>

                            @endforeach
                        </div>

                    </div>
                </div>
        </div>
    <br><br>
     <h2 class="font-medium" id="past"> Past projects</h2>

 
     <div class="row g-4">
        @foreach ($pastprojects as $pastproject)

            <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.1s">
                <div class="rounded pt-3">

                    <div class="m-2 card">
                        <a href="https://{{$pastproject->website}}">
                            <img src="images/projects/{{ $pastproject->photo }}" class="card-img-top" alt="project photo" style="height:180px">
                         </a>
                        <div class="card-body">
                            <h5 class="card-title">{{ $pastproject->name }}</h5>
                            <p class="card-text">
                                {{ $pastproject->description }}
                            </p>
                         </div>
                    </div>
                </div>
            </div>

            @endforeach
        </div>

        </div>  
    </div>


    <!-- Footer Start -->
    @include('footer')
    <!-- Footer End -->
    @endforeach

    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-success btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>


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