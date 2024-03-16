$(document).ready(function() {
    $('#create_order').click(function() {
        $.ajax({
            type: 'post',
            url: '/create_order',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            success: function (response) {
                window.location.href = '/cart';
                sessionStorage.setItem('orderSuccessMessage', 'Order registrated sucessfully.');
            },
            error: function (response) {
                console.error('Error creating order:');
            }
        });
    });
});

$(document).ready(function() {
    var orderSuccessMessage = sessionStorage.getItem('orderSuccessMessage');
    if (orderSuccessMessage) {
        alert(orderSuccessMessage);
        sessionStorage.removeItem('orderSuccessMessage');
    }
});

$('.dropdown-item.delete_cart_item').on('click', function(e) {
    e.stopPropagation();

    var $this = $(this);
    var par_cart_item_id = $(this).data('cart-item-id');

    $.ajax({
        url: '/delete_cart_item',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        data: {
            cart_item_id: par_cart_item_id,
        },
        success: function (response) {
            console.log(response.message);
            $this.closest('.cart_item[data-cart-item-id="' + par_cart_item_id + '"]').fadeOut(500, function() {
                $(this).remove();
            });
        },
        error: function (response) {
            console.error('Error deleting product data:');
        }
    })
});

$('.dropdown-item.modify_cart_item').on('click', function(e) {
    e.stopPropagation();

    var par_cart_item_id = $(this).data('cart-item-id');

    var $cart_container = $(this).closest('.cart_item[data-cart-item-id="' + par_cart_item_id + '"]');

    var par_quantity = $cart_container.find('input[name="quantity"]').val();

    $.ajax({
        type: 'post',
        url: '/modify_cart_item',
        method: 'post',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        data: {
            cart_item_id: par_cart_item_id,
            quantity: par_quantity,
        },
        success: function (response) {
            var totalPrice = par_quantity * response.unit_price;
            $cart_container.find('.total-price').text(totalPrice.toFixed(2));
            console.log(response.message);
        },
        error: function (response) {
            console.error('Error modifying product data:');
            console.log(par_quantity);
            console.log(par_cart_item_id);
        }
    })
});
