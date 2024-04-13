$(document).ready(function() {

    $('#add_new_supply_number').click(function() {
        var dialog = document.getElementById('supply_number_dialog');
        dialog.style.display = 'block';
    });
});

document.getElementById('cancel_supply_number').addEventListener('click', function() {
    var dialog = document.getElementById('supply_number_dialog');
    dialog.style.display = 'none';
});

document.getElementById('new_supply_number').addEventListener('click', function() {
    var dialog = document.getElementById('supply_number_dialog');

    var par_supply_no = document.getElementById('new_supply_number_no').value;
    var par_description = document.getElementById('new_description').value;

    var errors = [];

    if (!par_supply_no) errors.push("Supply number is required.");
    if (!par_description) errors.push("Description is required.");


    if (errors.length > 0) {
        alert("Please fill out all fields.\n" + errors.join("\n"));
        return;
    }

    $.ajax({
        type: 'post',
        url: '/add_supply_number',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            supply_number: par_supply_no,
            description: par_description,
        },
        success: function(response) {
            dialog.style.display = 'none';
            console.log(response.supply_number);
            var supply_number = response.supply_number;
            var new_row = `
                <tr>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle no-caret" type="button"
                                    id="dropdownMenuButton" data-supply_number-id="${supply_number.id}"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                     fill="currentColor" class="bi bi-three-dots-vertical"
                                     viewBox="0 0 16 16">
                                    <path
                                        d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"></path>
                                </svg>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <li><p class="dropdown-item modify_supply_number" id="modify_supply_number"
                                       data-supply_number-id="${supply_number.id}">Modify</p></li>
                                <li><p class="dropdown-item delete_supply_number" id="delete_supply_number"
                                       data-supply_number-id="${supply_number.id}">Delete</p></li>
                            </ul>
                        </div>
                    </td>
                    <td><input type="text" class="form-control" name="supply_number_id"
                                                   value="${supply_number.id}" disabled></td>
                    <td><input type="text" class="form-control" name="supply_number"
                                                   value="${supply_number.supply_number}"></td>
                    <td><input type="text" class="form-control" name="description"
                                                   value="${supply_number.description}"></td>
                </tr>`;
            $('.supply_number_table tbody').append(new_row);
        },
        error: function(error) {
            console.error('Error adding supply number:', error);
            console.log(par_supply_no, par_description);
        }
    });
});

$(document).on('click', '.dropdown-item.delete_supply_number', function(e) {
    e.stopPropagation();

    var $this = $(this);
    var par_supply_number_id = $(this).data('supply_number-id');

    $.ajax({
        url: '/delete_supply_number',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        data: {
            supply_number_id: par_supply_number_id,
        },
        success: function(response) {
            console.log(response.message);
            $this.closest('tr').fadeOut(500, function() {
                $(this).remove();
            });
        },
        error: function(response) {
            console.error('Error deleting supply number data:');
        }
    })
});

$(document).on('click', '.dropdown-item.modify_supply_number', function(e) {

    e.stopPropagation();

    var par_supply_number_id = $(this).data('supply_number-id');

    var $row = $(this).closest('tr');

    var par_supply_no = $row.find('input[name="supply_number"]').val();
    var par_description = $row.find('input[name="description"]').val();

    $.ajax({
        type: 'post',
        url: '/modify_supply_number',
        method: 'post',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        data: {
            supply_number_id: par_supply_number_id,
            supply_number: par_supply_no,
            description: par_description,
        },
        success: function(response) {
            console.log(response.message);
        },
        error: function(response) {
            console.error('Error modifying supply number data:');
        }
    })
});

$(document).ready(function() {
    $('#search_supply_numbers').click(function() {
        var search = $('#search_inputs');
        var inputs = '<div class="form-group"><input type="text" id="search_id" name="search_id" class="form-control" placeholder="supply number id" /></div>\n' +
            '<div class="form-group"><input type="text" id="search_supply_number" name="search_supply_number" class="form-control" placeholder="supply number"/></div>\n' +
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
        var par_supply_number = $('#search_supply_number').val();
        var par_description = $('#search_description').val();

        $.ajax({
            url: '/search_supply_numbers',
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                supply_number_id: par_id,
                supply_number: par_supply_number,
                description: par_description,

            },
            success: function(response) {
                console.log(response.message);
                var supply_numbers = response.supply_numbers;
                $('.card.p-3').remove();

                var supply_numberHtml = '<div class="card p-3">' +
                    '<table class="supply_number_table" id="supply_number_table">' +
                    '<thead>' +
                    '<tr>'+
                    '<th></th>'+
                    ' <th>Supply No Id</th>'+
                    '<th>Supply No</th>'+
                    '<th>Description</th>'+
                    '</tr>' +
                    '</thead>' +
                    '<tbody>';

                supply_numbers.forEach(function(supply_number) {
                    supply_numberHtml += `<tr>
                        <td>
                        <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle no-caret" type="button"
                    id="dropdownMenuButton" data-supply_number-id="${ supply_number.id }"
                    data-bs-toggle="dropdown" aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                    fill="currentColor" class="bi bi-three-dots-vertical"
                    viewBox="0 0 16 16">
                        <path
                    d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"></path>
                </svg>
                </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <li><a href="/supply_number/${ supply_number.id }"
                               class="dropdown-item detail_supply_number" id="detail_supply_number"
                               data-supply_number-id="${ supply_number.id }">Detail</a></li>
                        <li><p class="dropdown-item modify_supply_number" id="modify_supply_number"
                               data-supply_number-id="${ supply_number.id }">Modify</p></li>
                        <li><p class="dropdown-item delete_supply_number" id="delete_supply_number"
                               data-supply_number-id="${ supply_number.id }">Delete</p></li>
                    </ul>
                </div>
                <td><input type="text" class="form-control" name="supply_number_id"
                                                   value="${supply_number.id}" disabled></td>
                                        <td><input type="text" class="form-control" name="supply_number"
                                                   value="${supply_number.supply_number}"></td>
                                        <td><input type="text" class="form-control" name="description"
                                                   value="${supply_number.description}"></td>

                </tr>`;
                });

                supply_numberHtml +=       '</tbody>' +
                    '</table>' +
                    '</div>';

                $('#search_inputs').after(supply_numberHtml);
            },
            error: function(error) {
                console.error('Error searching supply_numbers:', error);
            }
        });
    });
});
