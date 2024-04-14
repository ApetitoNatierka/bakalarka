$(document).ready(function() {

    $('#add_new_animals').click(function() {
        var dialog = document.getElementById('animal_dialog');
        dialog.style.display = 'block';
    });
});

document.getElementById('cancel_animal').addEventListener('click', function() {
    var dialog = document.getElementById('animal_dialog');
    dialog.style.display = 'none';
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

document.getElementById('new_animal').addEventListener('click', function() {
    var dialog = document.getElementById('animal_dialog');

    var errors = [];

    var par_animal_no = $('#animal_no_select').val();
    var par_warehouse_id = $('#warehouse_select').val();

    var par_weight = document.getElementById('new_weight').value;
    var par_height = document.getElementById('new_height').value;
    var par_born = document.getElementById('new_born').value;
    var par_condition = document.getElementById('new_condition').value;
    var par_gender = document.getElementById('new_gender').value;

    if (!par_animal_no) errors.push("Animal number is required.");
    if (!par_warehouse_id) errors.push("Warehouse ID is required.");
    if (!par_weight) errors.push("Weight is required.");
    if (!par_height) errors.push("Height is required.");
    if (!par_born) errors.push("Date of birth is required.");
    if (!par_condition) errors.push("Condition is required.");
    if (!par_gender) errors.push("Gender is required.");

    if (errors.length > 0) {
        alert("Please fill out all fields.\n" + errors.join("\n"));
        return;
    }

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
            console.log(response.animal_nos);
            var animal = response.animal;
            var animal_nos = response.animal_nos;
            var warehouses = response.warehouses;
            var new_row = `
                <tr>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle no-caret" type="button"
                                    id="dropdownMenuButton" data-animal-id="${animal.id}"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                     fill="currentColor" class="bi bi-three-dots-vertical"
                                     viewBox="0 0 16 16">
                                    <path
                                        d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"></path>
                                </svg>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <li><a href="/animal/${animal.id}"
                                       class="dropdown-item detail_animal" id="detail_animal"
                                       data-animal-id="${animal.id}">Detail</a></li>
                                <li> <p class="dropdown-item offer_animal" id="offer_animal"
                                       data-animal-id="${animal.id}">Offer</p></li>
                                <li><p class="dropdown-item modify_animal" id="modify_animal"
                                       data-animal-id="${animal.id}">Modify</p></li>
                                <li><p class="dropdown-item delete_animal" id="delete_animal"
                                       data-animal-id="${animal.id}">Delete</p></li>
                            </ul>
                        </div>
                    </td>
                                        <td><input type="number" class="form-control" name="animal_id"
                                                   value="${animal.id}" disabled></td>
                                        <td>${createAnimalNosSelect(animal_nos, animal.animal_number_id)}</td>
                                        <td><input type="number" class="form-control" name="weight"
                                                   value="${animal.weight}"></td>
                                        <td><input type="number" class="form-control" name="height"
                                                   value="${animal.height}"></td>
                                        <td><input type="date" class="form-control" name="born"
                                                   value="${animal.born}"></td>
                                        <td><input type="text" class="form-control" name="condition"
                                                   value="${animal.condition}"></td>
                                       <td><input type="text" class="form-control" name="gender"
                                                   value="${animal.gender}"></td>
                                       <td>${createwarehousesSelect(warehouses, animal.warehouse_id)}</td>
                </tr>`;
            $('.animals_table tbody').append(new_row);
            console.log(animal.warehouse_id)
        },
        error: function (error) {
            console.error('Error adding animal:', error);
        }
    });
});

$(document).on('click', '.dropdown-item.delete_animal', function(e) {
    e.stopPropagation();

    var $this = $(this);
    var par_animal_id = $(this).data('animal-id');

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
            console.log(response.item);
            $this.closest('tr').fadeOut(500, function() {
                $(this).remove();
            });
        },
        error: function (response) {
            console.error('Error deleting animal data:');
        }
    })
});


$(document).on('click', '.dropdown-item.modify_animal', function(e) {
    e.stopPropagation();

    var par_animal_id = $(this).data('animal-id');

    var $row = $(this).closest('tr');
    var par_warehouse_id = $row.find('select[name="warehouse_id"]').val();

    var par_animal_number_id = $row.find('select[name="animal_no_id"]').val();
    var par_weight = $row.find('input[name="weight"]').val();
    var par_height = $row.find('input[name="height"]').val();
    var par_born = $row.find('input[name="born"]').val();
    var par_condition = $row.find('input[name="condition"]').val();
    var par_gender = $row.find('input[name="gender"]').val();

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
            console.log(par_warehouse_id);
        },
        error: function (response) {
            console.error('Error modifying animal data:');
        }
    })
});

$(document).ready(function() {
    $('#search_animals').click(function() {
        var search = $('#search_inputs');
        var inputs = '<div class="form-group"><input type="text" id="search_id" name="search_id" class="form-control" placeholder="animal id" /></div>\n' +
            '<div class="form-group"><input type="text" id="search_animal_number" name="search_animal_number" class="form-control" placeholder="animal number"/></div>\n' +
            '<div class="form-group"><input type="number" id="search_weight" name="search_weight" class="form-control" placeholder="weight"/></div>\n' +
            '<div class="form-group"><input type="number" id="search_height" name="search_height" class="form-control" placeholder="height"/></div>\n' +
            '<div class="form-group"><input type="date" id="search_born_from" name="search_born_from" class="form-control" placeholder="born from"/></div>\n' +
            '<div class="form-group"><input type="date" id="search_born_to" name="search_born_to" class="form-control" placeholder="born to"/></div>\n' +
            '<div class="form-group"><input type="text" id="search_condition" name="search_condition" class="form-control" placeholder="condition"/></div>\n' +
            '<div class="form-group"><input type="text" id="search_gender" name="search_gender" class="form-control" placeholder="gender"/></div>\n' +
            '<div class="form-group"><input type="text" id="search_warehouse" name="search_warehouse" class="form-control" placeholder="warehouse"/></div>\n' +
            '<div class="form-group"><button id="search_button" class="btn btn-primary" style="border-radius: 5px">Search</button></div>';

        if (search.is(':empty')) {
            search.append(inputs)
        } else {
            search.empty()
        }
    });
});

$(document).ready(function() {
    $(document).on('click', '#search_button', function() {
        var par_id = $('#search_id').val();
        var par_animal_number_id = $('#search_animal_number').val();
        var par_weight = $('#search_weight').val();
        var par_height = $('#search_height').val();
        var par_born_to = $('#search_born_to').val();
        var par_born_from = $('#search_born_from').val();
        var par_condition = $('#search_condition').val();
        var par_gender = $('#search_gender').val();
        var par_warehouse = $('#search_warehouse').val();

        $.ajax({
            url: '/search_animals',
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                animal_id: par_id,
                animal_number_id: par_animal_number_id,
                weight: par_weight,
                height:par_height,
                born_to: par_born_to,
                born_from: par_born_from,
                condition: par_condition,
                gender: par_gender,
                warehouse: par_warehouse,
            },
            success: function(response) {
                console.log(response.message);
                var animals = response.animals;
                var animal_nos = response.animal_nos;
                var warehouses = response.warehouses;
                $('.card.p-3').remove();

                var animalHtml = '<div class="card p-3">' +
                    '<table class="warehouse_table" id="warehouse_table">' +
                    '<thead>' +
                    '<tr>'+
                    '<th></th>'+
                    ' <th>Animal Id</th>'+
                    '<th>Animal No</th>'+
                    '<th>Weight</th>'+
                    '<th>Height</th>'+
                    '<th>Born</th>'+
                    '<th>Condition</th>'+
                    '<th>Gender</th>'+
                    '<th>Warehouse</th>'+
                    '</tr>' +
                    '</thead>' +
                    '<tbody>';

                animals.forEach(function(animal) {
                    animalHtml += `<tr>
                        <td>
                        <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle no-caret" type="button"
                    id="dropdownMenuButton" data-animal-id="${ animal.id }"
                    data-bs-toggle="dropdown" aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                    fill="currentColor" class="bi bi-three-dots-vertical"
                    viewBox="0 0 16 16">
                        <path
                    d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"></path>
                </svg>
                </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <li><a href="/animal/${ animal.id }"
                               class="dropdown-item detail_animal" id="detail_animal"
                               data-animal-id="${ animal.id }">Detail</a></li>
                        <li> <p class="dropdown-item offer_animal" id="offer_animal"
                                                            data-animal-id="${animal.id}">Offer</p></li>
                        <li><p class="dropdown-item modify_animal" id="modify_animal"
                               data-animal-id="${ animal.id }">Modify</p></li>
                        <li><p class="dropdown-item delete_animal" id="delete_animal"
                               data-animal-id="${ animal.id }">Delete</p></li>
                    </ul>
                </div>
                <td><input type="number" class="form-control" name="animal_id"
                                                   value="${animal.id}" disabled></td>
                                        <td>${createAnimalNosSelect(animal_nos, animal.animal_number_id)}</td>
                                        <td><input type="number" class="form-control" name="weight"
                                                   value="${animal.weight}"></td>
                                        <td><input type="number" class="form-control" name="height"
                                                   value="${animal.height}"></td>
                                        <td><input type="date" class="form-control" name="born"
                                                   value="${animal.born}"></td>
                                        <td><input type="text" class="form-control" name="condition"
                                                   value="${animal.condition}"></td>
                                       <td><input type="text" class="form-control" name="gender"
                                                   value="${animal.gender}"></td>
                                       <td>${createwarehousesSelect(warehouses, animal.warehouse_id)}</td>
                </tr>`;
                });

                animalHtml +=       '</tbody>' +
                    '</table>' +
                    '</div>';

                $('#search_inputs').after(animalHtml);
            },
            error: function(error) {
                console.error('Error searching animals:', error);
                console.log(par_born_from, par_born_to);
            }
        });
    });
});

function createAnimalNosSelect(animal_nos, selectedId) {
    var optionsHtml = animal_nos.map(function(animal_no) {
        var isSelected = animal_no.id === selectedId ? 'selected' : '';
        return `<option value="${animal_no.id}" ${isSelected}>${animal_no.id} : ${animal_no.animal_number}</option>`;
    }).join('');

    return `<select class="form-control" name="animal_no_id">${optionsHtml}</select>`;
}

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

function createwarehousesSelect(warehouses, selectedId) {
    var optionsHtml = warehouses.map(function(warehouse) {
        var isSelected = warehouse.id === selectedId ? 'selected' : '';
        return `<option value="${warehouse.id}" ${isSelected}>${warehouse.id} : ${warehouse.warehouse}</option>`;
    }).join('');

    return `<select class="form-control" name="warehouse_id">${optionsHtml}</select>`;
}


document.getElementById('offer').addEventListener('click', function() {
    var dialog = document.getElementById('price_dialog');
    var par_animal_id = dialog.getAttribute('data-animal-id');
    var par_price = document.getElementById('new_price').value;

    console.log("Sending animal ID:", par_animal_id);
    console.log("Sending price:", par_price);

    $.ajax({
        url: '/offer_animal',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        data: {
            animal_id: par_animal_id,
            price: par_price,
        },
        success: function (response) {
            console.log(response.message);
            alert('Animal offered sucessfully');
        },
        error: function(xhr) {
            console.error("Error:", xhr.responseText);
            alert('Error: ' + xhr.responseJSON.error + "\nMessage: " + xhr.responseJSON.message);
        }
    });
    dialog.style.display = 'none';
});

document.getElementById('cancel_offer').addEventListener('click', function() {
    var dialog = document.getElementById('price_dialog');
    dialog.style.display = 'none';
});

$(document).on('click', '.dropdown-item.offer_animal', function(e) {
    e.stopPropagation();

    var $this = $(this);
    var par_animal_id = $(this).data('animal-id');
    var dialog = document.getElementById('price_dialog');
    dialog.setAttribute('data-animal-id', par_animal_id);
    dialog.style.display = 'block';

});


document.addEventListener('DOMContentLoaded', function () {
    var statsModal = document.getElementById('statsModal');
    statsModal.addEventListener('show.bs.modal', function () {
        fetch('/get-examination-stats')
            .then(response => response.json())
            .then(data => {
                var ctx = document.getElementById('examinationsChart').getContext('2d');
                var chart = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: data.map(item => item.name),
                        datasets: [{
                            label: 'Medical Examination Usage',
                            data: data.map(item => item.percentage),
                            backgroundColor: [
                                'red', 'blue', 'green', 'yellow', 'orange', 'purple', 'cyan'
                            ],
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                        }
                    }
                });
            })
            .catch(error => console.error('Error loading the data:', error));
    });
});
