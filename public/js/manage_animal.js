$(document).ready(function() {

    $('#add_new_animal').click(function() {
        var dialog = document.getElementById('animal_dialog');
        dialog.style.display = 'block';
    });
});

document.getElementById('cancel_animal').addEventListener('click', function() {
    var dialog = document.getElementById('animal_dialog');
    dialog.style.display = 'none';
});

$(document).ready(function() {
    $('#medical_examination_select').select2({
        ajax: {
            url: '/select_medical_examinations',
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    search_term: params.term,
                };
            },
            processResults: function(response) {
                return {
                    results: response.medical_examinations.map(function(medical_examination) {
                        return {id: medical_examination.id, text: medical_examination.medical_examination};
                    })
                };
            },
            cache: true
        },
        placeholder: 'Select a medical examination',
        minimumInputLength: 1,
        minimumResultsForSearch: 0,
        width: '100%',
    });
});

document.getElementById('new_animal').addEventListener('click', function() {
    var dialog = document.getElementById('animal_dialog');

    var par_animal_no = $('#animal_no_select').val();
    var par_warehouse_id = $('#warehouse_select').val();

    var par_weight = document.getElementById('new_weight').value;
    var par_height = document.getElementById('new_height').value;
    var par_born = document.getElementById('new_born').value;
    var par_condition = document.getElementById('new_condition').value;
    var par_gender = document.getElementById('new_gender').value;


    $.ajax({
        type: 'post',
        url: '/add_animal',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data : {
            animal_number_id: par_animal_no,
            weight: par_weight,
            height: par_height,
            born: par_born,
            condition: par_condition,
            gender: par_gender,
            warehouse_id: par_warehouse_id,
        },
        success: function (response) {
            dialog.style.display = 'none';
            console.log(response.animal);
            window.location.replace('/animal/' + response.animal.id);
        },
        error: function (error) {
            console.error('Error adding warehouse:', error);
        }
    });
});

$(document).ready(function() {
    $('#modify_animal').click(function() {
        var par_animal_id = document.getElementById('animal_id').value;
        var par_warehouse_id = document.getElementById('warehouse_id').value;
        var par_animal_number_id = document.getElementById('animal_no_id').value;
        var par_weight = document.getElementById('weight').value;
        var par_height = document.getElementById('height').value;
        var par_born = document.getElementById('born').value;
        var par_condition = document.getElementById('condition').value;
        var par_gender = document.getElementById('gender').value;

        $.ajax({
            type: 'post',
            url: '/modify_animal',
            method: 'post',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            data: {
                animal_id: par_animal_id,
                animal_number_id: par_animal_number_id,
                weight: par_weight,
                height: par_height,
                born: par_born,
                condition: par_condition,
                gender: par_gender,
                warehouse_id: par_warehouse_id,
            },
            success: function (response) {
                console.log(response.message);
            },
            error: function (response) {
                console.error('Error saving order data:');
            }
        });

    });
});

$('#delete_animal').on('click', function(e) {
    e.stopPropagation();

    var par_animal_id = document.getElementById('animal_id').value;

    $.ajax({
        url: '/delete_animal',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        data: {
            animal_id: par_animal_id,
        },
        success: function (response) {
            console.log(response.message);
            window.location.replace('/animals');
        },
        error: function (response) {
            console.error('Error deleting product data:');
        }
    })
});

$('#add_medical_treatment').click(function() {
    var dialog = document.getElementById('medical_treatment_dialog');
    dialog.style.display = 'block';
});

document.getElementById('cancel_medical_treatment').addEventListener('click', function() {
    var dialog = document.getElementById('medical_treatment_dialog');
    dialog.style.display = 'none';
});

document.getElementById('new_medical_treatment').addEventListener('click', function() {
    var dialog = document.getElementById('medical_treatment_dialog');

    var par_medical_examination_id = $('#medical_examination_select').val();
    var par_note = document.getElementById('new_note').value;
    var par_animal_id = document.getElementById('animal_id').value;
    var par_start = document.getElementById('new_start').value;
    var par_end = document.getElementById('new_end').value;

    console.log(par_animal_id);

    $.ajax({
        type: 'post',
        url: '/add_medical_treatment',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data : {
            medical_examination_id : par_medical_examination_id,
            note: par_note,
            start:par_start,
            end: par_end,
            animal_id: par_animal_id,
        },
        success: function (response) {
            dialog.style.display = 'none';
            console.log(response.medical_treatment);
            var medical_treatment = response.medical_treatment;
            var medical_examinations = response.medical_examinations;
            var new_row = `
               <tr>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle no-caret" type="button"
                                    id="dropdownMenuButton"
                                    data-$medical_treatment-id="${medical_treatment.id}"
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
                                       data-medical_treatment-id="${medical_treatment.id}">Modify</p></li>
                                <li><p class="dropdown-item delete_medical_treatment" id="delete_medical_treatment"
                                       data-medical_treatment-id="${medical_treatment.id}">Delete</p></li>
                            </ul>
                        </div>
                    </td>
                    <td><input type="text" class="form-control" name="medical_treatment_id"
                               value="${medical_treatment.id}" disabled></td>
                    <td>${createMedicalExaminationSelect(medical_examinations, medical_treatment.medical_examination_id)}</td>
                    <td><input type="text" class="form-control" name="note"
                               value="${medical_treatment.note}" ></td>
                    <td><input type="date" class="form-control" name="start"
                               value="${medical_treatment.start}" disabled></td>
                    <td><input type="date" class="form-control" name="end"
                               value="${medical_treatment.end}" disabled></td>
                </tr>`;
            $('.medical_treatment_table tbody').append(new_row);
        },
        error: function(xhr, status, error) {
            console.error('Error saving Order data:')
            console.log(par_animal_id);
        }
    });
});

function createMedicalExaminationSelect(medical_examinations, selectedId) {
    var optionsHtml = medical_examinations.map(function(medical_examination) {
        var isSelected = medical_examination.id === selectedId ? 'selected' : '';
        return `<option value="${medical_examination.id}" ${isSelected}>${medical_examination.id} : ${medical_examination.medical_examination}</option>`;
    }).join('');

    return `<select class="form-control" name="medical_examination_id">${optionsHtml}</select>`;
}

$(document).ready(function() {
    //$(document).on('click', '.dropdown-item.delete_item', function(e) {
    $('.dropdown-item.delete_medical_treatment').on('click', function(e) {
        e.stopPropagation();
        e.preventDefault();

        var $this = $(this);
        var par_medical_treatment_id = $(this).data('medical_treatment-id');

        $.ajax({
            url: '/delete_medical_treatment',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            data: {
                medical_treatment: par_medical_treatment_id,
            },
            success: function (response) {
                console.log(response.message);
                $this.closest('tr').fadeOut(500, function() {
                    $(this).remove();
                });
            },
            error: function (response) {
                console.error('Error deleting employee data:');
            }
        })
    });
});

$('.dropdown-item.modify_medical_treatment').on('click', function(e) {

    e.stopPropagation();
    e.preventDefault();

    var par_medical_treatment = $(this).data('medical_treatment-id');

    var $row = $(this).closest('tr');

    var par_medical_examination_id =  $row.find('select[name="medical_examination_id"]').val();
    var par_note =  $row.find('input[name="note"]').val();
    var par_start =  $row.find('input[name="start"]').val();
    var par_end =  $row.find('input[name="end"]').val();

    $.ajax({
        type: 'post',
        url: '/modify_medical_treatment',
        method: 'post',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        data: {
            medical_treatment_id: par_medical_treatment,
            medical_examination_id: par_medical_examination_id,
            note: par_note,
            start: par_start,
            end: par_end,
        },
        success: function (response) {
            console.log('Medical treatment modified sucessfully');
        },
        error: function (response) {
            console.error('Error modifying item data:');
        }
    })
});

$(document).ready(function() {
    $('#warehouse_select').select2({
        ajax: {
            url: '/select_warehouses',
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    search_term: params.term,
                };
            },
            processResults: function(response) {
                return {
                    results: response.warehouses.map(function(warehouse) {
                        return {id: warehouse.id, text: warehouse.warehouse};
                    })
                };
            },
            cache: true
        },
        placeholder: 'Select warehouse',
        minimumInputLength: 1,
        minimumResultsForSearch: 0,
        width: '100%',
    });
});

$(document).ready(function() {
    $('#animal_no_select').select2({
        ajax: {
            url: '/select_animal_nos',
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    search_term: params.term,
                };
            },
            processResults: function(response) {
                return {
                    results: response.animal_nos.map(function(animal_no) {
                        return {id: animal_no.id, text: animal_no.animal_number};
                    })
                };
            },
            cache: true
        },
        placeholder: 'Select animal no',
        minimumInputLength: 1,
        minimumResultsForSearch: 0,
        width: '100%',
    });
});

$(document).ready(function() {
    $('.dropdown-menu').on('click', function(event) {
        event.stopPropagation();
    });

    $('.dropdown-item').on('click', function() {
        $(this).closest('.dropdown').find('[data-bs-toggle="dropdown"]').dropdown('hide');
    });
});
