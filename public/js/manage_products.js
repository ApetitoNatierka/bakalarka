$(document).ready(function() {
    $('#search_products').click(function() {
        var search = $('#search_inputs');
        var inputs = '<div class="form-group"><input type="text" id="search_name" name="search_name" class="form-control" placeholder="product" /></div>\n' +
            '<div class="form-group"><input type="number" id="search_min_price" name="search_min_price" class="form-control" placeholder="min. price"/></div>\n' +
            '<div class="form-group"><input type="number" id="search_max_price" name="search_max_price" class="form-control" placeholder="max. price"/></div>\n' +
            '<div class="form-group"><input type="text" id="search_units" name="search_units" class="form-control" placeholder="units"/></div>\n' +
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
        var par_name = $('#search_name').val();
        var par_min_price = $('#search_min_price').val();
        var par_max_price = $('#search_max_price').val();
        var par_type = document.getElementById('entity_type').value;
        var par_units = $('#search_units').val();

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
                type: par_type,
                units: par_units,
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
                } else if(response.user.role === 'admin') {
                    response.products.forEach(function(product) {
                        var productHtml = '<div class="row mb-3">' +
                            '    <div class="col-4">' +
                            '        <img src="' + productImagePath + '" class="card-img-top" alt="Product image">' +
                            '    </div>' +
                            '<div class="col-8">\n' +
'                                        <label>\n' +
'                                            <input name="product_name" value="' + product.name + '">\n' +
'                                        </label>\n' +
'                                        <label>\n' +
'                                            <textarea name="product_description">' + product.description + '</textarea>\n' +
'                                        </label>\n' +
'                                        <label>\n' +
'                                            <input name="product_price" value="' + product.price * 1.2 + '€">\n' +
'                                        </label>\n' +
'                                        <label>\n' +
'                                            <input name="product_type" value="' + product.type + '">\n' +
'                                        </label>\n' +
'                                        <label>\n' +
'                                            <input name="product_units" value="' + product.units + '">\n' +
'                                        </label>' +
                            '</div> ' +
                            '<div class="dropdown">\n' +
            '                                        <button class="btn btn-secondary dropdown-toggle no-caret" type="button" id="dropdownMenuButton" data-product-id="' + product.id + '" data-bs-toggle="dropdown" aria-expanded="false">\n' +
            '                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">\n' +
            '                                                <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"></path>\n' +
            '                                            </svg>\n' +
            '                                        </button>\n' +
            '                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">\n' +
            '                                            <li><p class="dropdown-item modify_product" id="modify_product" data-product-id="' + product.id + '">Modify</p></li>\n' +
            '                                            <li><p class="dropdown-item delete_product" id="delete_product" data-product-id="' + product.id + '">Delete</p></li>\n' +
            '                                        </ul>\n' +
            '                                    </div>'
                        '</div>';
                        $('#search_inputs').after(productHtml);
                });
                }
                else{
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
                            '        <p>' + product.type + '</p>' +
                            '        <p>' + product.units + '</p>' +
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
        var par_type = document.getElementById('product_type').value;
        var par_units = document.getElementById('product_units').value;

        var errors = [];

        if (!par_name) errors.push("Name is required.");
        if (!par_description) errors.push("Description is required.");
        if (!par_price) errors.push("Price is required.");
        if (!par_type) errors.push("Type is required.");
        if (!par_units) errors.push("Units is required.");


        if (errors.length > 0) {
            alert("Please fill out all fields.\n" + errors.join("\n"));
            return;
        }

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
                type: par_type,
                units: par_units,
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
                                    '            <p>' + new_product.type + '</p>' +
                                    '            <p>' + new_product.units + '</p>' +
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
//$(document).on('click', '.dropdown-item.modify_product', function(e) {
    e.stopPropagation();

    console.log('jjj');
    var par_product_id = $(this).data('product-id');

    var $productContainer = $(this).closest('.product[data-product-id="' + par_product_id + '"]');

    var par_name = $productContainer.find('input[name="product_name"]').val();
    var par_description = $productContainer.find('textarea[name="product_description"]').val();
    var par_price = $productContainer.find('input[name="product_price"]').val();
    par_price = par_price ? par_price.replace('€', '') : '';
    var par_type = $productContainer.find('input[name="product_type"]').val();
    var par_units = $productContainer.find('input[name="product_units"]').val();



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
            type: par_type,
            units: par_units,
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
//$(document).on('click', '.dropdown-item.delete_product', function(e) {
    e.stopPropagation();

    console.log('nnn');

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


$(document).ready(function() {

    $('.add_to_cart_button').click(function() {
        var dialog = document.getElementById('quantity_dialog');
        var par_product_id = $(this).closest('.product').data('product-id');
        dialog.style.display = 'block';
        $('#quantity_dialog').attr('data-cart-product-id', par_product_id);
        console.log(par_product_id)
    });
});

$(document).ready(function() {
    $(document).on('click', '#add_to_cart', function() {
        var dialog = document.getElementById('quantity_dialog');
        var par_quantity = document.getElementById('quantity').value;
        var par_product_id = dialog.getAttribute('data-cart-product-id');

        $.ajax({
            type: 'post',
            url: '/add_to_cart',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            data: {
                quantity: par_quantity,
                product_id: par_product_id,
            },
            success: function (response) {
                dialog.style.display = 'none';
                var show_cart_dialog = document.getElementById('show_cart_dialog');
                show_cart_dialog.style.display = 'block';
            },
            error: function (response) {
                console.error('Error adding product to cart:');
            }
        });
    });
});

$(document).ready(function() {
    $('#go_to_cart').click(function() {
        window.location.href = '/cart';
    });
});

$(document).ready(function() {
    $('#cancel_go_to_cart').click(function() {
        var par_show_cart_dialog = document.getElementById('show_cart_dialog');
        par_show_cart_dialog.style.display = 'none';
    });
});

$(document).ready(function() {
    $('#cancel_add_to_cart').click(function() {
        var par_quantity_dialog = document.getElementById('quantity_dialog');
        par_quantity_dialog.style.display = 'none';
    });
});

