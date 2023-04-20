       <nav class="navbar navbar-expand-lg navbar-light px-4 px-lg-5 py-3 py-lg-0">
            <a href="/" class="navbar-brand p-0">
                {{-- <h1 class="text-success m-0">Makerere IoT Lab</h1> --}}
                <img src="../img/logo.jpeg" alt="Logo">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="fa fa-bars"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav ms-auto py-0">
                {{-- homepage dropdown --}}
                        <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle">Home</a>
                        <div class="dropdown-menu m-0">
                            <a href="/#mission" class="dropdown-item">Mission</a>
                            <a href="/#objectives" class="dropdown-item">Objectives</a>
                            <a href="/#word" class="dropdown-item">Word from Team Leader</a>
                            <a href="/#partners" class="dropdown-item">Partners</a>
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

                   <div class="nav-item dropdown">
                        <a href="/publications" class="nav-link dropdown-toggle">publications</a>
                        <div class="dropdown-menu m-0">
                            <a href="/publications#products" class="dropdown-item">products</a>
                            <a href="/publications#reports" class="dropdown-item">technical reports</a>
                            <a href="/publications#datasets" class="dropdown-item">Data sets</a>
                        </div>
                    </div>

                    <a href="/news" class="nav-item nav-link">News</a>

                      <div class="nav-item dropdown">
                        <a href="/about" class="nav-link dropdown-toggle">About Us</a>
                        <div class="dropdown-menu m-0">
                            <a href="/about#researchers" class="dropdown-item">Researchers</a>
                            <a href="/about#interns" class="dropdown-item">Interns</a>
                          </div>
                       </div>
                </div>
                <a href="/login" class="btn btn-success rounded-pill py-2 px-4">Admin 
                </a>
            </div>
        </nav>