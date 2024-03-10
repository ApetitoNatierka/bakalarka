$(document).ready(function() {
    $('#search_products').click(function() {
        var search = $('#search_inputs');
        var inputs = '<input type="text" id="search_name" name="search_name" placeholder="product" />\n' +
            '        <input type="number" id="search_min_price" name="search_min_price" placeholder="min. price"/>\n' +
            '        <input type="number" id="search_max_price" name="search_max_price" placeholder="max. price"/>\n' +
            '        <button id="search_button" style="border-radius: 5px" >search</button>';

        if (search.is(':empty')) {
            search.append(inputs)
        } else {
            search.empty()
        }
    });
});


$(document).ready(function() {
    $(document).on('click', '#search_button', function() {
        var par_name = $('#search_name').val();
        var par_min_price = $('#search_min_price').val();
        var par_max_price = $('#search_max_price').val();

        $.ajax({
            url: '/search_products',
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                name: par_name,
                min_price: par_min_price,
                max_price: par_max_price,
            },
            success: function(response) {
                console.log(response.message);
                var productImagePath = $('.container').data('product-image-path');
                $('.card.p-3').remove();

                if (response.products.length === 0) {
                    var noProductsHtml = '<div class="card p-3">' +
                        '    <div class="row mb-3 align-content-center">' +
                        '        <p> no data found</p>' +
                        '        <hr>' +
                        '    </div>' +
                        '</div>';
                    $('#search_inputs').after(noProductsHtml);
                } else {
                    response.products.forEach(function(product) {
                        var productHtml = '<div class="card p-3">' +
                            '<div class="row mb-3">' +
                            '    <div class="col-4">' +
                            '        <img src="' + productImagePath + '" class="card-img-top" alt="Product image">' +
                            '    </div>' +
                            '    <div class="col-8">' +
                            '        <h3>' + product.name + '</h3>' +
                            '        <p>' + product.description + '</p>' +
                            '        <p>' + product.price + '€</p>' +
                            '    </div>' +
                            '</div>' +
                            '</div>';
                        $('#search_inputs').after(productHtml);
                    });
                }
            },
            error: function(error) {
                console.error('Error searching products:', error);
            }
        });
    });
});

$(document).ready(function() {
    $(document).on('click', '#new_product', function() {
        var dialog = document.getElementById('product_dialog');
        var par_name = document.getElementById('product_name').value;
        var par_description = document.getElementById('product_description').value;
        var par_price = document.getElementById('product_price').value;

        $.ajax({
            type: 'post',
            url: '/add_new_product',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            data: {
                name: par_name,
                description: par_description,
                price: par_price,
            },
            success: function (response) {
                dialog.style.display = 'none';
                console.log(response.new_product);
                var new_product = response.new_product;
                var productImagePath = $('.container').data('product-image-path');

                var new_row = '<div class="card p-3">' +
                                    '    <div class="row mb-3">' +
                                    '        <div class="col-4">' +
                                    '            <img src="' + productImagePath + '" class="card-img-top" alt="Placeholder image">' +
                                    '        </div>' +
                                    '        <div class="col-8">' +
                                    '            <h3>' + new_product.name + '</h3>' +
                                    '            <p>' + new_product.description + '</p>' +
                                    '            <p>' + new_product.price + '€</p>' +
                                    '        </div>' +
                                    '    </div>' +
                                    '</div>';

                $('.card-body').append(new_row);
            },
            error: function (response) {
                console.error('Error saving user data:');
                console.log(par_price, par_name, par_description);
            }
        });
    });
});

$(document).ready(function() {

    $('#add_new_product').click(function() {
        var dialog = document.getElementById('product_dialog');
        dialog.style.display = 'block';
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

document.getElementById('cancel_product').addEventListener('click', function() {
    var dialog = document.getElementById('product_dialog');
    dialog.style.display = 'none';
});

$('.dropdown-item.modify_product').on('click', function(e) {
    e.stopPropagation();

    var par_product_id = $(this).data('product-id');

    var $productContainer = $(this).closest('.product[data-product-id="' + par_product_id + '"]');

    var par_name = $productContainer.find('input[name="product_name"]').val();
    var par_description = $productContainer.find('textarea[name="product_description"]').val();
    var par_price = $productContainer.find('input[name="product_price"]').val();
    par_price = par_price ? par_price.replace('€', '') : '';

    $.ajax({
        type: 'post',
        url: '/modify_product',
        method: 'post',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        data: {
            product_id: par_product_id,
            name: par_name,
            description: par_description,
            price: par_price,
        },
        success: function (response) {
            console.log(response.message);
        },
        error: function (response) {
            console.error('Error modifying product data:');
            console.log('cena ' + par_price);
            console.log('desc ' + par_description);
            console.log('nazov ' + par_name);

        }
    })
});

$('.dropdown-item.delete_product').on('click', function(e) {
    e.stopPropagation();

    var $this = $(this);
    var par_product_id = $(this).data('product-id');

    $.ajax({
        url: '/delete_product',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        data: {
            product_id: par_product_id,
        },
        success: function (response) {
            console.log(response.message);
            $this.closest('.product[data-product-id="' + par_product_id + '"]').fadeOut(500, function() {
                $(this).remove();
            });
        },
        error: function (response) {
            console.error('Error deleting product data:');
        }
    })
});