@extends('layout.navigator_intra')
@section('content')
    <link href="{{ asset('css/styles_welcome.css') }}" rel="stylesheet">
        <p class="welcome-message">Welcome</p>
    <style>
        .content {
            background-image: url("{{ asset('images/welcome_img.png') }}");
            background-size: cover; /* Zabezpečí pokrytie celej oblasti bez zmeny pomeru strán */
            background-position: center; /* Centruje obrázok na pozadí */
        }
    </style>

@endsection
