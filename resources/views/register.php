<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title></title>
</head>
<body>
    <div class="modal-body p-5 pt-0">
        <form class="needs-validation" novalidate action="/register" method="POST">
            @csrf
            <div class="form-floating mb-3">
                <input type="text" class="form-control rounded-3" id="name" placeholder="name" name="name">
                <label for="name">Username</label>
            </div>
            <div class="form-floating mb-3">
                <input type="email" class="form-control rounded-3" id="email" placeholder="name@example.com" name="email">
                <label for="email">Email address</label>
            </div>
            <div class="form-floating mb-3">
                <input type="password" class="form-control rounded-3" id="password" placeholder="Password" name="password">
                <label for="password">Password</label>
            </div>
            <div class="form-floating mb-3">
                <input type="password" class="form-control rounded-3" id="password_confirmation" placeholder="Password" name="password_confirmation">
                <label for="password_confirmation">Repeat password</label>
            </div>
            <button class="w-100 mb-2 btn btn-lg rounded-3 btn-primary" type="submit">Register</button>
            <small class="text-body-secondary">By clicking Register, you agree to the terms of use.</small>
            <hr class="my-4">
        </form>
    </div>
</body>
</html>
