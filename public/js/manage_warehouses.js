$(document).ready(function() {

    $('#add_new_warehouse').click(function() {
        var dialog = document.getElementById('warehouse_dialog');
        dialog.style.display = 'block';
    });
});

document.getElementById('cancel_warehouse').addEventListener('click', function() {
    var dialog = document.getElementById('warehouse_dialog');
    dialog.style.display = 'none';
});

$(document).ready(function() {
    $('#user_select').select2({
        ajax: {
            url: '/select_users',
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    search_term: params.term,
                };
            },
            processResults: function(response) {
                return {
                    results: response.users.map(function(user) {
                        return {id: user.id, text: user.name};
                    })
                };
            },
            cache: true
        },
        placeholder: 'Select a user',
        minimumInputLength: 1,
        minimumResultsForSearch: 0,
        width: '100%',
    });
});


document.getElementById('new_warehouse').addEventListener('click', function() {
    var dialog = document.getElementById('warehouse_dialog');

    var par_user = $('#user_select').val();

    var par_warehouse = document.getElementById('new_warehouse_name').value;
    var par_capacity = document.getElementById('new_capacity').value;
    var par_location = document.getElementById('new_location').value;

    var errors = [];

    if (!par_warehouse) errors.push("Warehouse name is required.");
    if (!par_capacity) errors.push("Capacity is required.");
    if (!par_location) errors.push("Location is required.");


    if (errors.length > 0) {
        alert("Please fill out all fields.\n" + errors.join("\n"));
        return;
    }

    $.ajax({
        type: 'post',
        url: '/add_warehouse',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data : {
            user_id: par_user,
            warehouse: par_warehouse,
            capacity: par_capacity,
            location: par_location,
        },
        success: function (response) {
            dialog.style.display = 'none';
            console.log(response.warehouse);
            var warehouse = response.warehouse;
            var new_row = `
                <tr>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle no-caret" type="button"
                                    id="dropdownMenuButton" data-warehouse-id="${warehouse.id}"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                     fill="currentColor" class="bi bi-three-dots-vertical"
                                     viewBox="0 0 16 16">
                                    <path
                                        d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"></path>
                                </svg>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <li><a href="/warehouse/${warehouse.id}"
                                       class="dropdown-item detail_warehouse" id="detail_warehouse"
                                       data-warehouse-id="${warehouse.id}">Detail</a></li>
                                <li><p class="dropdown-item modify_warehouse" id="modify_warehouse"
                                       data-warehouse-id="${warehouse.id}">Modify</p></li>
                                <li><p class="dropdown-item delete_warehouse" id="delete_warehouse"
                                       data-warehouse-id="${warehouse.id}">Delete</p></li>
                            </ul>
                        </div>
                    </td>
                    <td><input type="text" class="form-control" name="warehouse_number"
                                                   value="${warehouse.id}" disabled></td>
                                        <td><input type="text" class="form-control" name="warehouse"
                                                   value="${warehouse.warehouse}"></td>
                                        <td><input type="text" class="form-control" name="location"
                                                   value="${warehouse.location}"></td>
                                        <td><input type="number" class="form-control" name="capacity"
                                                   value="${warehouse.capacity}"></td>
                                        <td><input type="text" class="form-control" name="manager"
                                                   value="${warehouse.manager_name}"></td>
                </tr>`;
            $('.warehouse_table tbody').append(new_row);
            alert(response.message);
        },
        error: function (error) {
            console.error('Error adding warehouse:', error);
            console.log(par_user,par_capacity,par_location,par_warehouse);
        }
    });
});

//$('.dropdown-item.delete_warehouse').on('click', function(e) {
$(document).on('click', '.dropdown-item.delete_warehouse', function(e) {
    e.stopPropagation();

    var $this = $(this);
    var par_warehouse_id = $(this).data('warehouse-id');

    $.ajax({
        url: '/delete_warehouse',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        data: {
            warehouse_id: par_warehouse_id,
        },
        success: function (response) {
            console.log(response.message);
            $this.closest('tr').fadeOut(500, function() {
                $(this).remove();
            });
            alert(response.message);
        },
        error: function (response) {
            console.error('Error deleting warehouse data:');
        }
    })
});

//$('.dropdown-item.modify_warehouse').on('click', function(e) {
$(document).on('click', '.dropdown-item.modify_warehouse', function(e) {
    e.stopPropagation();

    var par_warehouse_id = $(this).data('warehouse-id');

    var $row = $(this).closest('tr');

    var par_warehouse = $row.find('input[name="warehouse"]').val();
    var par_location = $row.find('input[name="location"]').val();
    var par_capacity = $row.find('input[name="capacity"]').val();
    var par_user = $row.find('input[name="manager"]').val();

    $.ajax({
        type: 'post',
        url: '/modify_warehouse',
        method: 'post',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        data: {
            warehouse_id: par_warehouse_id,
            warehouse: par_warehouse,
            capacity: par_capacity,
            location: par_location,
            user_id: par_user,
        },
        success: function (response) {
            console.log(response.message);
            alert(response.message);
        },
        error: function (response) {
            console.error('Error modifying warehouse data:');
            console.log(par_user,par_capacity,par_location,par_warehouse,par_warehouse_id);
        }
    })
});

$(document).ready(function() {
    $('#search_warehouse').click(function() {
        var search = $('#search_inputs');
        var inputs = '<div class="form-group"><input type="text" id="search_id" name="search_id" class="form-control" placeholder="warehouse number" /></div>\n' +
            '<div class="form-group"><input type="text" id="search_warehouse" name="search_warehouse" class="form-control" placeholder="warehouse name"/></div>\n' +
            '<div class="form-group"><input type="number" id="search_capacity" name="search_capacity" class="form-control" placeholder="capacity"/></div>\n' +
            '<div class="form-group"><input type="text" id="search_location" name="search_location" class="form-control" placeholder="location"/></div>\n' +
            '<div class="form-group"><input type="text" id="search_manager" name="search_manager" class="form-control" placeholder="manager"/></div>\n' +
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
        var par_warehouse = $('#search_warehouse').val();
        var par_location = $('#search_location').val();
        var par_capacity = $('#search_capacity').val();
        var par_manager = $('#search_manager').val();

        $.ajax({
            url: '/search_warehouses',
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                warehouse_id: par_id,
                warehouse: par_warehouse,
                location: par_location,
                capacity: par_capacity,
                manager: par_manager,

            },
            success: function(response) {
                console.log(response.message);
                var warehouses = response.warehouses;
                $('.card.p-3').remove();

                var warehouseHtml = '<div class="card p-3">' +
                    '<table class="warehouse_table" id="warehouse_table">' +
                    '<thead>' +
                    '<tr>'+
                    '<th></th>'+
                    ' <th>Warehouse No</th>'+
                    '<th>Warehouse</th>'+
                    '<th>Location</th>'+
                    '<th>Capacity</th>'+
                    '<th>Manager</th>'+
                    '</tr>' +
                    '</thead>' +
                    '<tbody>';

                warehouses.forEach(function(warehouse) {
                    warehouseHtml += `<tr>
                        <td>
                        <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle no-caret" type="button"
                    id="dropdownMenuButton" data-warehouse-id="${ warehouse.id }"
                    data-bs-toggle="dropdown" aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                    fill="currentColor" class="bi bi-three-dots-vertical"
                    viewBox="0 0 16 16">
                        <path
                    d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"></path>
                </svg>
                </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <li><a href="/warehouse/${ warehouse.id }"
                               class="dropdown-item detail_warehouse" id="detail_warehouse"
                               data-warehouse-id="${ warehouse.id }">Detail</a></li>
                        <li><p class="dropdown-item modify_warehouse" id="modify_warehouse"
                               data-warehouse-id="${ warehouse.id }">Modify</p></li>
                        <li><p class="dropdown-item delete_warehouse" id="delete_warehouse"
                               data-warehouse-id="${ warehouse.id }">Delete</p></li>
                    </ul>
                </div>
                <td><input type="text" class="form-control" name="warehouse_number"
                                                   value="${warehouse.id}" disabled></td>
                                        <td><input type="text" class="form-control" name="warehouse"
                                                   value="${warehouse.warehouse}"></td>
                                        <td><input type="text" class="form-control" name="location"
                                                   value="${warehouse.location}"></td>
                                        <td><input type="number" class="form-control" name="capacity"
                                                   value="${warehouse.capacity}"></td>
                                        <td><input type="number" class="form-control" name="manager"
                                                   value="${warehouse.manager_id}"></td>
                </tr>`;
                });

                warehouseHtml +=       '</tbody>' +
                    '</table>' +
                    '</div>';

                $('#search_inputs').after(warehouseHtml);
            },
            error: function(error) {
                console.error('Error searching warehouses:', error);
            }
        });
    });
});

