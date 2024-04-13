$(document).ready(function() {

    $('#add_new_animal_number').click(function() {
        var dialog = document.getElementById('animal_number_dialog');
        dialog.style.display = 'block';
    });
});

document.getElementById('cancel_animal_number').addEventListener('click', function() {
    var dialog = document.getElementById('animal_number_dialog');
    dialog.style.display = 'none';
});

document.getElementById('new_animal_number').addEventListener('click', function() {
    var dialog = document.getElementById('animal_number_dialog');
    var errors = [];
    var par_animal_no = document.getElementById('new_animal_number_no').value;
    var par_description = document.getElementById('new_description').value;

    if (!par_animal_no) errors.push("Animal number is required.");
    if (!par_description) errors.push("Description is required.");

    if (errors.length > 0) {
        alert("Please fill out all fields.\n" + errors.join("\n"));
        return;
    }

    $.ajax({
        type: 'post',
        url: '/add_animal_number',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data : {
            animal_number: par_animal_no,
            description: par_description,
        },
        success: function (response) {
            dialog.style.display = 'none';
            console.log(response.animal_number);
            var animal_number = response.animal_number;
            var new_row = `
                <tr>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle no-caret" type="button"
                                    id="dropdownMenuButton" data-animal_number-id="${animal_number.id}"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                     fill="currentColor" class="bi bi-three-dots-vertical"
                                     viewBox="0 0 16 16">
                                    <path
                                        d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"></path>
                                </svg>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <li><p class="dropdown-item modify_animal_number" id="modify_animal_number"
                                       data-animal_number-id="${animal_number.id}">Modify</p></li>
                                <li><p class="dropdown-item delete_animal_number" id="delete_animal_number"
                                       data-animal_number-id="${animal_number.id}">Delete</p></li>
                            </ul>
                        </div>
                    </td>
                    <td><input type="text" class="form-control" name="animal_number_id"
                                                   value="${animal_number.id}" disabled></td>
                                        <td><input type="text" class="form-control" name="animal_number"
                                                   value="${animal_number.animal_number}"></td>
                                        <td><input type="text" class="form-control" name="description"
                                                   value="${animal_number.description}"></td>
                </tr>`;
            $('.animal_number_table tbody').append(new_row);
        },
        error: function (error) {
            console.error('Error adding animal number:', error);
            console.log(par_animal_no, par_description);
        }
    });
});

//$('.dropdown-item.delete_animal_number').on('click', function(e) {
$(document).on('click', '.dropdown-item.delete_animal_number', function(e) {
    e.stopPropagation();

    var $this = $(this);
    var par_animal_number_id = $(this).data('animal_number-id');

    $.ajax({
        url: '/delete_animal_number',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        data: {
            animal_number_id: par_animal_number_id,
        },
        success: function (response) {
            console.log(response.message);
            $this.closest('tr').fadeOut(500, function() {
                $(this).remove();
            });
        },
        error: function (response) {
            console.error('Error deleting animal number data:');
        }
    })
});

//$('.dropdown-item.modify_animal_number').on('click', function(e) {
$(document).on('click', '.dropdown-item.modify_animal_number', function(e) {

    e.stopPropagation();

    var par_animal_number_id = $(this).data('animal_number-id');

    var $row = $(this).closest('tr');

    var par_animal_no = $row.find('input[name="animal_number"]').val();
    var par_descriptoin = $row.find('input[name="description"]').val();

    $.ajax({
        type: 'post',
        url: '/modify_animal_number',
        method: 'post',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        data: {
            animal_number_id: par_animal_number_id,
            animal_number: par_animal_no,
            description: par_descriptoin,
        },
        success: function (response) {
            console.log(response.message);
        },
        error: function (response) {
            console.error('Error modifying animal number data:');
        }
    })
});

$(document).ready(function() {
    $('#search_animal_numbers').click(function() {
        var search = $('#search_inputs');
        var inputs = '<div class="form-group"><input type="text" id="search_id" name="search_id" class="form-control" placeholder="animal number id" /></div>\n' +
            '<div class="form-group"><input type="text" id="search_animal_number" name="search_animal_number" class="form-control" placeholder="animal number"/></div>\n' +
            '<div class="form-group"><input type="text" id="search_description" name="search_description" class="form-control" placeholder="description"/></div>\n' +
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
        var par_animal_number = $('#search_animal_number').val();
        var par_description = $('#search_description').val();

        $.ajax({
            url: '/search_animal_numbers',
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                animal_number_id: par_id,
                animal_number: par_animal_number,
                description: par_description,

            },
            success: function(response) {
                console.log(response.message);
                var animal_numbers = response.animal_numbers;
                $('.card.p-3').remove();

                var animal_numberHtml = '<div class="card p-3">' +
                    '<table class="animal_number_table" id="animal_number_table">' +
                    '<thead>' +
                    '<tr>'+
                    '<th></th>'+
                    ' <th>Animal No Id</th>'+
                    '<th>Animal No</th>'+
                    '<th>Description</th>'+
                    '</tr>' +
                    '</thead>' +
                    '<tbody>';

                animal_numbers.forEach(function(animal_number) {
                    animal_numberHtml += `<tr>
                        <td>
                        <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle no-caret" type="button"
                    id="dropdownMenuButton" data-animal_number-id="${ animal_number.id }"
                    data-bs-toggle="dropdown" aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                    fill="currentColor" class="bi bi-three-dots-vertical"
                    viewBox="0 0 16 16">
                        <path
                    d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"></path>
                </svg>
                </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <li><a href="/animal_number/${ animal_number.id }"
                               class="dropdown-item detail_animal_number" id="detail_animal_number"
                               data-animal_number-id="${ animal_number.id }">Detail</a></li>
                        <li><p class="dropdown-item modify_animal_number" id="modify_animal_number"
                               data-animal_number-id="${ animal_number.id }">Modify</p></li>
                        <li><p class="dropdown-item delete_animal_number" id="delete_animal_number"
                               data-animal_number-id="${ animal_number.id }">Delete</p></li>
                    </ul>
                </div>
                <td><input type="text" class="form-control" name="animal_number_id"
                                                   value="${animal_number.id}" disabled></td>
                                        <td><input type="text" class="form-control" name="animal_number"
                                                   value="${animal_number.animal_number}"></td>
                                        <td><input type="text" class="form-control" name="description"
                                                   value="${animal_number.description}"></td>

                </tr>`;
                });

                animal_numberHtml +=       '</tbody>' +
                    '</table>' +
                    '</div>';

                $('#search_inputs').after(animal_numberHtml);
            },
            error: function(error) {
                console.error('Error searching animal_numbers:', error);
            }
        });
    });
});
