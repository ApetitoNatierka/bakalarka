<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/styles_register.css') }}">
</head>
<body>
<div class="modal modal-sheet position-static d-block custom-modal-background p-4 py-md-5" tabindex="-1" role="dialog" id="modalRegister">
    <div class="modal-dialog" role="document">
        <div class="modal-content rounded-4 shadow">
            <div class="modal-header p-5 pb-4 border-bottom-0 custom-modal-header">
                <h1 class="fw-bold mb-0 fs-2">Register for free</h1>
            </div>
            <div class="modal-body p-5 pt-0 custom-modal-body">
                <form class="needs-validation" novalidate action="/register" method="POST">
                    @csrf
                    <div class="form-floating mb-3 custom-form-floating">
                        <input type="text" class="form-control rounded-3 custom-input" id="name" placeholder="name" name="name">
                        <label for="name" class="custom-label">Username</label>
                    </div>
                    <div class="form-floating mb-3 custom-form-floating">
                        <input type="email" class="form-control rounded-3 custom-input" id="email" placeholder="name@example.com" name="email">
                        <label for="email" class="custom-label">Email address</label>
                    </div>
                    <div class="form-floating mb-3 custom-form-floating">
                        <input type="password" class="form-control rounded-3 custom-input" id="password" placeholder="Password" name="password">
                        <label for="password" class="custom-label">Password</label>
                    </div>
                    <div class="form-floating mb-3 custom-form-floating">
                        <input type="password" class="form-control rounded-3 custom-input" id="password_confirmation" placeholder="Password" name="password_confirmation">
                        <label for="password_confirmation" class="custom-label">Repeat password</label>
                    </div>
                    <button class="w-100 mb-2 btn btn-lg rounded-3 custom-btn-primary" type="submit">Register</button>
                    <small class="text-body-secondary custom-text-secondary">By clicking Register, you agree to the terms of use.</small>
                    <hr class="my-4">
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>
