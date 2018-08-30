<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Resume</title>

    <!-- Bootstrap core CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom fonts for this template -->
    <link href="https://fonts.googleapis.com/css?family=Saira+Extra+Condensed:500,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Muli:400,400i,800,800i" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">

    <!-- Custom styles for this template -->
    <link href="{{asset('css/resume.min.css')}}" rel="stylesheet">

</head>

<body id="page-top">


<nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top" id="sideNav">
    <a class="navbar-brand js-scroll-trigger" href="#page-top">
        <span id="name" class="d-block d-lg-none">{{$profile->name}}</span>
        <span class="d-none d-lg-block">
            <div class="img-fluid img-profile rounded-circle mx-auto mb-2" style="{{$profile->profile_url}}  width:500px; height: 500px;background-size: contain" ></div>
        </span>
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link js-scroll-trigger" href="#about">About</a>
            </li>
            <li class="nav-item">
                <a class="nav-link js-scroll-trigger" href="#experience">Experience</a>
            </li>
            <li class="nav-item">
                <a class="nav-link js-scroll-trigger" href="#education">Education</a>
            </li>
            <li class="nav-item">
                <a class="nav-link js-scroll-trigger" href="#skills">Skills</a>
            </li>
            <li class="nav-item">
                <a class="nav-link js-scroll-trigger" href="#awards">Languages</a>
            </li>
        </ul>
    </div>
</nav>

<div class="container-fluid p-0">

    <section class="resume-section p-3 p-lg-5 d-flex d-column" id="about">
        <div class="my-auto">
            <h1 class="mb-0">
                <span class="text-primary">{{$profile->name}}</span>

            </h1>
            <div class="subheading mb-5">{{$profile->location}}·
                <a href="mailto:name@email.com">{{$profile->current_position}}</a>
            </div>
            <p class="lead mb-5">{{$profile->description}}</p>
            <div class="social-icons">
                <a href="#">
                    <i class="fab fa-linkedin-in"></i>
                </a>
                <a href="#">
                    <i class="fab fa-github"></i>
                </a>
                <a href="#">
                    <i class="fab fa-twitter"></i>
                </a>
                <a href="#">
                    <i class="fab fa-facebook-f"></i>
                </a>
            </div>
        </div>
    </section>

    <hr class="m-0">

    <section class="resume-section p-3 p-lg-5 d-flex flex-column" id="experience">
        <div class="my-auto">
            <h2 class="mb-5">Experience</h2>

            @foreach($profile->experiences as $experience)
                <div class="resume-item d-flex flex-column flex-md-row mb-5">
                    <div class="resume-content mr-auto">
                        <h3 class="mb-0">{{$experience->job_position}}</h3>
                        <div class="subheading mb-3">{{$experience->company_name}}</div>
                        <div class="subheading mb-3">{{$experience->location}}</div>
                    </div>
                    <div class="resume-date text-md-right">
                        <span class="text-primary">{{$experience->dates}}</span>
                    </div>
                </div>
            @endforeach

        </div>

    </section>

    <hr class="m-0">

    <section class="resume-section p-3 p-lg-5 d-flex flex-column" id="education">
        <div class="my-auto">
            <h2 class="mb-5">Education</h2>

            @foreach($profile->educations as $education)

                <div class="resume-item d-flex flex-column flex-md-row mb-5">
                    <div class="resume-content mr-auto">
                        <h3 class="mb-0">{{$education->institution_name}}</h3>
                        <div class="subheading mb-3">{{$education->degree}}</div>
                    </div>
                    <div class="resume-date text-md-right">
                        <span class="text-primary">{{$education->dates}}</span>
                    </div>
                </div>
            @endforeach

        </div>
    </section>

    <hr class="m-0">

    <section class="resume-section p-3 p-lg-5 d-flex flex-column" id="skills">
        <div class="my-auto">
            <h2 class="mb-5">Skills</h2>

            <div class="subheading mb-3">Main Skills</div>

            <ul class="list-inline dev-icons">
                @foreach($profile->skills as $skill)
                    @if($skill->main_skill == 1)
                        <li class="list-inline-item">
                            {{ $skill->name }}
                        </li>
                    @endif
                @endforeach
            </ul>

            <div class="subheading mb-3">Other skills</div>
            <ul class="fa-ul mb-0">
                @foreach($profile->skills as $skill)
                    @if($skill->main_skill == 0)
                        <li class="list-inline-item">
                            {{ $skill->name }}
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>
    </section>

    <hr class="m-0">

    <section class="resume-section p-3 p-lg-5 d-flex flex-column" id="awards">
        <div class="my-auto">
            <h2 class="mb-5">Accomplishments</h2>
            <ul class="fa-ul mb-0">
                @foreach($profile->accomplishments as $accomplishment)
                    @if($accomplishment->accomplishment_type == 'language')
                        <i class="fa-li fa fa-trophy text-warning"></i>
                        <li>
                            {{ $accomplishment->accomplishment_name}}
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>
    </section>

</div>

<!-- Bootstrap core JavaScript -->
<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.bundle.min.js"></script>

<!-- Plugin JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.0/jquery.easing.min.js"></script>

<!-- Custom scripts for this template -->
<script src="{{asset('js/resume.min.js')}}"></script>

</body>

</html>
