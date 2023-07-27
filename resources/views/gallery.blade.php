<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>gallery - IoT</title>
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
 
     {{-- links for the contact section --}}
     
     <!-- Libraries Stylesheet -->
     <link href="lib/animate/animate.min.css" rel="stylesheet">
     <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
     <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />
 
     <!-- Customized Bootstrap Stylesheet -->
     {{-- <link href="css/bootstrap.min.css" rel="stylesheet"> --}}
 
     <!-- Template Stylesheet -->
     <link href="css/style.css" rel="stylesheet">
 
     {{-- online bootstrap --}}
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
 
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
    </div>
    <!-- Navbar & Hero End -->

    <!-- content start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="text-center wow fadeInUp" data-wow-delay="0.1s">

                <div class="row">
                    <div class="col-md-6">
                        <!-- Content for the first column goes here -->
                        <h3>Welcome to the gallery, <br> pick a category to view the pictures</h3>
                        <br><br><br>
                        <div class="list-group list-group-flush">
                            <a href="#" class="list-group-item list-group-item-action">News and Events</a>
                            <a href="#" class="list-group-item list-group-item-action">Project photos</a>
                            <a href="#" class="list-group-item list-group-item-action">Prototypes</a>
                            <a href="#" class="list-group-item list-group-item-action">Team photos</a>
                            <a class="list-group-item list-group-item-action disabled" aria-disabled="true">Others</a>
                          </div>
                    </div>
                    <div class="col-md-6">
                      <!-- Content for the second column goes here -->
                      <div class="card" style="width: 22rem;">
  
                        <div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel">
                          <div class="carousel-inner">

                            @foreach ($randomPhotos as $key => $photo)
                            <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                              <img src="{{ asset('images/team/' . $photo->photo) }}" class="card-img-top" alt="...">
                            </div>
                          @endforeach

                          </div>
                          <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                          </button>
                          <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                          </button>
                        </div>
                        
                          <div class="card-body">
                            <h5 class="card-title">Meet the Team</h5>
                            <p class="card-text">view our full team.</p>
                            <a href="#" class="btn btn-success">Show</a>
                          </div>
                        </div>

                    </div>
                  </div>

            </div>
            <div class="row g-4">


            </div>
        </div>
    </div>
    <!-- upcoming end-->
        

    <!-- Footer Start -->
     @include('footer')
    <!-- Footer End -->

    @endforeach
    
    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-success btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>

<!-- JavaScript Libraries -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g="crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js" integrity="sha384-fbbOQedDUMZZ5KreZpsbe1LCZPVmfTnH7ois6mU1QK+m14rQ1l2bGBq41eYeM/fS" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>

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