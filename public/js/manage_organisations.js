$(document).ready(function() {

    $('#add_new_organisation').click(function() {
        var dialog = document.getElementById('organisation_dialog');
        dialog.style.display = 'block';
    });
});

document.getElementById('cancel_organisation').addEventListener('click', function() {
    var dialog = document.getElementById('organisation_dialog');
    dialog.style.display = 'none';
});

document.getElementById('new_organisation').addEventListener('click', function() {
    var dialog = document.getElementById('organisation_dialog');
    var par_organisation = document.getElementById('organisation').value;
    var par_email = document.getElementById('email').value;
    var par_phone_number = document.getElementById('phone_number').value;

    var errors = [];

    if (!par_organisation) errors.push("Organisation is required.");
    if (!par_email) errors.push("Email is required.");
    if (!par_phone_number) errors.push("Phone number is required.");


    if (errors.length > 0) {
        alert("Please fill out all fields.\n" + errors.join("\n"));
        return;
    }

    $.ajax({
        type: 'post',
        url: '/add_organisation',
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        data: {
            organisation: par_organisation,
            email: par_email,
            phone_number: par_phone_number,
        },
        success: function (response) {
            dialog.style.display = 'none';
            console.log(response.organisation);
            var organisation = response.organisation;

            var new_row = `
                                    <tr>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-secondary dropdown-toggle no-caret" type="button"
                                                        id="dropdownMenuButton" data-organisation-id="${organisation.id}"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                         fill="currentColor" class="bi bi-three-dots-vertical"
                                                         viewBox="0 0 16 16">
                                                        <path
                                                            d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"></path>
                                                    </svg>
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <li><a href="/organisation/${organisation.id}"
                                                           class="dropdown-item detail_organisation" id="detail_organisation"
                                                           data-organisation-id="${organisation.id}">Detail</a></li>
                                                    <li><p class="dropdown-item modify_organisation" id="modify_organisation"
                                                           data-organisation-id="${organisation.id}">Modify</p></li>
                                                    <li><p class="dropdown-item delete_organisation" id="delete_organisation"
                                                           data-organisation-id="${organisation.id}">Delete</p></li>
                                                </ul>
                                            </div>
                                        </td>
                                        <td><input type="text" class="form-control" name="organisation_number"
                                                   value="${organisation.id}" disabled></td>
                                        <td>
                                            <input type="text" class="form-control" name="organisation"
                                                   value="${organisation.organisation}">
                                        </td>
                                        <td><input type="text" class="form-control" name="email"
                                                   value="${organisation.email}"></td>
                                        <td><input type="text" class="form-control" name="phone_number"
                                                   value="${organisation.phone_number}"></td>
                                        <td><input type="text" class="form-control" name="num_of_employees"
                                                   value="${organisation.num_of_employees}" disabled></td>
                                    </tr>`;


            $('.organisations_table tbody').append(new_row);
            alert(response.message);
        },
        error: function (response) {
            console.error('Error saving Organisation data:');
            console.log(par_organisation, par_email, par_phone_number);
        }
    });
});

//$('.dropdown-item.delete_organisation').on('click', function(e) {
$(document).on('click', '.dropdown-item.delete_organisation', function(e) {
    e.stopPropagation();

    var $this = $(this);
    var par_organisation_id = $(this).data('organisation-id');

    $.ajax({
        url: '/delete_organisation',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        data: {
            organisation_id: par_organisation_id,
        },
        success: function (response) {
            console.log(response.message);
            $this.closest('tr').fadeOut(500, function() {
                $(this).remove();
            });
            alert(response.message);
        },
        error: function (response) {
            console.error('Error deleting organisation data:');
        }
    })
});

//$('.dropdown-item.modify_organisation').on('click', function(e) {
    $(document).on('click', '.dropdown-item.modify_organisation', function(e) {
    e.stopPropagation();

    var par_organisation_id = $(this).data('organisation-id');

    var $row = $(this).closest('tr');

    var par_organisation =  $row.find('input[name="organisation"]').val();
    var par_email =  $row.find('input[name="email"]').val();
    var par_phone_number =  $row.find('input[name="phone_number"]').val();

    $.ajax({
        type: 'post',
        url: '/modify_organisation',
        method: 'post',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        data: {
            organisation_id: par_organisation_id,
            organisation: par_organisation,
            email: par_email,
            phone_number: par_phone_number,
        },
        success: function (response) {
            console.log(response.message);
            alert(response.message);
        },
        error: function (response) {
            console.error('Error modifying organisation data:');
            console.log(par_organisation_id, par_email, par_phone_number, par_organisation);
        }
    })
});

$(document).ready(function() {
    $('#search_organisations').click(function() {
        var search = $('#search_inputs');
        var inputs =
            '<div class="form-group"><input type="number" id="search_organisation_id" name="search_organisation_id" class="form-control" placeholder="Organisation id"/></div>\n' +
            '<div class="form-group"><input type="text" id="search_organisation" name="search_organisation" class="form-control" placeholder="Organisation"/></div>\n' +
            '<div class="form-group"><input type="email" id="search_email" name="search_email" class="form-control" placeholder="Email"/></div>\n' +
            '<div class="form-group"><input type="text" id="search_phone_number" name="search_phone_number" class="form-control" placeholder="Phone number"/></div>\n' +
            '<div class="form-group"><input type="number" id="search_num_of_empl_min" name="search_num_of_empl_min" class="form-control" placeholder="Num of empl. min"/></div>\n' +
            '<div class="form-group"><input type="number" id="search_num_of_empl_max" name="search_num_of_empl_max" class="form-control" placeholder="Num of empl. max"/></div>\n' +
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
        var par_id = $('#search_organisation_id').val();
        var par_organisation = $('#search_organisation').val();
        var par_email = $('#search_email').val();
        var par_phone_number = $('#search_phone_number').val();
        var par_empl_min = $('#search_num_of_empl_min').val();
        var par_empl_max= $('#search_num_of_empl_max').val();

        $.ajax({
            url: '/search_organisations',
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                organisation_id: par_id,
                 organisation: par_organisation,
                 email: par_email,
                 phone_number: par_phone_number,
                 empl_min: par_empl_min,
                 empl_max: par_empl_max,
            },
            success: function(response) {
                console.log(response.message);
                var organisations = response.organisations;
                $('.card.p-3').remove();

                var organisationHtml = '<div class="card p-3">' +
                    '<table class="organisations_table" id="organisations_table">' +
                    '<thead>' +
                    '<tr>' +
                    '<th></th>' +
                    '<th>Organisation Number</th>' +
                    '<th>Organisation Name</th>' +
                    '<th>Email</th>' +
                    '<th>Phone Number</th>' +
                    '<th>Num. of Employees</th>' +
                    '</tr>' +
                    '</thead>' +
                    '<tbody>';

                organisations.forEach(function(organisation) {
                    organisationHtml += '<tr>' +
                        '<td>' +
                        '<div class="dropdown">' +
                        '<button class="btn btn-secondary dropdown-toggle no-caret" type="button" id="dropdownMenuButton' + organisation.id + '" data-organisation-id="' + organisation.id + '" data-bs-toggle="dropdown" aria-expanded="false">' +
                        '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">' +
                        '<path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"></path>' +
                        '</svg>' +
                        '</button>' +
                        '<ul class="dropdown-menu" aria-labelledby="dropdownMenuButton' + organisation.id + '">' +
                        '<li><a href="/organisation/' + organisation.id + '" class="dropdown-item detail_organisation" id="detail_organisation" data-organisation-id="' + organisation.id + '">Detail</a></li>' +
                        '<li><p class="dropdown-item modify_organisation" id="modify_organisation" data-organisation-id="' + organisation.id + '">Modify</p></li>' +
                        '<li><p class="dropdown-item delete_organisation" id="delete_organisation" data-organisation-id="' + organisation.id + '">Delete</p></li>' +
                        '</ul>' +
                        '</div>' +
                        '</td>' +
                        '<td><input type="text" class="form-control" name="organisation_number" value="' + organisation.id + '" disabled></td>' +
                        '<td><input type="text" class="form-control" name="organisation" value="' + organisation.organisation + '"></td>' +
                        '<td><input type="text" class="form-control" name="email" value="' + organisation.email + '"></td>' +
                        '<td><input type="text" class="form-control" name="phone_number" value="' + organisation.phone_number + '"></td>' +
                        '<td><input type="text" class="form-control" name="num_of_employees" value="' + organisation.num_of_employees + '" disabled></td>' +
                        '</tr>';
                });

                organisationHtml +=       '</tbody>' +
                    '</table>' +
                    '</div>';

                $('#search_inputs').after(organisationHtml);
            },
            error: function(error) {
                console.error('Error searching organisations:', error);
            }
        });
    });
});
