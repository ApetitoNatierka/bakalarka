@extends('layout.navigator_intra')
@section('content')

    <link href="{{ asset('css/styles_table.css') }}" rel="stylesheet">
    <div class="container">
        <div class="card shadow">
            <div class="card-body">
                <h1 class="card-title">Services</h1>
                <button type="button" class="btn btn-secondary custom-btn" id="add_new_service">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                         class="bi bi-plus-lg" viewBox="0 0 16 16">
                        <path fill-rule="evenodd"
                              d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2"></path>
                    </svg>
                </button>
                <button type="button" class="btn btn-secondary" id="search_services">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                         class="bi bi-search" viewBox="0 0 16 16">
                        <path
                            d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"></path>
                    </svg>
                </button>
                <hr>
                <div id="search_inputs">

                </div>
                @if(isset($services))
                    <div class="card p-3">
                        <table class="service_table" id="service_table">
                            <thead>
                            <tr>
                                <th></th>
                                <th>Service No</th>
                                <th>Service</th>
                                <th>Description</th>
                                <th>Price</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(isset($services))
                                @foreach($services as $service)
                                    <tr>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-secondary dropdown-toggle no-caret" type="button"
                                                        id="dropdownMenuButton"
                                                        data-service-id="{{ $service->id }}"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                         fill="currentColor" class="bi bi-three-dots-vertical"
                                                         viewBox="0 0 16 16">
                                                        <path
                                                            d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"></path>
                                                    </svg>
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <li> <p class="dropdown-item offer_service" id="offer_service"
                                                            data-service-id="{{ $service->id }}">Offer</p></li>
                                                    <li><p class="dropdown-item modify_service"
                                                           id="modify_service"
                                                           data-service-id="{{ $service->id }}">Modify</p>
                                                    </li>
                                                    <li><p class="dropdown-item delete_service"
                                                           id="delete_service"
                                                           data-service-id="{{ $service->id }}">Delete</p>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                        <td><input type="text" class="form-control" name="service_id"
                                                   value="{{ $service->id }}" disabled></td>
                                        <td><input type="text" class="form-control" name="name"
                                                   value="{{ $service->name }}"></td>
                                        <td><input type="text" class="form-control" name="description"
                                                   value="{{ $service->description }}"></td>
                                        <td><input type="number" class="form-control" name="price"
                                                   value="{{ $service->price}}"></td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
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

    <div id="service_dialog" class="dialog" style="display: none;">
        <form id="service_form">
            @csrf
            <label>
                <input type="text" name="new_name" id="new_name" placeholder="Name">
            </label><br>
            <label>
                <input type="text" name="description" id="new_description" placeholder="Description">
            </label><br>
            <label>
                <input type="number" name="new_price" id="new_price" placeholder="Price">
            </label><br>

            <button type="button" id="new_service">New</button>
            <button type="button" id="cancel_service">Cancel</button>

        </form>
    </div>

    <div id="price_dialog" class="dialog" style="display: none;" data-service-id="">
        <form id="price_form">
            @csrf
            <label>
                <input type="number" name="price" id="new_price_offer" placeholder="price">
            </label><br>
            <button type="button" id="offer">Offer</button>
            <button type="button" id="cancel_offer">Cancel</button>
        </form>
    </div>


    <script src="{{ asset('js/manage_services.js')}}"></script>
    <meta name="csrf-token" content="tu_je_vasho_csrf_tokenu">

@endsection
