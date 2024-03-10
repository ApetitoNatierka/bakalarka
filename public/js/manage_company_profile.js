
$(document).ready(function() {
    $('#add_company_info').click(function() {
        var par_company = document.getElementById('company').value;
        var par_email = document.getElementById('email').value;
        var par_phone_number = document.getElementById('phone_number').value;
        var par_type = document.getElementById('type').value;

        $.ajax({
            type: 'post',
            url: '/add_company_info',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            data: {
                company: par_company,
                email: par_email,
                phone_number: par_phone_number,
                type: par_type,
            },
            success: function(response) {
                if(response.success) {
                    console.log(response.message);
                    window.location.href = '/company_profile';
                }
            },
            error: function(response) {
                console.error('Error saving user data:');
            }
        });
    });
});

$(document).ready(function() {
    $('#modify_company_info').click(function() {
        var par_company = document.getElementById('company').value;
        var par_email = document.getElementById('email').value;
        var par_phone_number = document.getElementById('phone_number').value;
        var par_type = document.getElementById('type').value;
        var par_company_id = document.getElementById('company_id').value;

        $.ajax({
            type: 'post',
            url: '/modify_company_info',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            data: {
                company_id: par_company_id,
                company: par_company,
                email: par_email,
                phone_number: par_phone_number,
                type: par_type,
            },
            success: function (response) {
                console.log(response.message);
            },
            error: function (response) {
                console.error('Error saving user data:');
            }
        });

    });
});

$('#add_user_line').click(function() {
    var dialog = document.getElementById('user_dialog');
    dialog.style.display = 'block';
});

document.getElementById('new_user').addEventListener('click', function() {
    var dialog = document.getElementById('user_dialog');
    var par_name = document.getElementById('name').value;
    var par_email = document.getElementById('email').value;
    var par_phone_number = document.getElementById('phone_number').value;
    var par_company_position = document.getElementById('company_position').value;
    var par_password = document.getElementById('password').value;
    var par_company_id = document.getElementById('company_id').value;

    $.ajax({
        type: 'post',
        url: '/add_new_user_line',
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        data: {
            company_id: par_company_id,
            name: par_name,
            email: par_email,
            phone_number: par_phone_number,
            company_position: par_company_position,
            password: par_password,

        },
        success: function (response) {
            dialog.style.display = 'none';
            console.log(response.user_line);
            var user_line = response.user_line;

            var new_row =
                `<tr>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle no-caret" type="button" id="dropdownMenuButton" data-user-line-id="${user_line.id}" data-bs-toggle="dropdown" aria-expanded="false">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                                    <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"></path>
                                </svg>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <li><p class="dropdown-item modify_user_line" data-user-line-id="${user_line.id}">Modify</p></li>
                                <li><p class="dropdown-item delete_user_line" data-user-line-id="${user_line.id}">Delete</p></li>
                            </ul>
                        </div>
                    </td>
                    <td><input type="text" class="form-control" name="name" value="${user_line.name}"></td>
                    <td><input type="text" class="form-control" name="email" value="${user_line.email}"></td>
                    <td><input type="text" class="form-control" name="phone_number" value="${user_line.phone_number}"></td>
                    <td><input type="text" class="form-control" name="company_position" value="${user_line.company_position}"></td>
                </tr>`;

            $('.users_line_table tbody').append(new_row);
        },
        error: function (response) {
            console.error('Error saving user data:');
        }
    });
});

$('.dropdown-item.modify_user_line').on('click', function(e) {
    e.stopPropagation();

    var par_user_line_id = $(this).data('user-line-id');

    var $row = $(this).closest('tr');

    var par_name = $row.find('input[name="name"]').val();
    var par_email = $row.find('input[name="email"]').val();
    var par_phone_number = $row.find('input[name="phone_number"]').val();
    var par_company_position = $row.find('input[name="company_position"]').val();

    $.ajax({
        type: 'post',
        url: '/modify_user_line',
        method: 'post',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        data: {
            name: par_name,
            email: par_email,
            phone_number: par_phone_number,
            company_position: par_company_position,
            user_line_id: par_user_line_id,
        },
        success: function (response) {
            console.log(response.message);
        },
        error: function (response) {
            console.error('Error modifying user data:');
        }
    })
});

$('.dropdown-item.delete_user_line').on('click', function(e) {
    e.stopPropagation();

    var $this = $(this);
    var par_user_line_id = $(this).data('user-line-id');

    $.ajax({
        url: '/delete_user_line',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        data: {
            user_line_id: par_user_line_id,
        },
        success: function (response) {
            console.log(response.message);
            $this.closest('tr').fadeOut(500, function() {
                $(this).remove();
            });
        },
        error: function (response) {
            console.error('Error deleting user data:');
        }
    })
});

$(document).ready(function() {
    $('.dropdown-menu').on('click', function(event) {
        event.stopPropagation();
    });

    $('.dropdown-item').on('click', function() {
        $(this).closest('.dropdown').find('[data-bs-toggle="dropdown"]').dropdown('hide');
    });
});

document.getElementById('cancel_user').addEventListener('click', function() {
    var dialog = document.getElementById('user_dialog');
    dialog.style.display = 'none';
});
