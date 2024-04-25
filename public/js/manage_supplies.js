$(document).ready(function() {

    $('#add_new_supplies').click(function() {
        var dialog = document.getElementById('supply_dialog');
        dialog.style.display = 'block';
    });
});

document.getElementById('cancel_supply').addEventListener('click', function() {
    var dialog = document.getElementById('supply_dialog');
    dialog.style.display = 'none';
});

$(document).ready(function() {
    $('#supply_no_select').select2({
        ajax: {
            url: '/select_supply_nos',
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    search_term: params.term,
                };
            },
            processResults: function(response) {
                return {
                    results: response.supply_nos.map(function(supply_no) {
                        return {id: supply_no.id, text: supply_no.supply_number};
                    })
                };
            },
            cache: true
        },
        placeholder: 'Select supply no',
        minimumInputLength: 1,
        minimumResultsForSearch: 0,
        width: '100%',
    });
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

document.getElementById('new_supply').addEventListener('click', function() {
    var dialog = document.getElementById('supply_dialog');

    var par_supply_no = $('#supply_no_select').val();
    var par_warehouse_id = $('#warehouse_select').val();

    var par_quantity = document.getElementById('new_quantity').value;
    var par_weight = document.getElementById('new_weight').value;
    var par_height = document.getElementById('new_height').value;
    var par_units = document.getElementById('new_units').value;
    var par_status = document.getElementById('new_status').value;
    var par_description = document.getElementById('new_description').value;

    var errors = [];

    if (!par_quantity) errors.push("Quantity is required.");
    if (!par_weight) errors.push("Weight is required.");
    if (!par_height) errors.push("Height is required.");
    if (!par_units) errors.push("Units is required.");
    if (!par_status) errors.push("Status is required.");
    if (!par_description) errors.push("Description is required.");


    if (errors.length > 0) {
        alert("Please fill out all fields.\n" + errors.join("\n"));
        return;
    }

    $.ajax({
        type: 'post',
        url: '/add_supply',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data : {
            supply_number_id: par_supply_no,
            quantity: par_quantity,
            weight: par_weight,
            height: par_height,
            units: par_units,
            status: par_status,
            description: par_description,
            warehouse_id: par_warehouse_id,
        },
        success: function (response) {
            dialog.style.display = 'none';
            console.log(response.supply);
            var supply = response.supply;
            var supply_nos = response.supply_nos;
            var warehouses = response.warehouses;
            var new_row = `
                <tr>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle no-caret" type="button"
                                    id="dropdownMenuButton" data-supply-id="${supply.id}"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                     fill="currentColor" class="bi bi-three-dots-vertical"
                                     viewBox="0 0 16 16">
                                    <path
                                        d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"></path>
                                </svg>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <li> <p class="dropdown-item offer_supply" id="offer_supply"
                                        data-supply-id="${supply.id}">Offer</p></li>
                                <li><p class="dropdown-item modify_supply" id="modify_supply"
                                       data-supply-id="${supply.id}">Modify</p></li>
                                <li><p class="dropdown-item delete_supply" id="delete_supply"
                                       data-supply-id="${supply.id}">Delete</p></li>
                            </ul>
                        </div>
                    </td>
                    <td><input type="number" class="form-control" name="supply_id"
                               value="${supply.id}" disabled></td>
                    <td>${createSerrialNosSelect(supply_nos, supply.supply_number_id)}</td>
                    <td><input type="text" class="form-control" name="description"
                               value="${supply.description}"></td>
                    <td><input type="number" class="form-control" name="quantity"
                               value="${supply.quantity}"></td>
                    <td><input type="number" class="form-control" name="weight"
                               value="${supply.weight}"></td>
                    <td><input type="number" class="form-control" name="height"
                               value="${supply.height}"></td>
                    <td><input type="text" class="form-control" name="units"
                               value="${supply.units}"></td>
                    <td><input type="text" class="form-control" name="status"
                               value="${supply.status}"></td>
                    <td>${createwarehousesSelect(warehouses, supply.warehouse_id)}</td>
                </tr>`;
            $('.supplies_table tbody').append(new_row);
            alert(response.message);
        },
        error: function (error) {
            console.error('Error adding supply:', error);
            console.log(par_units,par_status,par_description,par_weight,par_height,par_supply_no,par_quantity,par_warehouse_id);
        }
    });
});

$(document).on('click', '.dropdown-item.delete_supply', function(e) {
    e.stopPropagation();

    var $this = $(this);
    var par_supply_id = $(this).data('supply-id');

    $.ajax({
        url: '/delete_supply',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        data: {
            supply_id: par_supply_id,
        },
        success: function (response) {
            console.log(response.message);
            $this.closest('tr').fadeOut(500, function() {
                $(this).remove();
            });
            alert(response.message);
        },
        error: function (response) {
            console.error('Error deleting supply data:');
        }
    });
});

$(document).on('click', '.dropdown-item.offer_supply', function(e) {
    e.stopPropagation();

    var $this = $(this);
    var par_supply_id = $(this).data('supply-id');
    var dialog = document.getElementById('price_dialog');
    dialog.setAttribute('data-supply-id', par_supply_id);
    dialog.style.display = 'block';

});

$(document).on('click', '.dropdown-item.modify_supply', function(e) {
    e.stopPropagation();

    var par_supply_id = $(this).data('supply-id');
    var $row = $(this).closest('tr');
    var par_warehouse_id = $row.find('select[name="warehouse_id"]').val();

    var par_quantity = $row.find('input[name="quantity"]').val();
    var par_weight = $row.find('input[name="weight"]').val();
    var par_height = $row.find('input[name="height"]').val();
    var par_description = $row.find('input[name="description"]').val();
    var par_status = $row.find('input[name="status"]').val();
    var par_units = $row.find('input[name="units"]').val();
    var par_supply_no = $row.find('select[name="supply_no_id"]').val();

    $.ajax({
        type: 'post',
        url: '/modify_supply',
        method: 'post',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        data: {
            supply_id: par_supply_id,
            quantity: par_quantity,
            weight: par_weight,
            height: par_height,
            status: par_status,
            description: par_description,
            supply_number_id: par_supply_no,
            units: par_units,
            warehouse_id: par_warehouse_id,
        },
        success: function (response) {
            console.log(response.message);
            alert(response.message);
        },
        error: function (response) {
            console.error('Error modifying supply data:');
        }
    });
});

function createSerrialNosSelect(supply_nos, selectedId) {
    var optionsHtml = supply_nos.map(function(supply_no) {
        var isSelected = supply_no.id === selectedId ? 'selected' : '';
        return `<option value="${supply_no.id}" ${isSelected}>${supply_no.id} : ${supply_no.supply_number}</option>`;
    }).join('');

    return `<select class="form-control" name="supply_no_id">${optionsHtml}</select>`;
}

$(document).ready(function() {
    $('#search_supplies').click(function() {
        var search = $('#search_inputs');
        var inputs = `
            <div class="form-group"><input type="text" id="search_id" name="search_id" class="form-control" placeholder="supply id" /></div>
            <div class="form-group"><input type="text" id="search_supply_no" name="search_supply_no" class="form-control" placeholder="supply number"/></div>
            <div class="form-group"><input type="number" id="search_quantity" name="search_quantity" class="form-control" placeholder="quantity"/></div>
            <div class="form-group"><input type="number" id="search_weight" name="search_weight" class="form-control" placeholder="weight"/></div>
            <div class="form-group"><input type="number" id="search_height" name="search_height" class="form-control" placeholder="height"/></div>
            <div class="form-group"><input type="text" id="search_status" name="search_status" class="form-control" placeholder="status"/></div>
            <div class="form-group"><input type="text" id="search_description" name="search_description" class="form-control" placeholder="description"/></div>
            <div class="form-group"><input type="text" id="search_units" name="search_units" class="form-control" placeholder="units"/></div>
            '<div class="form-group"><input type="text" id="search_warehouse" name="search_warehouse" class="form-control" placeholder="warehouse"/></div>\\n' +
            <div class="form-group"><button id="search_button" class="btn btn-primary" style="border-radius: 5px">Search</button></div>
        `;

        if (search.is(':empty')) {
            search.html(inputs);
        } else {
            search.empty()
        }
    });
});

$(document).ready(function() {
    $(document).on('click', '#search_button', function() {
        var par_id = $('#search_id').val();
        var par_supply_no = $('#search_supply_no').val();
        var par_quantity = $('#search_quantity').val();
        var par_weight = $('#search_weight').val();
        var par_height = $('#search_height').val();
        var par_units = $('#search_units').val();
        var par_status = $('#search_status').val();
        var par_description = $('#search_description').val();
        var par_warehouse = $('#search_warehouse').val();

        $.ajax({
            url: '/search_supplies',
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                supply_id: par_id,
                supply_number_id: par_supply_no,
                quantity: par_quantity,
                weight: par_weight,
                height: par_height,
                description: par_description,
                status: par_status,
                units: par_units,
                warehouse: par_warehouse,
            },
            success: function(response) {
                console.log(response.message);
                var supplies = response.supplies;
                var supply_nos = response.supply_nos;
                var warehouses = response.warehouses;
                $('.card.p-3').remove();

                var suppliesHtml = '<div class="card p-3">' +
                    '<table class="supplies_table" id="supplies_table">' +
                    '<thead>' +
                    '<tr>'+
                    '<th></th>'+
                    ' <th>Supply Id</th>'+
                    '<th>Supply No</th>'+
                    '<th>Description</th>'+
                    '<th>Quantity</th>'+
                    '<th>Weight</th>'+
                    '<th>Height</th>'+
                    '<th>Units</th>'+
                    '<th>Status</th>'+
                    '<th>Warehouse</th>'+
                    '</tr>' +
                    '</thead>' +
                    '<tbody>';

                supplies.forEach(function(supply) {
                    suppliesHtml += `<tr>
                        <td>
                        <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle no-caret" type="button"
                    id="dropdownMenuButton" data-supply-id="${supply.id}"
                    data-bs-toggle="dropdown" aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                    fill="currentColor" class="bi bi-three-dots-vertical"
                    viewBox="0 0 16 16">
                        <path
                    d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"></path>
                </svg>
                </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <li> <p class="dropdown-item offer_supply" id="offer_supply"
                               data-supply-id="${supply.id}">Offer</p></li>
                        <li><p class="dropdown-item modify_supply" id="modify_supply"
                               data-supply-id="${supply.id}">Modify</p></li>
                        <li><p class="dropdown-item delete_supply" id="delete_supply"
                               data-supply-id="${supply.id}">Delete</p></li>
                    </ul>
                </div>
                <td><input type="text" class="form-control" name="supply_id"
                               value="${supply.id}" disabled></td>
                    <td>${createSerrialNosSelect(supply_nos, supply.supply_number_id)}</td>
                    <td><input type="text" class="form-control" name="description"
                               value="${supply.description}"></td>
                    <td><input type="number" class="form-control" name="quantity"
                               value="${supply.quantity}"></td>
                    <td><input type="number" class="form-control" name="weight"
                               value="${supply.weight}"></td>
                    <td><input type="number" class="form-control" name="height"
                               value="${supply.height}"></td>
                    <td><input type="text" class="form-control" name="units"
                               value="${supply.units}"></td>
                    <td><input type="text" class="form-control" name="status"
                               value="${supply.status}"></td>
                    <td>${createwarehousesSelect(warehouses, supply.warehouse_id)}</td>
                </tr>`;
                });

                suppliesHtml += '</tbody></table></div>';

                $('#search_inputs').after(suppliesHtml);
            },
            error: function(error) {
                console.error('Error searching supplies:', error);
            }
        });
    });
});

function createwarehousesSelect(warehouses, selectedId) {
    var optionsHtml = warehouses.map(function(warehouse) {
        var isSelected = warehouse.id === selectedId ? 'selected' : '';
        return `<option value="${warehouse.id}" ${isSelected}>${warehouse.id} : ${warehouse.warehouse}</option>`;
    }).join('');

    return `<select class="form-control" name="warehouse_id">${optionsHtml}</select>`;
}

document.getElementById('cancel_offer').addEventListener('click', function() {
    var dialog = document.getElementById('price_dialog');
    dialog.style.display = 'none';
});

document.getElementById('offer').addEventListener('click', function() {
    var dialog = document.getElementById('price_dialog');
    var par_supply_id = dialog.getAttribute('data-supply-id');
    var par_price = document.getElementById('new_price').value;

    $.ajax({
        url: '/offer_supply',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        data: {
            supply_id: par_supply_id,
            price: par_price,
        },
        success: function (response) {
            console.log(response.message);
            alert('Supply offered sucessfully');
        },
        error: function (response) {
            console.error('Error deleting supply data:');
        }
    });
    dialog.style.display = 'none';
});
