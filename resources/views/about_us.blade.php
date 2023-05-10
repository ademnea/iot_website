<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>IoT-about</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Nunito:wght@600;700;800&display=swap" rel="stylesheet">

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
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
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

    <div class="container-fluid bg-primary py-5 mb-5 hero-header">
    
        </div> 
    </div>
    <!-- Navbar & Hero End -->




{{-- team start--}}
 
    <!-- Team Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                <h6 class="section-title bg-white text-center text-success px-3">Team</h6>
                <h1 class="mb-5" id="researchers">Researchers</h1>
            </div>
            <div class="row g-4">
    
      @foreach ($researchers as $researcher)

                <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.7s">
                    <div class="team-item">
                        <div class="overflow-hidden">
                            <img class="img-fluid" src="images/team/{{ $researcher->photo }}" alt="">
                        </div>

                        <div class="position-relative d-flex justify-content-center" style="margin-top: -19px;">
                            <a class="btn btn-square mx-1" href=""><i class="fab fa-facebook-f"></i></a>
                            <a class="btn btn-square mx-1" href=""><i class="fab fa-twitter"></i></a>
                            <a class="btn btn-square mx-1" href=""><i class="fab fa-instagram"></i></a>
                        </div>

                        <div class="text-center p-4">
                            <h5 class="mb-0">{{ $researcher->fname }}  {{ $researcher->lname }}</h5>
                            <small>{{ $researcher->designation }}</small>
                        </div>
                    </div>
                </div>
          
      @endforeach


            </div>
        </div>
    </div>
    <!-- researcher Team End -->  
   
   
   
   
   
   
    <!-- intern Team Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                <h6 class="section-title bg-white text-center text-success px-3" id="interns">Interns</h6>
                <h1 class="mb-5">Meet Our Intern Team</h1>
            </div>
            <div class="row g-4">

    {{-- interns begin here  --}}

      @foreach ($interns as $intern)

                <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.7s">
                    <div class="team-item">
                        <div class="overflow-hidden">
                            <img class="img-fluid" src="images/team/{{ $intern->photo }}" alt="">
                        </div>

                        <div class="position-relative d-flex justify-content-center" style="margin-top: -19px;">
                            <a class="btn btn-square mx-1" href=""><i class="fab fa-facebook-f"></i></a>
                            <a class="btn btn-square mx-1" href=""><i class="fab fa-twitter"></i></a>
                            <a class="btn btn-square mx-1" href=""><i class="fab fa-instagram"></i></a>
                        </div>

                        <div class="text-center p-4">
                            <h5 class="mb-0">{{ $intern->fname }}  {{ $intern->lname }}</h5>
                            <small>{{ $intern->designation }}</small>
                        </div>
                    </div>
                </div>
          
      @endforeach
    <!-- Team End -->

    <!-- contacts start -->




     <!-- contacts end -->


   <!-- Partners Start -->
    <div class="container-xxl py-5 wow fadeInUp" data-wow-delay="0.1s">
        <div class="container">
            <div class="text-center" id="partners">
                <h6 class="section-title bg-white text-center text-success px-3">Partners</h6>
                <h1 class="mb-5">Our partners</h1>
            </div>

            <div class="owl-carousel testimonial-carousel position-relative">

       @foreach ($partners as $partner)

                <div class="testimonial-item bg-white text-center border p-4">
                    <img class="bg-white rounded-circle shadow p-1 mx-auto mb-3" src="images/partners/{{ $partner->logo }}" style="width: 80px; height: 80px;">
                    <h5 class="mb-0">{{ $partner->name }}</h5>
                    {{-- <p>New York, USA</p> --}}
                    <p class="mb-0">
                    {{ $partner->description }}
                    </p>
                </div>
                   
       @endforeach
               
            </div>
        </div>
    </div>
    <!-- Testimonial End -->
                         </div>
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