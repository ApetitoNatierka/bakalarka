$(document).on('click', '.dropdown-item.delete_item', function(e) {
    e.stopPropagation();

    var $this = $(this);
    var par_item_id = $(this).data('item-id');

    $.ajax({
        url: '/delete_item',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        data: {
            item_id: par_item_id,
        },
        success: function (response) {
            console.log(response.message);
            $this.closest('tr').fadeOut(500, function() {
                $(this).remove();
            });
        },
        error: function (response) {
            console.error('Error deleting item data:');
        }
    })
});

//$('.dropdown-item.modify_animal_number').on('click', function(e) {
$(document).on('click', '.dropdown-item.modify_item', function(e) {

    e.stopPropagation();

    var par_item_id = $(this).data('item-id');

    var $row = $(this).closest('tr');

    var par_warehouse_id = $row.find('select[name="warehouse_id"]').val();

    $.ajax({
        type: 'post',
        url: '/modify_item',
        method: 'post',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        data: {
            item_id: par_item_id,
            warehouse_id: par_warehouse_id,
        },
        success: function (response) {
            console.log(response.item);
            console.log(par_warehouse_id, par_item_id);
        },
        error: function (response) {
            console.error('Error modifying item data:');
        }
    })
});

$(document).ready(function() {
    $('#search_items').click(function() {
        var search = $('#search_inputs');
        var inputs = '<div class="form-group"><input type="text" id="search_id" name="search_id" class="form-control" placeholder="item id" /></div>\n' +
            '<div class="form-group"><input type="text" id="search_item_no" name="search_item_no" class="form-control" placeholder="item no"/></div>\n' +
            '<div class="form-group"><input type="text" id="search_item_type" name="search_item_type" class="form-control" placeholder="item type"/></div>\n' +
            '<div class="form-group"><input type="text" id="search_quantity" name="search_quantity" class="form-control" placeholder="quantity"/></div>\n' +
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
        var par_item_no = $('#search_item_no').val();
        var par_item_type = $('#search_item_type').val();
        var par_quantity= $('#search_quantity').val();
        var par_warehouse = $('#search_warehouse').val();

        $.ajax({
            url: '/search_items',
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                item_id: par_id,
                item_no: par_item_no,
                item_type: par_item_type,
                quantity: par_quantity,
                warehouse: par_warehouse,

            },
            success: function(response) {
                console.log(response.message);
                var items = response.items;
                var warehouses = response.warehouses;
                $('.card.p-3').remove();

                var itemHtml = '<div class="card p-3">' +
                    '<table class="item_table" id="item_table">' +
                    '<thead>' +
                    '<tr>'+
                    '<th></th>'+
                    ' <th>Item No Id</th>'+
                    '<th>Item No</th>'+
                    '<th>Item Type</th>'+
                    '<th>Quantity</th>'+
                    '<th>Warehouse</th>'+
                    '</tr>' +
                    '</thead>' +
                    '<tbody>';

                items.forEach(function(item) {
                    itemHtml += `<tr>
                        <td>
                        <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle no-caret" type="button"
                    id="dropdownMenuButton" data-item-id="${ item.id }"
                    data-bs-toggle="dropdown" aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                    fill="currentColor" class="bi bi-three-dots-vertical"
                    viewBox="0 0 16 16">
                        <path
                    d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"></path>
                </svg>
                </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <li><p class="dropdown-item modify_item" id="modify_item"
                               data-item-id="${ item.id }">Modify</p></li>
                        <li><p class="dropdown-item delete_item" id="delete_item"
                               data-item-id="${ item.id }">Delete</p></li>
                    </ul>
                </div>
                <td><input type="text" class="form-control" name="item_id"
                                                   value="${item.id}" disabled></td>
                                        <td><input type="text" class="form-control" name="item_no"
                                                   value="${item.item_no}" disabled></td>
                                        <td><input type="text" class="form-control" name="item_type"
                                                   value="${item.item_type}" disabled></td>
                                        <td><input type="number" class="form-control" name="quantity"
                                                   value="${item.quantity}" disabled></td>
                                        <td>${createwarehousesSelect(warehouses, item.warehouse_id)}</td>
                </tr>`;
                });

                itemHtml +=       '</tbody>' +
                    '</table>' +
                    '</div>';

                $('#search_inputs').after(itemHtml);
            },
            error: function(error) {
                console.error('Error searching items:', error);
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
