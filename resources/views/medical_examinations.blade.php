@extends('layout.navigator_intra')
@section('content')

    <link href="{{ asset('css/styles_table.css') }}" rel="stylesheet">
    <div class="container">
        <div class="card shadow">
            <div class="card-body">
                <h1 class="card-title">Medical Examinations</h1>
                <button type="button" class="btn btn-secondary custom-btn" id="add_new_medical_examination">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                         class="bi bi-plus-lg" viewBox="0 0 16 16">
                        <path fill-rule="evenodd"
                              d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2"></path>
                    </svg>
                </button>
                <button type="button" class="btn btn-secondary" id="search_medical_examinations">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                         class="bi bi-search" viewBox="0 0 16 16">
                        <path
                            d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"></path>
                    </svg>
                </button>
                <hr>
                <div id="search_inputs">

                </div>
                @if(isset($medical_examinations))
                    <div class="card p-3">
                        <table class="medical_examination_table" id="medical_examination_table">
                            <thead>
                            <tr>
                                <th></th>
                                <th>Medical Exam Id</th>
                                <th>Medical Exam No</th>
                                <th>Description</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(isset($medical_examinations))
                                @foreach($medical_examinations as $medical_examination)
                                    <tr>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-secondary dropdown-toggle no-caret" type="button"
                                                        id="dropdownMenuButton"
                                                        data-medical-id="{{ $medical_examination->id }}"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                         fill="currentColor" class="bi bi-three-dots-vertical"
                                                         viewBox="0 0 16 16">
                                                        <path
                                                            d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"></path>
                                                    </svg>
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <li><p class="dropdown-item modify_medical_examination" id="modify_medical_examination"
                                                           data-medical_examination-id="{{ $medical_examination->id }}">Modify</p></li>
                                                    <li><p class="dropdown-item delete_medical_examination" id="delete_medical_examination"
                                                           data-medical_examination-id="{{ $medical_examination->id }}">Delete</p></li>
                                                </ul>
                                            </div>
                                        </td>
                                        <td><input type="text" class="form-control" name="medical_examination_id"
                                                   value="{{ $medical_examination->id }}" disabled></td>
                                        <td><input type="text" class="form-control" name="medical_examination"
                                                   value="{{ $medical_examination->medical_examination}}"></td>
                                        <td><input type="text" class="form-control" name="description"
                                                   value="{{ $medical_examination->description }}"></td>
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

    <div id="medical_examination_dialog" class="dialog" style="display: none;">
        <form id="medical_examination_form">
            @csrf
            <label>
                <input type="text" name="medical" id="new_medical_examination_no" placeholder="Medical Exam No">
            </label><br>
            <label>
                <input type="text" name="description" id="new_description" placeholder="Description">
            </label><br>

            <button type="button" id="new_medical_examination">New</button>
            <button type="button" id="cancel_medical_examination">Cancel</button>

        </form>
    </div>

    <script src="{{ asset('js/manage_medical_examinations.js')}}"></script>
    <meta name="csrf-token" content="tu_je_vasho_csrf_tokenu">

@endsection
