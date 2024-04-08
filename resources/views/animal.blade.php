
@extends('layout.navigator_intra')
@section('content')
    <link href="{{ asset('css/styles_form.css') }}" rel="stylesheet">
    <div class="container">
        <div class="card shadow">
            <div class="card-body">
                @if(isset($animal))
                    <h1 class="card-title">Animal - {{$animal->id}}</h1>
                @endif
                <button type="button" class="btn btn-secondary custom-btn" id="add_new_animal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2"></path>
                    </svg>
                </button>
                @if(isset($animal))
                    <button type="button" class="btn btn-secondary custom-btn" id="modify_animal">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-save" viewBox="0 0 16 16">
                            <path d="M2 1a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H9.5a1 1 0 0 0-1 1v7.293l2.646-2.647a.5.5 0 0 1 .708.708l-3.5 3.5a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L7.5 9.293V2a2 2 0 0 1 2-2H14a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h2.5a.5.5 0 0 1 0 1z"></path>
                        </svg>
                    </button>
                    <button type="button" class="btn btn-secondary" id="delete_animal">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293z"></path>
                        </svg>
                    </button>
                @endif
                <div class="animal-container mt-3">
                    <div class="dorpdown mt-3">
                        <button class="btn btn-secondary dropdown-toggle w-100" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                            Commands
                        </button>
                        <div class="dropdown-menu w-100" id="dropdownMenu" aria-labelledby="dropdownMenuButton">
                            <div class="card p-3">
                                <h5 class="card-title">Commands</h5>

                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="card p-3">
                    @csrf
                    <div class="row mb-3">
                        <div class="col">
                            <label for="name" class="col-form-label">Animal number</label>
                            @if(isset($animal))
                                <input type="number" class="form-control col custom-input" id="animal_id" name="animal_id" value="{{ $animal->id }}" disabled>
                            @else
                                <input type="number" class="form-control col custom-input" id="animal_id" name="animal_id" disabled>
                            @endif
                        </div>
                        <div class="col">
                            <label for="animal_no" class="col-form-label">Animal No</label>
                            @if(isset($animal))
                                <select class="form-control" name="animal_no_id" id="animal_no_id">
                                    @foreach($animal_nos as $animal_no)
                                        <option value="{{ $animal_no->id }}" {{ $animal_no->id == $animal->animal_number_id ? 'selected' : '' }}>{{ $animal_no->id . ' : ' .  $animal_no->animal_number }}</option>
                                    @endforeach
                                </select>
                            @endif
                        </div>
                        <div class="col">
                            <label for="location" class="col-form-label">Weight</label>
                            @if(isset($animal))
                                <input type="number" class="form-control col custom-input" id="weight" name="weight" value="{{ $animal->weight }}" >
                            @else
                                <input type="number" class="form-control col custom-input" id="weight" name="weight" disabled>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label for="height" class="col-form-label">Height</label>
                            @if(isset($animal))
                                <input type="number" class="form-control col custom-input" id="height" name="height" value="{{ $animal->height }}" >
                            @else
                                <input type="number" class="form-control col custom-input" id="height" name="height" disabled>
                            @endif
                        </div>
                        <div class="col">
                            <label for="born" class="col-form-label">Born</label>
                            @if(isset($animal))
                                <input type="date" class="form-control col custom-input" id="born" name="born" value="{{ $animal->born }}" >
                            @else
                                <input type="date" class="form-control col custom-input" id="born" name="born" disabled>
                            @endif
                        </div>
                        <div class="col">
                            <label for="condition" class="col-form-label">Condition</label>
                            @if(isset($animal))
                                <input type="text" class="form-control col custom-input" id="condition" name="condition" value="{{ $animal->condition }}" >
                            @else
                                <input type="text" class="form-control col custom-input" id="condition" name="condition" disabled>
                            @endif
                        </div>
                        <div class="col">
                            <label for="gender" class="col-form-label">Gender</label>
                            @if(isset($animal))
                                <input type="text" class="form-control col custom-input" id="gender" name="gender" value="{{ $animal->gender }}" >
                            @else
                                <input type="text" class="form-control col custom-input" id="gender" name="gender" disabled>
                            @endif
                        </div>
                        <label for="warehouse_select_form">Warehouse</label>
                        <select id="warehouse_id" name="warehouse_id">
                            @foreach($warehouses as $warehouse)
                                <option value="{{ $warehouse->id }}" {{ $animal->warehouse_id == $warehouse->id ? 'selected' : '' }}>{{ $warehouse->warehouse }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                    <div class="item-container mt-3">
                        <div class="dorpdown mt-3">
                            <button class="btn btn-secondary dropdown-toggle w-100" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                Medical Treatments
                            </button>
                            <div class="dropdown-menu w-100" id="dropdownMenu" aria-labelledby="dropdownMenuButton">
                                <div class="card p-3">
                                    <h5 class="card-title">Medical Treatments</h5>
                                    <button type="button" class="btn btn-secondary custom-btn" id="add_medical_treatment">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                             fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd"
                                                  d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2"></path>
                                        </svg>
                                    </button>
                                    <hr>
                                    <table class="medical_treatment_table" id="medical_treatment_table">
                                        <thead>
                                        <tr>
                                            <th></th>
                                            <th>Medical Treat Id</th>
                                            <th>Medical Treat No</th>
                                            <th>Note</th>
                                            <th>Start</th>
                                            <th>End</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(isset($animal->medical_treatments))
                                            @foreach($animal->medical_treatments as $medical_treatment)
                                                <tr>
                                                    <td>
                                                        <div class="dropdown">
                                                            <button class="btn btn-secondary dropdown-toggle no-caret" type="button"
                                                                    id="dropdownMenuButton"
                                                                    data-$medical_treatment-id="{{ $medical_treatment->id }}"
                                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                                     fill="currentColor" class="bi bi-three-dots-vertical"
                                                                     viewBox="0 0 16 16">
                                                                    <path
                                                                        d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"></path>
                                                                </svg>
                                                            </button>
                                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                                <li><p class="dropdown-item modify_medical_treatment" id="modify_medical_treatment"
                                                                       data-medical_treatment-id="{{ $medical_treatment->id }}">Modify</p></li>
                                                                <li><p class="dropdown-item delete_medical_treatment" id="delete_medical_treatment"
                                                                       data-medical_treatment-id="{{ $medical_treatment->id }}">Delete</p></li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                    <td><input type="text" class="form-control" name="medical_treatment_id"
                                                               value="{{ $medical_treatment->id }}" disabled></td>
                                                    <td>
                                                        <select class="form-control" name="medical_examination_id">
                                                            @foreach($medical_examination_nos as $medical_examination_no)
                                                                <option value="{{ $medical_examination_no->id }}" {{ $medical_examination_no->id == $medical_treatment->medical_examination_id ? 'selected' : '' }}>{{ $medical_examination_no->id . ' : ' .  $medical_examination_no->medical_examination }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td><input type="text" class="form-control" name="note"
                                                               value="{{ $medical_treatment->note }}" ></td>
                                                    <td><input type="date" class="form-control" name="start"
                                                               value="{{ $medical_treatment->start }}" disabled></td>
                                                    <td><input type="date" class="form-control" name="end"
                                                               value="{{ $medical_treatment->end }}" disabled></td>
                                                </tr>
                                            @endforeach
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
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

    <div id="medical_treatment_dialog" class="dialog" style="display: none;">
        <form id="medical_treatment_form">
            @csrf
            <label>
                <select id="medical_examination_select" name="animal"></select>
            </label><br>
            <label>
                <input type="text" name="note" id="new_note" placeholder="Note">
            </label><br>
            <label>
                <input type="date" name="start" id="new_start" placeholder="Start">
            </label><br>
            <label>
                <input type="date" name="end" id="new_end" placeholder="End">
            </label><br>

            <button type="button" id="new_medical_treatment">New</button>
            <button type="button" id="cancel_medical_treatment">Cancel</button>

        </form>
    </div>

    <script src="{{ asset('js/manage_animal.js')}}"></script>
    <meta name="csrf-token" content="tu_je_vasho_csrf_tokenu">

@endsection
