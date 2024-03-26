$(document).ready(function() {

    $('#add_new_employee').click(function() {
        var dialog = document.getElementById('employee_dialog');
        dialog.style.display = 'block';
    });
});

document.getElementById('cancel_employee').addEventListener('click', function() {
    var dialog = document.getElementById('employee_dialog');
    dialog.style.display = 'none';
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

document.getElementById('new_employee').addEventListener('click', function() {
    var dialog = document.getElementById('employee_dialog');

    var par_organisation = $('#organisation_select').val();
    var par_user = $('#user_select').val();

    var par_surname = document.getElementById('surname').value;
    var par_last_name = document.getElementById('last_name').value;
    var par_position = document.getElementById('position').value;
    var par_identification_number = document.getElementById('identification_number').value;
    var par_department = document.getElementById('department').value;
    var par_birth_date = document.getElementById('birth_date').value;
    var par_start_date = document.getElementById('start_date').value;


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
            $('.employee_table tbody').append(new_row);
        },
        error: function (error) {
            console.error('Error adding employee:', error);
            console.log(par_user, par_department, par_surname, par_organisation, par_position, par_birth_date);
        }
    });
});

$('.dropdown-item.delete_employee').on('click', function(e) {
    e.stopPropagation();

    var $this = $(this);
    var par_employee_id = $(this).data('employee-id');

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
            console.error('Error deleting organisation data:');
        }
    })
});


