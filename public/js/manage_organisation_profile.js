$(document).ready(function() {

    $('#add_organisation_info').click(function() {
        var dialog = document.getElementById('organisation_dialog');
        dialog.style.display = 'block';
    });
});

document.getElementById('cancel_organisation').addEventListener('click', function() {
    var dialog = document.getElementById('organisation_dialog');
    dialog.style.display = 'none';
});


$(document).ready(function () {
    $('.dropdown-menu').on('click', function (event) {
        event.stopPropagation();
    });

    $('.dropdown-item').on('click', function () {
        $(this).closest('.dropdown').find('[data-bs-toggle="dropdown"]').dropdown('hide');
    });
});

document.getElementById('new_organisation').addEventListener('click', function() {
    var dialog = document.getElementById('organisation_dialog');

    var par_organisation = document.getElementById('new_organisation_input').value;
    var par_email = document.getElementById('new_email').value;
    var par_phone_number = document.getElementById('new_phone_number').value;


    $.ajax({
        type: 'post',
        url: '/add_organisation',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data : {
            organisation: par_organisation,
            email: par_email,
            phone_number: par_phone_number,
        },
        success: function (response) {
            dialog.style.display = 'none';
            console.log(response.organisation);
            window.location.replace('/organisation/' + response.organisation.id);
        },
        error: function (error) {
            console.error('Error adding organisation:', error);
        }
    });
});

$(document).ready(function() {
    $('#modify_organisation_info').click(function() {
        var par_organisation_id = document.getElementById('organisation_id').value;
        var par_organisation = document.getElementById('organisation').value;
        var par_email = document.getElementById('email').value;
        var par_phone_number = document.getElementById('phone_number').value;

        $.ajax({
            type: 'post',
            url: '/modify_organisation',
            method: 'POST',
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
            },
            error: function (response) {
                console.error('Error saving organisation data:');
            }
        });

    });
});

$('#delete_organisation').on('click', function(e) {
    e.stopPropagation();

    var par_organisation_id = document.getElementById('organisation_id').value;

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
            window.location.replace('/organisations');
        },
        error: function (response) {
            console.error('Error deleting product data:');
        }
    })
});

$(document).ready(function() {
    //$(document).on('click', '.dropdown-item.delete_item', function(e) {
    $('.dropdown-item.delete_employee_line').on('click', function(e) {
        e.stopPropagation();

        var $this = $(this);
        var par_employee_id = $(this).data('employee-line-id');

        $.ajax({
            url: '/delete_employee',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            data: {
                employee_id: par_employee_id,
            },
            success: function (response) {
                console.log(response.message);
                $this.closest('tr').fadeOut(500, function() {
                    $(this).remove();
                });
            },
            error: function (response) {
                console.error('Error deleting employee data:');
            }
        })
    });
});

$('.dropdown-item.modify_emplyee_line').on('click', function(e) {

    e.stopPropagation();
    e.preventDefault();

    var par_employee_id = $(this).data('employee-line-id');

    var $row = $(this).closest('tr');

    var par_surname =  $row.find('input[name="surname"]').val();
    var par_last_name =  $row.find('input[name="last_name"]').val();
    var par_position =  $row.find('input[name="position"]').val();
    var par_identification_number =  $row.find('input[name="identification_number"]').val();
    var par_department =  $row.find('input[name="department"]').val();
    var par_email =  $row.find('input[name="email"]').val();
    var par_phone_number =  $row.find('input[name="phone_number"]').val();

    $.ajax({
        type: 'post',
        url: '/modify_employee',
        method: 'post',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        data: {
            employee_id: par_employee_id,
            surname: par_surname,
            last_name: par_last_name,
            position: par_position,
            identification_number: par_identification_number,
            department: par_department,
            email: par_email,
            phone_number: par_phone_number,
        },
        success: function (response) {
            console.log(response.employees);
        },
        error: function (response) {
            console.error('Error modifying item data:');
        }
    })
});

$('#add_employee_line').click(function() {
    var dialog = document.getElementById('employee_dialog');
    dialog.style.display = 'block';
});

document.getElementById('cancel_employee').addEventListener('click', function() {
    var dialog = document.getElementById('employee_dialog');
    dialog.style.display = 'none';
});

document.getElementById('new_employee').addEventListener('click', function() {
    var dialog = document.getElementById('employee_dialog');

    var par_user = $('#user_select').val();
    var par_organisation = document.getElementById('organisation_id').value;

    var par_surname = document.getElementById('new_surname').value;
    var par_last_name = document.getElementById('new_last_name').value;
    var par_position = document.getElementById('new_position').value;
    var par_identification_number = document.getElementById('new_identification_number').value;
    var par_department = document.getElementById('new_department').value;
    var par_birth_date = document.getElementById('new_birth_date').value;
    var par_start_date = document.getElementById('new_start_date').value;


    $.ajax({
        type: 'post',
        url: '/add_employee',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data : {
            organisation_id: par_organisation,
            user_id: par_user,
            surname: par_surname,
            last_name: par_last_name,
            position: par_position,
            identification_number: par_identification_number,
            department: par_department,
            start_date: par_start_date,
            birth_date: par_birth_date,
        },
        success: function (response) {
            dialog.style.display = 'none';
            console.log(response.employee);
            var employee = response.employee;
            var user = response.user;
            var new_row = `
                <tr>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle no-caret" type="button"
                                    id="dropdownMenuButton" data-employee-id="${employee.id}"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                     fill="currentColor" class="bi bi-three-dots-vertical"
                                     viewBox="0 0 16 16">
                                    <path
                                        d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"></path>
                                </svg>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <li><a href="/employee/${employee.id}"
                                       class="dropdown-item detail_employee" id="detail_employee"
                                       data-employee-id="${employee.id}">Detail</a></li>
                                <li><p class="dropdown-item modify_employee" id="modify_employee"
                                       data-employee-id="${employee.id}">Modify</p></li>
                                <li><p class="dropdown-item delete_employee" id="delete_employee"
                                       data-employee-id="${employee.id}">Delete</p></li>
                            </ul>
                        </div>
                    </td>
                    <td><input type="text" class="form-control" name="employee_number"
                               value="${employee.id}" disabled></td>
                    <td><input type="text" class="form-control" name="surname"
                               value="${employee.surname}"></td>
                    <td><input type="text" class="form-control" name="last_name"
                               value="${employee.last_name}"></td>
                    <td><input type="text" class="form-control" name="position"
                               value="${employee.position}" ${user.role !== 'admin' ? 'disabled' : ''}></td>
                    <td><input type="text" class="form-control" name="identification_number"
                               value="${employee.identification_number}"></td>
                    <td><input type="text" class="form-control" name="department"
                               value="${employee.department}" ${user.role !== 'admin' ? 'disabled' : ''}></td>
                    <td><input type="text" class="form-control" name="email"
                               value="${employee.email}"></td>
                    <td><input type="text" class="form-control" name="phone_number"
                               value="${employee.phone_number}"></td>
                </tr>`;
            $('.employee_line_table tbody').append(new_row);
        },
        error: function(xhr, status, error) {
            console.error('Error saving Order data:')
        }
    });
});

$(document).ready(function() {
    $('#user_select').select2({
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
        placeholder: 'Select a user',
        minimumInputLength: 1,
        minimumResultsForSearch: 0,
        width: '100%',
    });
});
