<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/circles.jpg') }}"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="{{ asset('css/styles_sign_in.css') }}">
    <script src="{{ asset('js/validation.js') }}"></script>
    <title>Sign in</title>
</head>
<body>
@if(session('loginError'))
    <script>
        alert("{{ session('loginError') }}");
    </script>
@endif
<div class="login-container">
    <img src="{{ asset('images/sun-1789653_1280.png') }}" alt="Logo">
    <h1>Sign in</h1>
    <form novalidate action="/sign_in" method="post" onsubmit="return validateSignIn()">
        @csrf
        <div class="form-group">
            <label for="loginname">username:</label>
            <input type="text" id="loginname" name="loginname">
        </div>
        <div class="form-group">
            <label for="loginpassword">Password:</label>
            <input type="password" id="loginpassword" name="loginpassword">
        </div>
        <button type="submit">Sign in</button>
    </form>
    <div class="registration-text">
        <a href="/register">
            <p>New ? Let's register!</p>
        </a>
    </div>
</div>
</body>
</html>
