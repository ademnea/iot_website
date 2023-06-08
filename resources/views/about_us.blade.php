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

    {{-- links for the contact section --}}
    
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

<br><br><br>


{{-- team start--}}
 
    <!-- Team Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                <h2 class="mb-5" id="researchers">Our Team</h2>
            </div>

            <div class="row g-4">
    
      @foreach ($teams as $team)

                <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.7s">
                    <div class="team-item">
                            <img class="card-img-top" alt="project photo" style="height:250px" src="images/team/{{ $team->photo }}" alt="">

                        <div class="position-relative d-flex justify-content-center" style="margin-top: -19px;">
                            <a class="btn btn-square mx-1" href=""><i class="fab fa-facebook-f"></i></a>
                            <a class="btn btn-square mx-1" href=""><i class="fab fa-twitter"></i></a>
                            <a class="btn btn-square mx-1" href=""><i class="fab fa-instagram"></i></a>
                        </div>

                        <div class="text-center p-4">
                            <h5 class="mb-0">{{ $team->fname }}  {{ $team->lname }}</h5>
                            <small>{{ $team->designation }}</small>
                        </div>
                    </div>
                </div>
          
      @endforeach
            </div>
        </div>
    </div>
    <!-- team Team End -->  
   

    <!-- intern Team Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                <h2>Interns</h2>
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
            </div>
            
      <br  id="contactus">
    <!-- Team End -->

    <!-- contacts start -->
    <br>
    <div class="container" >

        <center>
            {{-- displaying an alert after registering a category --}}
               @if (session('message'))

               <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>{{ session('message') }}</strong> we will get back to you. 
              </div>

              @endif
      </center>
        <div class="row">
            <div class="col-md-7">
              <h4>Get in touch</h4>
            <form method="post" action="/insert_message">
                @csrf

                <div class="mb-3">
                    <label for="formGroupExampleInput" class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" id="formGroupExampleInput" placeholder="Enter your name" required>
                  </div>
                  <div class="mb-3">
                    <label for="formGroupExampleInput2" class="form-label">Email</label>
                    <input type="text" name="email" class="form-control" id="formGroupExampleInput2" placeholder="Enter your email" required>
                  </div>
                  <div class="mb-3">
                    <label for="formGroupExampleInput2" class="form-label">Contact Number</label>
                    <input type="text" name="phone" class="form-control" id="formGroupExampleInput2" placeholder="Enter your number">
                  </div>
                  <div class="mb-3">
                    <label for="exampleFormControlTextarea1" class="form-label">Message</label>
                    <textarea class="form-control" name="message" id="exampleFormControlTextarea1" rows="3"></textarea>
                  </div>
                  <button class="btn btn-success">Submit</button>
            </div>
        </form>

            <div class="col-md-5 ">
              <h4>Contact us</h4><hr>
              <div class="mt-4">
                  <div class="d-flex">
                    <i class="bi bi-geo-alt-fill"></i>
                    <p >Address: {{ $content->physical_address }}</p>
                  </div><hr>
                  <div class="d-flex">
                    <i class="bi bi-telephone-fill"></i>
                    <p>Contact: {{ $content->company_telephone }}</p>
                  </div><hr>
                  <div class="d-flex">
                    <i class="bi bi-envelope-fill"></i>
                    <p>Email: {{ $content->company_email }}</p>
                  </div><hr>
                  <div class="d-flex">
                    <i class="bi bi-browser-chrome"></i>
                    {{-- <p>Website: www.contact.com</p> --}}
                  </div><hr>
              </div>
            </div>
        </div>
    </div>

     <!-- contacts end -->


   <!-- Partners Start -->
    <div class="container-xxl py-5 wow fadeInUp" data-wow-delay="0.1s">
        <div class="container">
            <div class="text-center" id="partners">
                <h2 class="section-title bg-white text-center text-success px-3">Partners</h2>
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