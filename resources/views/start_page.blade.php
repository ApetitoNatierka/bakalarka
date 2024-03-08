
@extends('layout.navigator')
@section('content')
    <link href="{{ asset('css/styles_start_page.css') }}" rel="stylesheet">


    <div class="container text-center">
        <div class="row">
            <div class="col">
                <div class="card">
                    <img src="{{ asset('images/bull.png') }}" class="card-img-top" alt="Placeholder image">
                    <div class="card-body">
                        <h5 class="card-title">Card title</h5>
                        <p class="card-text">This is a longer card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="col">
                    <a href="/products">
                        <div class="card">
                            <img src="{{ asset('images/products.jpg') }}" class="card-img-top" alt="Placeholder image">
                            <div class="card-body">
                                <h5 class="card-title">Products</h5>
                                <p class="card-text">This is a longer card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col">
                <div class="col">
                    <div class="card">
                        <img src="{{ asset('images/services.jpg') }}" class="card-img-top" alt="Placeholder image">
                        <div class="card-body">
                            <h5 class="card-title">Card title</h5>
                            <p class="card-text">This is a longer card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
