$(document).ready(function() {

    $('#add_new_service').click(function() {
        var dialog = document.getElementById('service_dialog');
        dialog.style.display = 'block';
    });
});

document.getElementById('cancel_service').addEventListener('click', function() {
    var dialog = document.getElementById('service_dialog');
    dialog.style.display = 'none';
});

document.getElementById('new_service').addEventListener('click', function() {
    var dialog = document.getElementById('service_dialog');

    var par_name = document.getElementById('new_name').value;
    var par_description = document.getElementById('new_description').value;
    var par_price = document.getElementById('new_price').value;

    var errors = [];

    if (!par_name) errors.push("Name is required.");
    if (!par_description) errors.push("Description is required.");
    if (!par_price) errors.push("Price is required.");


    if (errors.length > 0) {
        alert("Please fill out all fields.\n" + errors.join("\n"));
        return;
    }


    $.ajax({
        type: 'post',
        url: '/add_service',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data : {
            name: par_name,
            description: par_description,
            price: par_price,
        },
        success: function (response) {
            dialog.style.display = 'none';
            console.log(response.service);
            var service = response.service;
            var new_row = `
                <tr>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle no-caret" type="button"
                                    id="dropdownMenuButton"
                                    data-service-id="${service.id}"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                     fill="currentColor" class="bi bi-three-dots-vertical"
                                     viewBox="0 0 16 16">
                                    <path
                                        d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"></path>
                                </svg>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <li> <p class="dropdown-item offer_service" id="offer_service"
                                                            data-service-id="${service.id}">Offer</p></li>
                                <li><p class="dropdown-item modify_service"
                                       id="modify_service"
                                       data-service-id="${service.id}">Modify</p>
                                </li>
                                <li><p class="dropdown-item delete_service"
                                       id="delete_service"
                                       data-service-id="${service.id}">Delete</p>
                                </li>
                            </ul>
                        </div>
                    </td>
                    <td><input type="text" class="form-control" name="service_id"
                               value="${service.id}" disabled></td>
                    <td><input type="text" class="form-control" name="name"
                               value="${service.name}"></td>
                    <td><input type="text" class="form-control" name="description"
                               value="${service.description}"></td>
                    <td><input type="number" class="form-control" name="price"
                               value="${service.price}"></td>
                </tr>`;
            $('.service_table tbody').append(new_row);
        },
        error: function (error) {
            console.error('Error adding animal number:', error);
            console.log(par_name, par_description, par_price);
        }
    });
});

$(document).on('click', '.dropdown-item.delete_service', function(e) {
    e.stopPropagation();

    var $this = $(this);
    var par_service_id = $(this).data('service-id');

    $.ajax({
        url: '/delete_service',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        data: {
            service_id: par_service_id,
        },
        success: function (response) {
            console.log(response.message);
            $this.closest('tr').fadeOut(500, function() {
                $(this).remove();
            });
        },
        error: function (response) {
            console.error('Error deleting service data:');
        }
    })
});

//$('.dropdown-item.modify_animal_number').on('click', function(e) {
$(document).on('click', '.dropdown-item.modify_service', function(e) {

    e.stopPropagation();

    var par_service_id = $(this).data('service-id');

    var $row = $(this).closest('tr');

    var par_name = $row.find('input[name="name"]').val();
    var par_descriptoin = $row.find('input[name="description"]').val();
    var par_price = $row.find('input[name="price"]').val();

    $.ajax({
        type: 'post',
        url: '/modify_service',
        method: 'post',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        data: {
            service_id: par_service_id,
            name: par_name,
            description: par_descriptoin,
            price: par_price,
        },
        success: function (response) {
            console.log(response.message);
        },
        error: function (response) {
            console.error('Error modifying service data:');
        }
    })
});

$(document).ready(function() {
    $('#search_services').click(function() {
        var search = $('#search_inputs');
        var inputs = '<div class="form-group"><input type="text" id="search_id" name="search_id" class="form-control" placeholder="service" /></div>\n' +
            '<div class="form-group"><input type="text" id="search_name" name="search_name" class="form-control" placeholder="name"/></div>\n' +
            '<div class="form-group"><input type="text" id="search_description" name="search_description" class="form-control" placeholder="description"/></div>\n' +
            '<div class="form-group"><input type="number" id="search_price_min" name="search_price_min" class="form-control" placeholder="price min"/></div>\n' +
            '<div class="form-group"><input type="number" id="search_price_max" name="search_price_max" class="form-control" placeholder="price max"/></div>\n' +
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
        var par_name = $('#search_name').val();
        var par_description = $('#search_description').val();
        var par_price_min = $('#search_price_min').val();
        var par_price_max = $('#search_price_max').val();


        $.ajax({
            url: '/search_services',
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                service_id: par_id,
                name: par_name,
                description: par_description,
                price_min: par_price_min,
                price_max: par_price_max,

            },
            success: function(response) {
                console.log(response.message);
                var services = response.services;
                $('.card.p-3').remove();

                var serviceHtml = '<div class="card p-3">' +
                    '<table class="service_table" id="service_table">' +
                    '<thead>' +
                    '<tr>'+
                    '<th></th>'+
                    ' <th>Service No</th>'+
                    '<th>Name</th>'+
                    '<th>Description</th>'+
                    '<th>Price</th>'+
                    '</tr>' +
                    '</thead>' +
                    '<tbody>';

                services.forEach(function(service) {
                    serviceHtml += `<tr>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle no-caret" type="button"
                                    id="dropdownMenuButton"
                                    data-service-id="${service.id}"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                     fill="currentColor" class="bi bi-three-dots-vertical"
                                     viewBox="0 0 16 16">
                                    <path
                                        d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"></path>
                                </svg>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <li> <p class="dropdown-item offer_service" id="offer_service"
                                                            data-service-id="${service.id}">Offer</p></li>
                                <li><p class="dropdown-item modify_service"
                                       id="modify_service"
                                       data-service-id="${service.id}">Modify</p>
                                </li>
                                <li><p class="dropdown-item delete_service"
                                       id="delete_service"
                                       data-service-id="${service.id}">Delete</p>
                                </li>
                            </ul>
                        </div>
                    </td>
                    <td><input type="text" class="form-control" name="service_id"
                               value="${service.id}" disabled></td>
                    <td><input type="text" class="form-control" name="name"
                               value="${service.name}"></td>
                    <td><input type="text" class="form-control" name="description"
                               value="${service.description}"></td>
                    <td><input type="number" class="form-control" name="price"
                               value="${service.price}"></td>
                </tr>`;
                });

                serviceHtml +=       '</tbody>' +
                    '</table>' +
                    '</div>';

                $('#search_inputs').after(serviceHtml);
            },
            error: function(error) {
                console.error('Error searching animal_numbers:', error);
            }
        });
    });
});

document.getElementById('offer').addEventListener('click', function() {
    var dialog = document.getElementById('price_dialog');
    var par_service_id = dialog.getAttribute('data-service-id');
    var par_price = document.getElementById('new_price_offer').value;

    $.ajax({
        url: '/offer_service',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        data: {
            service_id: par_service_id,
            price: par_price,
        },
        success: function (response) {
            console.log(response.message);
            alert('Service offered sucessfully');
        },
        error: function (response) {
            console.error('Error offering service:');
            console.log('price -> ' + par_price);
        }
    });
    dialog.style.display = 'none';
});

document.getElementById('cancel_offer').addEventListener('click', function() {
    var dialog = document.getElementById('price_dialog');
    dialog.style.display = 'none';
});

$(document).on('click', '.dropdown-item.offer_service', function(e) {
    e.stopPropagation();

    var $this = $(this);
    var par_service_id = $(this).data('service-id');
    var dialog = document.getElementById('price_dialog');
    dialog.setAttribute('data-service-id', par_service_id);
    dialog.style.display = 'block';

});
