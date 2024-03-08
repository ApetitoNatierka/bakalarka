
@extends('layout.navigator')
@section('content')

    <link href="{{ asset('css/styles_products.css') }}" rel="stylesheet">
    <div class="container">
        <div class="card shadow">
            <div class="card-body">
                <h1 class="card-title">Products</h1>
                <hr>
                <div class="card p-3">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-4">
                            <img src="{{ asset('images/products.webp') }}" class="card-img-top" alt="Placeholder image">
                        </div>
                        <div class="col-8">
                            <h3 id="product_name">Názov produktu</h3>
                            <p id="product_description">Popis produktu</p>
                            <p id="product_price">Cena: XX€</p>
                       </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="user_dialog" class="dialog" style="display: none;">
        <form id="user_form">
            @csrf
            <label>
                <input type="text" name="name" id="name" placeholder="Name">
            </label><br>
            <label>
                <input type="text" name="email" id="email" placeholder="Email">
            </label><br>
            <label>
                <input type="text" name="password" id="password" placeholder="Password">
            </label><br>
            <label>
                <input type="text" name="phone_number" id="phone_number" placeholder="Phone number">
            </label><br>
            <label>
                <input type="text" name="company_position" id="company_position" placeholder="Company position">
            </label><br>
            <button type="button" id="new_user">New</button>
            <button type="button" id="cancel_user">Cancel</button>

        </form>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <meta name="csrf-token" content="tu_je_vasho_csrf_tokenu">

@endsection
