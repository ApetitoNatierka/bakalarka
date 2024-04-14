<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sidebar</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link href="{{ asset('css/styles_navigator_intra.css') }}" rel="stylesheet">

</head>
<body>
<nav class="navbar navbar-expand-lg " aria-label="Twelfth navbar example">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsExample10" aria-controls="navbarsExample10" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-md-center" id="navbarsExample10">
            <ul class="navbar-nav">
                @if(auth()->user()->get_role() == 'admin' || auth()->user()->get_role() == 'employee')
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="/intranet">Intranet</a>
                    </li>
                @endauth
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="/homepage">Get back home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="/prod_animals">Animals</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="/products">Products</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="/services">Services</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/cart">Cart</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false">Account</a>
                    <ul class="dropdown-menu">
                        @auth
                            <li><a class="dropdown-item" href="/company_profile">Company</a></li>
                        @endauth
                        @auth
                            <li><a class="dropdown-item" href="/user_profile">User profile</a></li>
                        @else
                            <li><a class="dropdown-item" href="/sign_in">User profile</a></li>
                        @endauth
                        <li><a class="dropdown-item" href="/sign_in">Sign in</a></li>
                        <li><a class="dropdown-item" href="/logout">Sign out</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="content-under-navbar">

    <div class="layout-wrapper">
        @include('layout.sidebar')
        <div class="content">
            @yield('content')
        </div>
    </div>

</div>


</body>
</html>
