$('#add_address_line').click(function() {
    var dialog = document.getElementById('address_dialog');
    dialog.style.display = 'block';
});

document.getElementById('new_address').addEventListener('click', function() {
    var dialog = document.getElementById('address_dialog');
    var errors = [];
    var par_country = document.getElementById('country').value;
    var par_postal_code = document.getElementById('postal_code').value;
    var par_region = document.getElementById('region').value;
    var par_city = document.getElementById('city').value;
    var par_house_number = document.getElementById('house_number').value;
    var par_street = document.getElementById('street').value;
    var par_entity_type = document.getElementById('entity_type').value;

    if (!par_country) errors.push("Country is required.");
    if (!par_postal_code) errors.push("Postal code is required.");
    if (!par_city) errors.push("City is required.");
    if (!par_region) errors.push("Region is required.");
    if (!par_house_number) errors.push("House number is required.");
    if (!par_street) errors.push("Street is required.");

    if (errors.length > 0) {
        alert("Please fill out all fields.\n" + errors.join("\n"));
        return;
    }

    $.ajax({
        type: 'post',
        url: '/add_new_address_line',
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        data: {
            entity_type: par_entity_type,
            street: par_street,
            house_number: par_house_number,
            city: par_city,
            region: par_region,
            postal_code: par_postal_code,
            country: par_country,
        },
        success: function (response) {
            dialog.style.display = 'none';
            console.log(response.address_line);
            var address_line = response.address_line;

            var new_row =
                `<tr>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle no-caret" type="button" id="dropdownMenuButton" data-address-line-id="${address_line.id}" data-bs-toggle="dropdown" aria-expanded="false">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                                    <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"></path>
                                </svg>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <li><p class="dropdown-item modify_address_line" data-address-line-id="${address_line.id}">Modify</p></li>
                                <li><p class="dropdown-item delete_address_line" data-address-line-id="${address_line.id}">Delete</p></li>
                            </ul>
                        </div>
                    </td>
                    <td><input type="text" class="form-control" name="street" value="${address_line.street}"></td>
                    <td><input type="text" class="form-control" name="house_number" value="${address_line.house_number}"></td>
                    <td><input type="text" class="form-control" name="city" value="${address_line.city}"></td>
                    <td><input type="text" class="form-control" name="region" value="${address_line.region}"></td>
                    <td><input type="text" class="form-control" name="postal_code" value="${address_line.postal_code}"></td>
                    <td><input type="text" class="form-control" name="country" value="${address_line.country}"></td>
                </tr>`;

            $('.address_line_table tbody').append(new_row);
        },
        error: function (response) {
            console.error('Error saving address data:');
        }
    });
});

//$('.dropdown-item.modify_address_line').on('click', function(e) {
$(document).on('click', '.dropdown-item.modify_address_line', function(e) {

        e.stopPropagation();

    var par_address_line_id = $(this).data('address-line-id');
    var par_entity_type = document.getElementById('entity_type').value;

    var $row = $(this).closest('tr');

    var par_country = $row.find('input[name="country"]').val();
    var par_postal_code = $row.find('input[name="postal_code"]').val();
    var par_region = $row.find('input[name="region"]').val();
    var par_city = $row.find('input[name="city"]').val();
    var par_house_number = $row.find('input[name="house_number"]').val();
    var par_street = $row.find('input[name="street"]').val();

    $.ajax({
        type: 'post',
        url: '/modify_address_line',
        method: 'post',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        data: {
            address_line_id: par_address_line_id,
            entity_type: par_entity_type,
            street: par_street,
            house_number: par_house_number,
            city: par_city,
            region: par_region,
            postal_code: par_postal_code,
            country: par_country,
        },
        success: function (response) {
            console.log(response.message);
        },
        error: function (response) {
            console.error('Error modifying adddress data:');
        }
    })
});

//$('.dropdown-item.delete_address_line').on('click', function(e) {
$(document).on('click', '.dropdown-item.delete_address_line', function(e) {

    e.stopPropagation();

    var $this = $(this);
    var par_address_line_id = $(this).data('address-line-id');
    var par_entity_type = document.getElementById('entity_type').value;

    $.ajax({
        url: '/delete_address_line',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        data: {
            address_line_id: par_address_line_id,
            entity_type: par_entity_type,
        },
        success: function (response) {
            console.log(response.message);
            $this.closest('tr').fadeOut(500, function() {
                $(this).remove();
            });
        },
        error: function (response) {
            console.error('Error deleting address data:');
        }
    })
});

document.getElementById('cancel_address').addEventListener('click', function() {
    var dialog = document.getElementById('address_dialog');
    dialog.style.display = 'none';
});

$(document).ready(function() {
    $('.dropdown-menu').on('click', function(event) {
        event.stopPropagation();
    });

    $('.dropdown-item').on('click', function() {
        $(this).closest('.dropdown').find('[data-bs-toggle="dropdown"]').dropdown('hide');
    });
});
