@extends('layout.navigator_intra')
@section('content')

    <link href="{{ asset('css/styles_table.css') }}" rel="stylesheet">
    <div class="container">
        <div class="card shadow">
            <div class="card-body">
                <h1 class="card-title">Animals</h1>
                <button type="button" class="btn btn-secondary custom-btn" id="add_new_animals">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                         class="bi bi-plus-lg" viewBox="0 0 16 16">
                        <path fill-rule="evenodd"
                              d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2"></path>
                    </svg>
                </button>
                <button type="button" class="btn btn-secondary" id="search_animals">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                         class="bi bi-search" viewBox="0 0 16 16">
                        <path
                            d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"></path>
                    </svg>
                </button>
                <hr>
                <div id="search_inputs">

                </div>
                @if(isset($animals))
                    <div class="card p-3">
                        <table class="animals_table" id="animals_table">
                            <thead>
                            <tr>
                                <th></th>
                                <th>Animal Id</th>
                                <th>Animal No</th>
                                <th>Weight</th>
                                <th>Height</th>
                                <th>Born</th>
                                <th>Condition</th>
                                <th>Gender</th>
                                <th>Warehouse</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(isset($animals))
                                @foreach($animals as $animal)
                                    <tr>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-secondary dropdown-toggle no-caret" type="button"
                                                        id="dropdownMenuButton" data-animal-id="{{ $animal->id }}"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                         fill="currentColor" class="bi bi-three-dots-vertical"
                                                         viewBox="0 0 16 16">
                                                        <path
                                                            d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"></path>
                                                    </svg>
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <li><a href="/animal/{{$animal->id}}"
                                                           class="dropdown-item detail_animal" id="detail_animal"
                                                           data-animal-id="{{ $animal->id }}">Detail</a></li>
                                                    <li><p class="dropdown-item modify_animal" id="modify_animal"
                                                           data-animal-id="{{ $animal->id }}">Modify</p></li>
                                                    <li><p class="dropdown-item delete_animal" id="delete_animal"
                                                           data-animal-id="{{ $animal->id }}">Delete</p></li>
                                                </ul>
                                            </div>
                                        </td>
                                        <td><input type="number" class="form-control" name="animal_id"
                                                   value="{{ $animal->id }}" disabled></td>
                                        <td>
                                            <select class="form-control" name="animal_no_id">
                                                @foreach($animal_nos as $animal_no)
                                                    <option value="{{ $animal_no->id }}" {{ $animal_no->id == $animal->animal_number_id ? 'selected' : '' }}>{{ $animal_no->id . ' : ' .  $animal_no->animal_number }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td><input type="number" class="form-control" name="weight"
                                                   value="{{ $animal->weight }}"></td>
                                        <td><input type="number" class="form-control" name="height"
                                                   value="{{ $animal->height }}"></td>
                                        <td><input type="date" class="form-control" name="born"
                                                   value="{{ $animal->born }}"></td>
                                        <td><input type="text" class="form-control" name="condition"
                                                   value="{{ $animal->condition }}"></td>
                                        <td><input type="text" class="form-control" name="gender"
                                                   value="{{ $animal->gender }}"></td>
                                        <td>
                                            <select class="form-control" name="warehouse_id">
                                                @foreach($warehouses as $warehouse)
                                                    <option value="{{ $warehouse->id }}" {{ $warehouse->id == $animal->warehouse_id ? 'selected' : '' }}>{{ $warehouse->id . ' : ' .  $warehouse->warehouse }}</option>
                                                @endforeach
                                            </select>
                                        </td>
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

    <div id="animal_dialog" class="dialog" style="display: none;">
        <form id="animal_form">
            @csrf
            <label>
                <select id="animal_no_select" name="animal"></select>
            </label><br>
            <label>
                <input type="number" name="weight" id="new_weight" placeholder="Weight">
            </label><br>
            <label>
                <input type="number" name="height" id="new_height" placeholder="Height">
            </label><br>
            <label>
                <input type="date" name="born" id="new_born" placeholder="Born">
            </label><br>
            <label>
                <input type="text" name="condition" id="new_condition" placeholder="Condition">
            </label><br>
            <label>
                <input type="text" name="gender" id="new_gender" placeholder="Gender">
            </label><br>
            <label>
                <select id="warehouse_select" name="warehouse"></select>
            </label><br>

            <button type="button" id="new_animal">New</button>
            <button type="button" id="cancel_animal">Cancel</button>

        </form>
    </div>

    <script src="{{ asset('js/manage_animals.js')}}"></script>
    <meta name="csrf-token" content="tu_je_vasho_csrf_tokenu">

@endsection
