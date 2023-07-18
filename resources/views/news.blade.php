<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>news - IoT</title>
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

    </div>
    <!-- Navbar & Hero End -->

    <br><br><br>

    <!-- Team Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
            </div>
            <div class="row g-4">

     <h2>Events</h2>

          @foreach ($onevents as $onevent)
              
                <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="team-item">
                        <div class="overflow-hidden">
                            <a href="http://{{$onevent->article_link}}" style=" 
                                text-decoration: none !important;
                                color: black;">
                            <img class="img-fluid" src="images/events/{{ $onevent->photo }}" alt="">
                        </div>
                        <div class="position-relative d-flex justify-content-center" style="margin-top: -19px;">
                        </div>
                        <div class="text-center p-4">
                        <h5 class="mb-0">

                                <svg style="width: 1.5rem; height: 1.5rem; color:green;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                                  </svg>
                              
                            {{ $onevent->venue }}</h5>
                            <hr>
                        <h5 class="mb-0">{{ $onevent->title }}</h5>

                            {{-- <small>{{ $onevent->description }}</small> --}}

                            <span class="collapsed-text">
                                <!-- Display only one line of text -->
                               {{ \Illuminate\Support\Str::words($onevent->description, $words = 10, $end = '....') }}
                             </span>
            
                            <a class="" data-bs-toggle="modal" data-bs-target="#{{ $onevent->id }}" style="cursor:pointer;">
                                More <i class="fas fa-chevron-down"></i>
                            </a>

                        <!-- Modal -->
                <div class="modal fade" id="{{ $onevent->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">{{ $onevent->title }}</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            {{-- {{ $team->bio }} --}}
                       <small> {!! preg_replace('/\r\n|\r|\n/', '<br>', $onevent->description) !!}</small>
                        </div>
                        <div class="modal-footer">
                        <a href="http://{{$onevent->article_link}}" style="cursor:pointer;" class="text-primary" >Click Here </a> to read the article
                        </div>
                    </div>
                    </div>
                </div>
          {{-- end bio popup --}}

                        </a>
                        </div>
                    </div>
                </div>
                         @endforeach
  <br><br>

       
   {{-- <h2>Past Events</h2> --}}

            @foreach ($pastevents as $pastevent)
              
                <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="team-item">
                        <div class="overflow-hidden">
                            <img class="img-fluid" src="images/events/{{ $pastevent->photo }}" alt="">
                        </div>
                        <div class="position-relative d-flex justify-content-center" style="margin-top: -19px;">
                            {{-- <a class="btn btn-square mx-1" href=""><i class="fab fa-facebook-f"></i></a> --}}
                            {{-- <a class="btn btn-square mx-1" href=""><i class="fab fa-instagram"></i></a>  --}}
                        </div>
                        <div class="text-center p-4">
                        <h5 class="mb-0">
                            {{-- <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                            </svg> --}}
                            {{ $pastevent->venue }}</h5>
                        <h5 class="mb-0">{{ $pastevent->title }}</h5>

                            <small>{{ $pastevent->description }}</small>
                        </div>
                    </div>
                </div>
                 @endforeach


            </div>
        </div>
    </div>
    <!-- upcoming end-->
        

    <!-- Footer Start -->
     @include('footer')
    <!-- Footer End -->

    @endforeach
    
    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>

<!-- JavaScript Libraries -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g="crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js" integrity="sha384-fbbOQedDUMZZ5KreZpsbe1LCZPVmfTnH7ois6mU1QK+m14rQ1l2bGBq41eYeM/fS" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>

{{-- <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script> --}}

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