$(document).ready(function() {
    $('#modify_order').click(function() {
        var par_order_id = document.getElementById('order_id').value;
        var par_state = document.getElementById('state').value;

        $.ajax({
            type: 'post',
            url: '/modify_order',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            data: {
                order_id: par_order_id,
                state: par_state,
            },
            success: function (response) {
                console.log(response.message);
                alert(response.message);
            },
            error: function (response) {
                console.error('Error saving order data:');
            }
        });

    });
});

$('#delete_order').on('click', function(e) {
    e.stopPropagation();

    var par_order_id = document.getElementById('order_id').value;

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
            window.location.replace('/order');
        },
        error: function (response) {
            console.error('Error deleting product data:');
        }
    })
});

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

    var errors = [];

    if (!par_customer) errors.push("Customer is required.");

    if (errors.length > 0) {
        alert("Please fill out all fields.\n" + errors.join("\n"));
        return;
    }

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
            window.location.replace('/order/ ' + response.order.id );
        },
        error: function (response) {
            console.error('Error saving user data:');
        }
    });
});

document.getElementById('new_order_line').addEventListener('click', function() {
    var dialog = document.getElementById('order_line_dialog');
    var par_order_id = document.getElementById('order_id').value;
    var par_product_id = document.getElementById('new_product_id_select').value;
    var par_quantity = document.getElementById('new_quantity').value;
    var par_vat_percentage = document.getElementById('new_vat_percentage').value;

    var errors = [];

    if (!par_product_id) errors.push("Product is required.");
    if (!par_quantity) errors.push("Quantity is required.");
    if (!par_vat_percentage) errors.push("VAT percentage is required.");


    if (errors.length > 0) {
        alert("Please fill out all fields.\n" + errors.join("\n"));
        return;
    }

    $.ajax({
        type: 'post',
        url: '/add_new_order_line',
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        data: {
            order_id: par_order_id,
            product_id: par_product_id,
            quantity: par_quantity,
            vat_percentage: par_vat_percentage,
        },
        success: function (response) {
            dialog.style.display = 'none';
            console.log(response.order_line);
            var order_line = response.order_line;

            var new_row =
                `<tr>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle no-caret" type="button" id="dropdownMenuButton" data-order-line-id="${ order_line.id }" data-bs-toggle="dropdown" aria-expanded="false">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                                    <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"></path>
                                </svg>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <li><p class="dropdown-item modify_order_lines" id="modify_order_lines" data-order-line-id="${ order_line.id }">Modify</p></li>
                                <li><p class="dropdown-item delete_order_lines" id="delete_order_lines" data-order-line-id="${ order_line.id }">Delete</p></li>
                            </ul>
                        </div>
                    </td>
                    <td><input type="text" class="form-control" name="order_line_id" value="${ order_line.order_line }" disabled></td>
                    <td><input type="text" class="form-control" name="product_id" value="${ order_line.product_id }"></td>
                    <td><input type="text" class="form-control" name="product_name" value="${ order_line.product_name }" disabled></td>
                    <td><input type="text" class="form-control" name="quantity" value="${ order_line.quantity }"></td>
                    <td><input type="text" class="form-control" name="units" value="${ order_line.units }" disabled></td>
                    <td><input type="text" class="form-control" name="unit_price" value="${ order_line.unit_price }" disabled></td>
                    <td><input type="text" class="form-control" name="vat_percentage" value="${ order_line.vat_percentage }"></td>
                    <td><input type="text" class="form-control" name="total_order_line_net_amount" value="${ order_line.total_order_line_net_amount }" disabled></td>
                    <td><input type="text" class="form-control" name="total_order_line_gross_amount" value="${ order_line.total_order_line_gross_amount }" disabled></td>
                </tr>`;

            $('.order_lines_table tbody').append(new_row);
            actualise_total_amounts(order_line.total_net_amount, order_line.total_gross_amount);
            alert(response.message);
        },
        error: function(xhr, status, error) {
           console.error('Error saving Order data:')
        }
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

$('#add_order_lines').click(function() {
    var dialog = document.getElementById('order_line_dialog');
    dialog.style.display = 'block';
});

document.getElementById('cancel_order_line').addEventListener('click', function() {
    var dialog = document.getElementById('order_line_dialog');
    dialog.style.display = 'none';
});

$('.dropdown-item.modify_order_line').on('click', function(e) {
    e.stopPropagation();

    var par_order_line_id = $(this).data('order-line-id');

    var $row = $(this).closest('tr');

    var par_product_id = $row.find('input[name="product_id"]').val();
    var par_quantity = $row.find('input[name="quantity"]').val();
    var par_vat_percentage = $row.find('input[name="vat_percentage"]').val();


    console.log(par_order_line_id);
    console.log(par_product_id);
    console.log(par_quantity);
    console.log(par_vat_percentage);
    $.ajax({
        type: 'post',
        url: '/modify_order_line',
        method: 'post',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        data: {
            order_line_id: par_order_line_id,
            product_id: par_product_id,
            quantity: par_quantity,
            vat_percentage: par_vat_percentage,

        },
        success: function (response) {
            console.log(response.message);
            var order_line = response.order_line;
            $row.find('input[name="product_name"]').val(order_line.product_name);
            $row.find('input[name="units"]').val(order_line.units);
            $row.find('input[name="unit_price"]').val(order_line.unit_price);
            $row.find('input[name="total_order_line_net_amount"]').val(order_line.total_order_line_net_amount);
            $row.find('input[name="total_order_line_gross_amount"]').val(order_line.total_order_line_gross_amount);
            actualise_total_amounts(order_line.total_net_amount, order_line.total_gross_amount);
            alert(response.message);
        },
        error: function(error) {
            console.error('Error modifying Order Line data:');
        }
    })
});

function actualise_total_amounts(total_net_amount, total_gross_amount) {
    document.getElementById('total_net_amount').value = total_net_amount;
    document.getElementById('total_gross_amount').value = total_gross_amount;
}

$('.dropdown-item.delete_order_line').on('click', function(e) {
    e.stopPropagation();

    var $this = $(this);
    var par_order_line_id = $(this).data('order-line-id');

    $.ajax({
        url: '/delete_order_line',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        data: {
            order_line_id: par_order_line_id,
        },
        success: function (response) {
            console.log(response.message);
            $this.closest('tr').fadeOut(500, function() {
                $(this).remove();
            });
            var order = response.order;
            var order_lines = order.order_lines;
            actualise_total_amounts(order.total_net_amount, order.total_gross_amount);
            actualise_order_lines_nums(order_lines);
            alert(response.message);
        },
        error: function(error) {
            console.error('Error deleting Order Line data:');
        }
    })
});

function actualise_order_lines_nums(order_lines) {
    order_lines.forEach(function(order_line) {
        var $row = $('tr[data-order-line-id="' + order_line.id + '"]');

        $row.find('input[name="order_line_id"]').val(order_line.order_line);
    });
}

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

$(document).ready(function() {
    $('#new_product_id_select').select2({
        ajax: {
            url: '/select_products',
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    search_term: params.term,
                };
            },
            processResults: function(response) {
                return {
                    results: response.products.map(function(product) {
                        return {id: product.id, text: product.name};
                    })
                };
            },
            cache: true
        },
        placeholder: 'Select a product',
        minimumInputLength: 1,
        minimumResultsForSearch: 0,
        width: '100%',
    });
});

$('#print_order').on('click', function() {
    var orderId = $(this).data('order-id');
    var dialog = document.getElementById('organisation_dialog');
    dialog.setAttribute('data-order-id', orderId);
    dialog.style.display = 'block';
});

document.getElementById('cancel_organisation').addEventListener('click', function() {
    var dialog = document.getElementById('organisation_dialog');
    dialog.style.display = 'none';
});

document.getElementById('print_the_order').addEventListener('click', function() {
    var dialog = document.getElementById('organisation_dialog');
    var par_organisation = $('#organisation_select').val();
    var orderId = dialog.getAttribute('data-order-id');

    window.location.href = "/download_order/" + orderId + "?organisation=" + encodeURIComponent(par_organisation);

    dialog.style.display = 'none';
});

$(document).ready(function() {
    $('#organisation_select').select2({
        ajax: {
            url: '/select_organisations',
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    search_term: params.term,
                };
            },
            processResults: function(response) {
                return {
                    results: response.organisations.map(function(organisation) {
                        return {id: organisation.id, text: organisation.organisation};
                    })
                };
            },
            cache: true
        },
        placeholder: 'Select a organisation',
        minimumInputLength: 1,
        minimumResultsForSearch: 0,
        width: '100%',
    });
});
