@foreach ($contents as $content)

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>IoT - Home</title>
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
     <link rel="stylesheet" href="../css/bootstrap.min.css">

    <!-- Template Stylesheet -->
    <link rel="stylesheet" href="../css/style.css">


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
     {{-- start of the navigation bar --}}
  
           
            <div class="container-fluid bg-success py-2 mb-5 hero-header">
                <div class="container py-2">
                    <div class="row justify-content-center py-2">
                        <div class="col-lg-10 pt-lg-5 mt-lg-5 text-center">
                            <h1 class="display-3 text-white mb-3 animated slideInDown">"{{ $content->motto }}"</h1>
                            <p class="fs-4 text-white mb-4 animated slideInDown">The Makerere University Internet of Things Lab.</p>
                        </div>
                    </div>
                </div>
            </div>

    </div>
    <!-- Navbar & Hero End -->

       
    {{-- mission statement start --}}
    <div class ="container" id="mission">
      <h6 class="section-title bg-white text-success px-3" style="margin-left:10%;">Mission and Objectives</h6>
      <div class="wow fadeInUp" data-wow-delay="0.1s">
       <p>
       <h1 id="objectives">Mission</h1>
          {{ $content->mission }}  
       </p>

     <br><br>
     <p>
    <h1>Objectives</h1>
          {{ $content->objectives }}  
       </p>
        </div>

{{--  start of the word from the team leader --}}

<br>
@foreach ($leader as $leader)

     <h1 id="word">Word from the team leader</h1>
   <div class="wow fadeInUp" data-wow-delay="0.1s">
                <div class="row">
                <div class="col-md-4">
                <br>
                    <img src = "../images/team/{{ $leader->photo}}" height="200" width="200" style="border-radius:50%;">
                    <br>
                    <h4>Dr {{ $leader->fname}} {{ $leader->lname }}</h4>
                </div>
                <div class="col-md-8">
                   <p> <br><br><br>
                       {{ $content->team_leader_word }} 
                    </p>
                    </div>
                </div>
                 </div>

      @endforeach
<br>
  @endforeach       
</div>
    <!-- Footer Start -->
     @include('footer')
    <!-- Footer End -->


    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-success btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>


    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../lib/wow/wow.min.js"></script>
    <script src="../lib/easing/easing.min.js"></script>
    <script src="../lib/waypoints/waypoints.min.js"></script>
    <script src="../lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="../lib/tempusdominus/js/moment.min.js"></script>
    <script src="../lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="../lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

    <!-- Template Javascript -->
    <script src="../js/main.js"></script>
</body>

</html>