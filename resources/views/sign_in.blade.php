<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/circles.jpg') }}"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/styles_sign_in.css') }}">
    <title>Sign in</title>
</head>
<body>
<div class="container-fluid">
    <div class="card login-card">
        <div class="card-body">
            <h1 class="card-title">Sign in</h1>
            <form novalidate action="/sign_in" method="post" onsubmit="return validateSignIn()">
                @csrf
                <div class="form-group">
                    <label for="loginname">Username:</label>
                    <input type="text" id="loginname" name="loginname" class="form-control">
                </div>
                <div class="form-group">
                    <label for="loginpassword">Password:</label>
                    <input type="password" id="loginpassword" name="loginpassword" class="form-control">
                </div>
                <button type="submit" class="btn btn-primary">Sign in</button>
            </form>
            <div class="registration-text">
                <a href="/register">
                    <p>New ? Let's register!</p>
                </a>
            </div>
        </div>
    </div>
</div>

</body>
<script src="{{ asset('js/validation.js')}}"></script>
</html>
