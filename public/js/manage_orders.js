$(document).ready(function() {

    $('#add_new_order').click(function() {
        var dialog = document.getElementById('order_dialog');
        dialog.style.display = 'block';
    });
});

document.getElementById('cancel_order').addEventListener('click', function() {
    var dialog = document.getElementById('order_dialog');
    dialog.style.display = 'none';
});

document.getElementById('new_order').addEventListener('click', function() {
    var dialog = document.getElementById('order_dialog');
    var par_customer = $('#customer_select').val();

    $.ajax({
        type: 'post',
        url: '/add_order',
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        data: {
            customer: par_customer,

        },
        success: function (response) {
            dialog.style.display = 'none';
            console.log(response.order);
            console.log(response.order);
            var order = response.order;

            var new_row =
                `<tr>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle no-caret" type="button" id="dropdownMenuButton" data-order-id="${order.id}" data-bs-toggle="dropdown" aria-expanded="false">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                                    <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"></path>
                                </svg>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <li><a href="/order/${order.id}" class="dropdown-item detail_order" id="detail_order" data-order-id="${order.id}">Detail</a></li>
                        <li><a href="/download_order/${order.id} " class="dropdown-item download_order" id="download_order" data-order-id="${order.id}">Print</a></li>
                        <li><p class="dropdown-item modify_order" id="modify_order" data-order-id=" ${order.id} ">Modify</p></li>
                        <li><p class="dropdown-item delete_order" id="delete_order" data-order-id=" ${order.id} ">Delete</p></li>
                            </ul>
                        </div>
                    </td>
                    <td><input type="text" class="form-control" name="order_number" value="${order.id}"></td>
                    <td>
                        <select class="state" id="state" class="form-select" name="type">
                            <option selected>${order.state}</option>
                            <option>arrival</option>
                            <option>ongoing</option>
                            <option>processed</option>
                        </select>
                    </td>
                    <td><input type="text" class="form-control" name="created" value="${order.created}"></td>
                    <td><input type="text" class="form-control" name="customer_name" value="${order.customer_name}"></td>
                    <td><input type="text" class="form-control" name="total_net_amount" value="${order.total_net_amount}"></td>
                    <td><input type="text" class="form-control" name="total_gross_amount" value="${order.total_gross_amount}"></td>
                </tr>`;

            $('.order_table tbody').append(new_row);
        },
        error: function (response) {
            console.error('Error saving user data:');
        }
    });
});

$('.dropdown-item.delete_order').on('click', function(e) {
    e.stopPropagation();

    var $this = $(this);
    var par_order_id = $(this).data('order-id');

    $.ajax({
        url: '/delete_order',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        data: {
            order_id: par_order_id,
        },
        success: function (response) {
            console.log(response.message);
            $this.closest('tr').fadeOut(500, function() {
                $(this).remove();
            });
        },
        error: function (response) {
            console.error('Error deleting product data:');
        }
    })
});

$('.dropdown-item.modify_order').on('click', function(e) {
    e.stopPropagation();

    var par_order_id = $(this).data('order-id');

    var $row = $(this).closest('tr');

    var par_state = $row.find('select[name="state"]').val();

    $.ajax({
        type: 'post',
        url: '/modify_order',
        method: 'post',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        data: {
            order_id: par_order_id,
            state: par_state,
        },
        success: function (response) {
            console.log(response.message);
        },
        error: function (response) {
            console.error('Error modifying user data:');
        }
    })
});

$(document).ready(function() {
    $('#search_orders').click(function() {
        var search = $('#search_inputs');
        var inputs = '<div class="form-group"><input type="text" id="search_id" name="search_id" class="form-control" placeholder="order number" /></div>\n' +
            '<div class="form-group"><input type="text" id="search_state" name="search_state" class="form-control" placeholder="state"/></div>\n' +
            '<div class="form-group"><input type="date" id="search_created_from" name="search_created_from" class="form-control" placeholder="created from"/></div>\n' +
            '<div class="form-group"><input type="date" id="search_created_to" name="search_created_to" class="form-control" placeholder="created to"/></div>\n' +
            '<div class="form-group"><input type="text" id="search_customer_name" name="search_customer_name" class="form-control" placeholder="customer name"/></div>\n' +
            '<div class="form-group"><input type="number" id="search_total_net_amount_min" name="search_total_net_amount_min" class="form-control" placeholder="total net amount min."/></div>\n' +
            '<div class="form-group"><input type="number" id="search_total_net_amount_max" name="search_total_net_amount_max" class="form-control" placeholder="total net amount max."/></div>\n' +
            '<div class="form-group"><input type="number" id="search_total_gross_amount_min" name="search_total_gross_amount_min" class="form-control" placeholder="total gross amount min."/></div>\n' +
            '<div class="form-group"><input type="number" id="search_total_gross_amount_max" name="search_total_gross_amount_max" class="form-control" placeholder="total gross amount max."/></div>\n' +
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
        var par_state = $('#search_state').val();
        var par_created_from = $('#search_created_from').val();
        var par_created_to = $('#search_created_to').val();
        var par_customer_name = $('#search_customer_name').val();
        var par_total_net_amount_min = $('#search_total_net_amount_min').val();
        var par_total_net_amount_max = $('#search_total_net_amount_max').val();
        var par_total_gross_amount_min = $('#search_total_gross_amount_min').val();
        var par_total_gross_amount_max = $('#search_total_gross_amount_max').val();

        $.ajax({
            url: '/search_orders',
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                order_id: par_id,
                state: par_state,
                created_from: par_created_from,
                created_to: par_created_to,
                customer_name: par_customer_name,
                total_net_amount_min: par_total_net_amount_min,
                total_net_amount_max: par_total_net_amount_max,
                total_gross_amount_min: par_total_gross_amount_min,
                total_gross_amount_max: par_total_gross_amount_max,
            },
            success: function(response) {
                console.log(response.message);
                var orders = response.orders;
                $('.card.p-3').remove();

                var orderHtml = '<div class="card p-3">' +
                    '<table class="order_table" id="order_table">' +
                    '<thead>' +
                    '<tr>' +
                    '<th></th>' +
                    '<th>Order Number</th>' +
                    '<th>State</th>' +
                    '<th>Created</th>' +
                    '<th>Customer Name</th>' +
                    '<th>Total Net Amount</th>' +
                    '<th>Total Gross Amount</th>' +
                    '</tr>' +
                    '</thead>' +
                    '<tbody>';

                orders.forEach(function(order) {
                    orderHtml += '<tr>' +
                        '<td>' +
                        '<div class="dropdown">' +
                        '<button class="btn btn-secondary dropdown-toggle no-caret" type="button" id="dropdownMenuButton' + order.id + '" data-order-id="' + order.id + '" data-bs-toggle="dropdown" aria-expanded="false">' +
                        '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">' +
                        '<path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"></path>' +
                        '</svg>' +
                        '</button>' +
                        '<ul class="dropdown-menu" aria-labelledby="dropdownMenuButton' + order.id + '">' +
                        '<li><a href="/order/' + order.id + '" class="dropdown-item detail_order" id="detail_order" data-order-id="' + order.id + '">Detail</a></li>' +
                        '<li><a href="/download_order/' + order.id + '" class="dropdown-item download_order" id="download_order" data-order-id="' + order.id + '">Print</a></li>\n' +
                        '<li><p class="dropdown-item modify_order" id="modify_order" data-order-id="' + order.id + '">Modify</p></li>' +
                        '<li><p class="dropdown-item delete_order" id="delete_order" data-order-id="' + order.id + '">Delete</p></li>' +
                        '</ul>' +
                        '</div>' +
                        '</td>' +
                        '<td><input type="text" class="form-control" name="order_number" value="' + order.id + '" disabled></td>' +
                        '<td><select id="state' + order.id + '" class="form-select state" name="state">' +
                        '<option' + (order.state === 'arrival' ? ' selected' : '') + '>arrival</option>' +
                        '<option' + (order.state === 'ongoing' ? ' selected' : '') + '>ongoing</option>' +
                        '<option' + (order.state === 'processed' ? ' selected' : '') + '>processed</option>' +
                        '</select></td>' +
                        '<td><input type="text" class="form-control" name="created" value="' + order.created_at + '" disabled></td>' +
                        '<td><input type="text" class="form-control" name="customer_name" value="' + order.customer_name + '" disabled></td>' +
                        '<td><input type="text" class="form-control" name="total_net_amount" value="' + order.total_net_amount + '" disabled></td>' +
                        '<td><input type="text" class="form-control" name="total_gross_amount" value="' + order.total_gross_amount + '" disabled></td>' +
                        '</tr>';
                });

                orderHtml +=       '</tbody>' +
                    '</table>' +
                    '</div>';

                $('#search_inputs').after(orderHtml);
            },
            error: function(error) {
                console.error('Error searching orders:', error);
            }
        });
    });
});

$(document).ready(function() {
    $('#customer_select').select2({
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
        placeholder: 'Select a customer',
        minimumInputLength: 1,
        minimumResultsForSearch: 0,
        width: '100%',
    });
});

