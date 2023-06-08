

       <nav class="navbar navbar-expand-lg bg-white navbar-light shadow-sm px-5 py-3 py-lg-0">
            <a href="/" class="navbar-brand p-0">

                @foreach ($contents as $content)
                {{-- <h1 class="text-success m-0">Makerere IoT Lab</h1> --}}
                <img src="../images/logo/{{ $content->logo }}" alt="Logo" style="height: 150px; width: 120px;">
                @endforeach

            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="fa fa-bars"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav ms-auto py-0">
              
                    {{-- homepage dropdown --}}
                        <div class="nav-item dropdown">
                        <a href="/" class="nav-link dropdown-toggle">Home</a>
                        <div class="dropdown-menu m-0">
                            <a href="/#vision" class="dropdown-item">Vision</a>
                            <a href="/#mission" class="dropdown-item">Mission</a>
                            <a href="/#objectives" class="dropdown-item">Objectives</a>
                            <a href="/#word" class="dropdown-item">Word from Team Leader</a>
                        </div>
                    </div>

                    {{-- projects dropdown --}}
               <div class="nav-item dropdown">
                        <a href="projects" class="nav-link dropdown-toggle">Projects</a>
                        <div class="dropdown-menu m-0">
                            <a href="/projects#ongoing" class="dropdown-item">Ongoing Projects</a>
                            <a href="/projects#past" class="dropdown-item">Past Projects</a>
                        </div>
                    </div>

                    {{-- products dropdown --}}
                   <div class="nav-item dropdown">
                        <a href="/publications" class="nav-link dropdown-toggle">Products</a>
                        <div class="dropdown-menu m-0">
                            <a href="/publications#products" class="dropdown-item">prototypes</a>
                            <a href="/publications#reports" class="dropdown-item">technical reports</a>
                            <a href="/publications#datasets" class="dropdown-item">Data sets</a>
                        </div>
                    </div>

                    <a href="/news" class="nav-item nav-link">News</a>

                    {{-- about us dropdown --}}
                      <div class="nav-item dropdown">
                        <a href="/about" class="nav-link dropdown-toggle">About Us</a>
                        <div class="dropdown-menu m-0">
                            <a href="/about#researchers" class="dropdown-item">Team</a>
                            <a href="/about#contactus" class="dropdown-item">Contact Us</a>
                            <a href="/about#partners" class="dropdown-item">Partners</a>
                          </div>
                       </div>
                </div>
                <a href="/logmein" class="btn btn-success rounded-pill py-2 px-4">Admin 
                </a>
            </div>
        </nav>
  