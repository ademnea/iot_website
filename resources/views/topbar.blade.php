@if ($contents)
   
@foreach ($contents as $content)
    
    <div class="container-fluid bg-dark px-5 d-none d-lg-block">
        <div class="row gx-0">
            <div class="col-lg-8 text-center text-lg-start mb-2 mb-lg-0">
                <div class="d-inline-flex align-items-center" style="height: 45px;">
                    <small class="me-3 text-light"><i class="fa fa-map-marker-alt me-2"></i>{{ $content->physical_address }}</small>
                </div>
            </div>
            <div class="col-lg-4 text-center text-lg-end">
                <div class="d-inline-flex align-items-center" style="height: 45px;">
                    
                    <small class="me-3 text-light"><i class="fa fa-phone-alt me-2"></i>{{ $content->company_telephone }}</small>
                    <small class="text-light"><i class="fa fa-envelope-open me-2"></i>{{ $content->company_email }}</small>

                    {{-- <a class="btn btn-sm btn-outline-light btn-sm-square rounded-circle me-2" href="{{ $content->twitter }}"><i class="fab fa-twitter fw-normal"></i></a> --}}
                    {{-- <a class="btn btn-sm btn-outline-light btn-sm-square rounded-circle me-2" href="{{ $content->facebook }}"><i class="fab fa-facebook-f fw-normal"></i></a> --}}
                    {{-- <a class="btn btn-sm btn-outline-light btn-sm-square rounded-circle me-2" href="{{ $content->linkedin }}"><i class="fab fa-linkedin-in fw-normal"></i></a> --}}
                    {{-- <a class="btn btn-sm btn-outline-light btn-sm-square rounded-circle me-2" href="{{ $content->instagram }}"><i class="fab fa-instagram fw-normal"></i></a>
                    <a class="btn btn-sm btn-outline-light btn-sm-square rounded-circle" href="{{ $content->youtube }}"><i class="fab fa-youtube fw-normal"></i></a> --}}
                </div>
            </div>
        </div>
    </div>
@endforeach

@else

<div class="container-fluid bg-dark px-5 d-none d-lg-block">
    <div class="row gx-0">
        <div class="col-lg-8 text-center text-lg-start mb-2 mb-lg-0">
            <div class="d-inline-flex align-items-center" style="height: 45px;">
                <small class="me-3 text-light"><i class="fa fa-map-marker-alt me-2"></i>Makerere, Cocis Block B</small>
                <small class="me-3 text-light"><i class="fa fa-phone-alt me-2"></i>no phone yet</small>
                <small class="text-light"><i class="fa fa-envelope-open me-2"></i>no email yet</small>
            </div>
        </div>
        <div class="col-lg-4 text-center text-lg-end">
            <div class="d-inline-flex align-items-center" style="height: 45px;">
                <a class="btn btn-sm btn-outline-light btn-sm-square rounded-circle me-2" href="#"><i class="fab fa-twitter fw-normal"></i></a>
                <a class="btn btn-sm btn-outline-light btn-sm-square rounded-circle me-2" href="#"><i class="fab fa-facebook-f fw-normal"></i></a>
                <a class="btn btn-sm btn-outline-light btn-sm-square rounded-circle me-2" href="#"><i class="fab fa-linkedin-in fw-normal"></i></a>
                <a class="btn btn-sm btn-outline-light btn-sm-square rounded-circle me-2" href="#"><i class="fab fa-instagram fw-normal"></i></a>
                <a class="btn btn-sm btn-outline-light btn-sm-square rounded-circle" href="#"><i class="fab fa-youtube fw-normal"></i></a>
            </div>
        </div>
    </div>
</div>

@endif
