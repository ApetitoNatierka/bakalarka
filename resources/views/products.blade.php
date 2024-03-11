
@extends('layout.navigator')
@section('content')

    <link href="{{ asset('css/styles_products.css') }}" rel="stylesheet">
    <div class="container" data-product-image-path="{{ asset('images/products.webp') }}">
        <div class="card shadow">
            <div class="card-body">
                <h1 class="card-title">Products</h1>
                @if(isset($user) && $user->get_role() == 'admin')
                    <button type="button" class="btn btn-secondary custom-btn" id="add_new_product">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2"></path>
                        </svg>
                    </button>
                @endif
                <button type="button" class="btn btn-secondary" id="search_products">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"></path>
                    </svg>
                </button>
                <hr>
                <div id="search_inputs">

                </div>
                @if(isset($products))
                    @foreach($products as $product)
                    <div class="product" data-product-id="{{ $product->id }}">
                        <div class="card p-3">
                            @csrf
                            <div class="row mb-3">
                                <div class="col-4">
                                    <img src="{{ asset('images/products.webp') }}" class="card-img-top" alt="Placeholder image">
                                </div>
                                @if(isset($user) && $user->get_role() == 'admin')
                                <div class="col-8">
                                        <label>
                                            <input name="product_name" value="{{$product->get_name()}}">
                                        </label>
                                        <label>
                                            <textarea name="product_description">{{$product->get_description()}}</textarea>
                                        </label>
                                        <label>
                                            <input name="product_price" value="{{$product->get_price()}}€">
                                        </label>
                                    <button id="add_to_cart_button" style="border-radius: 5px" >add to cart</button>
                                @else
                                <div class="col-8">
                                        <h3>Name - {{$product->get_name()}}</h3>
                                        <p>Description - {{$product->get_description()}}</p>
                                        <p>Price - {{$product->get_price()}}€</p>
                                    <button id="add_to_cart_button" style="border-radius: 5px" >add to cart</button>
                                @endif
                               </div>
                                @if(isset($user) && $user->get_role() == 'admin')
                                    <div class="dropdown">
                                        <button class="btn btn-secondary dropdown-toggle no-caret" type="button" id="dropdownMenuButton" data-product-id="{{ $product->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                                                <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"></path>
                                            </svg>
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <li><p class="dropdown-item modify_product" id="modify_product" data-product-id="{{ $product->id }}">Modify</p></li>
                                            <li><p class="dropdown-item delete_product" id="delete_product" data-product-id="{{ $product->id }}">Delete</p></li>
                                        </ul>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="card p-3">
                        @csrf
                        <div class="row mb-3 align-content-center">
                            <p> no data found</p>
                            <hr>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div id="product_dialog" class="dialog" style="display: none;">
        <form id="product_form">
            @csrf
            <label>
                <input type="text" name="product_name" id="product_name" placeholder="Name">
            </label><br>
            <label>
                <input type="text" name="product_description" id="product_description" placeholder="Description">
            </label><br>
            <label>
                <input type="text" name="product_price" id="product_price" placeholder="Price">
            </label><br>
            <button type="button" id="new_product">New</button>
            <button type="button" id="cancel_product">Cancel</button>
        </form>
    </div>

    <div id="quantity_dialog" class="dialog" style="display: none;" data-cart-product-id="">
        <form id="quantity">
            @csrf
            <label>
                <input type="number" id="quantity" name="quantity" placeholder="Quantity"/>
            </label><br>
            <button type="button" id="add_to_cart">New</button>
            <button type="button" id="cancel_add_to_cart">Cancel</button>
        </form>
    </div>

    <div id="show_cart_dialog" class="dialog" style="display: none;">
        <form id="show_cart">
            @csrf
            <p>Proceed to cart?</p>
            <button type="button" id="go_to_cart">New</button>
            <button type="button" id="cancel_go_to_cart">Cancel</button>
        </form>
    </div>

    <script src="{{ asset('js/manage_products.js')}}"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <meta name="csrf-token" content="tu_je_vasho_csrf_tokenu">

@endsection
