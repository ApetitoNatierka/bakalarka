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
    var par_customer = document.getElementById('customer').value;

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
            var customer = response.customer;

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
                                <li><p class="dropdown-item detail_order" data-order-id="${order.id}">Detail</p></li>
                                <li><p class="dropdown-item modify_order" data-order-id="${order.id}">Modify</p></li>
                                <li><p class="dropdown-item delete_order" data-order-id="${order.id}">Delete</p></li>
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
                    <td><input type="text" class="form-control" name="customer_name" value="${customer.name}"></td>
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
