$(document).ready(function() {

    $('#add_new_company').click(function() {
        var dialog = document.getElementById('company_dialog');
        dialog.style.display = 'block';
    });
});

document.getElementById('cancel_company').addEventListener('click', function() {
    var dialog = document.getElementById('company_dialog');
    dialog.style.display = 'none';
});

document.getElementById('new_company').addEventListener('click', function() {
    var dialog = document.getElementById('company_dialog');
    var par_company = document.getElementById('new_company_name').value;
    var par_email = document.getElementById('new_email').value;
    var par_phone_number = document.getElementById('new_phone_number').value;
    var par_ico = document.getElementById('new_ico').value;
    var par_dic = document.getElementById('new_dic').value;
    var par_company_type = document.getElementById('new_company_type').value;

    var errors = [];

    if (!par_company) errors.push("Company name is required.");
    if (!par_email) errors.push("Email is required.");
    if (!par_phone_number) errors.push("Phone number is required.");
    if (!par_ico) errors.push("ICO is required.");
    if (!par_dic) errors.push("DIC is required.");
    if (!par_company_type) errors.push("Company type is required.");

    if (errors.length > 0) {
        alert("Please fill out all fields.\n" + errors.join("\n"));
        return;
    }

    $.ajax({
        type: 'post',
        url: '/add_company',
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        data: {
            company: par_company,
            email: par_email,
            phone_number: par_phone_number,
            ICO: par_ico,
            DIC: par_dic,
            type: par_company_type,

        },
        success: function (response) {
            dialog.style.display = 'none';
            console.log(response.company);
            var company = response.company;

            var new_row =
                `<tr>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle no-caret" type="button"
                                    id="dropdownMenuButton" data-company-id="${ company.id }"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                     fill="currentColor" class="bi bi-three-dots-vertical"
                                     viewBox="0 0 16 16">
                                    <path
                                        d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"></path>
                                </svg>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <li><a href="/company/${ company.id }"
                                       class="dropdown-item detail_company" id="detail_company"
                                       data-company-id="${ company.id }">Detail</a></li>
                                <li><p class="dropdown-item modify_company" id="modify_company"
                                       data-company-id="${ company.id }">Modify</p></li>
                                <li><p class="dropdown-item delete_company" id="delete_company"
                                       data-company-id="${ company.id }">Delete</p></li>
                            </ul>
                        </div>
                    </td>
                    <td><input type="text" class="form-control" name="company_number"
                               value="${ company.id }" disabled></td>
                    <td><input type="text" class="form-control" name="company"
                               value="${ company.company }" ></td>
                    <td><input type="text" class="form-control" name="email"
                               value="${ company.email }" ></td>
                    <td><input type="text" class="form-control" name="phone_number"
                               value="${ company.phone_number }" ></td>
                    <td><input type="text" class="form-control" name="ico"
                               value="${ company.ICO}" ></td>
                    <td><input type="text" class="form-control" name="dic"
                               value="${ company.DIC }" ></td>
                </tr>`;

            $('.company_table tbody').append(new_row);
        },
        error: function (response) {
            console.error('Error saving company data:');
        }
    });
});

//$('.dropdown-item.delete_company').on('click', function(e) {
$(document).on('click', '.dropdown-item.delete_company', function(e) {
    e.stopPropagation();

    var $this = $(this);
    var par_company_id = $(this).data('company-id');

    $.ajax({
        url: '/delete_company',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        data: {
            company_id: par_company_id,
        },
        success: function (response) {
            console.log(response.message);
            $this.closest('tr').fadeOut(500, function() {
                $(this).remove();
            });
        },
        error: function (response) {
            console.error('Error deleting product data:');
        }
    })
});

//$('.dropdown-item.modify_company').on('click', function(e) {
$(document).on('click', '.dropdown-item.modify_company', function(e) {

        e.stopPropagation();

    var par_company_id = $(this).data('company-id');

    var $row = $(this).closest('tr');

    var par_company = $row.find('input[name="company"]').val();
    var par_email = $row.find('input[name="email"]').val();
    var par_phone_number = $row.find('input[name="phone_number"]').val();
    var par_ico = $row.find('input[name="ico"]').val();
    var par_dic = $row.find('input[name="dic"]').val();
    var par_type = document.getElementById('new_company_type').value;

    $.ajax({
        type: 'post',
        url: '/modify_company',
        method: 'post',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        data: {
            company_id: par_company_id,
            company: par_company,
            email: par_email,
            phone_number: par_phone_number,
            type: par_type,
            ICO: par_ico,
            DIC: par_dic,
        },
        success: function (response) {
            console.log(response.message);
        },
        error: function (response) {
            console.error('Error modifying company data:');
        }
    })
});

$(document).ready(function() {
    $('#search_companies').click(function() {
        var search = $('#search_inputs');
        var inputs = '<div class="form-group"><input type="text" id="search_id" name="search_id" class="form-control" placeholder="company number" /></div>\n' +
            '<div class="form-group"><input type="text" id="search_company" name="search_company" class="form-control" placeholder="company"/></div>\n' +
            '<div class="form-group"><input type="email" id="search_email" name="search_email" class="form-control" placeholder="email"/></div>\n' +
            '<div class="form-group"><input type="text" id="search_phone_number" name="search_phone_number" class="form-control" placeholder="phone number"/></div>\n' +
            '<div class="form-group"><input type="text" id="search_ico" name="search_ico" class="form-control" placeholder="ICO"/></div>\n' +
            '<div class="form-group"><input type="text" id="search_dic" name="search_dic" class="form-control" placeholder="DIC"/></div>\n' +
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
        var par_company = $('#search_company').val();
        var par_email = $('#search_email').val();
        var par_phone_number = $('#search_phone_number').val();
        var par_ico = $('#search_ico').val();
        var par_dic = $('#search_dic').val();
        var par_type = document.getElementById('new_company_type').value;

        $.ajax({
            url: '/search_companies',
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                company_id: par_id,
                company: par_company,
                email: par_email,
                type: par_type,
                phone_number: par_phone_number,
                ICO: par_ico,
                DIC: par_dic,
            },
            success: function(response) {
                console.log(response.message);
                var companies = response.companies;
                $('.card.p-3').remove();

                var companyHtml = '<div class="card p-3">' +
                    '<table class="company_table" id="company_table">' +
                    '<thead>' +
                    '<tr>'+
                    '<th></th>'+
                    ' <th>Company number</th>'+
                    '<th>Company</th>'+
                    '<th>Email</th>'+
                    '<th>Phone number</th>'+
                    '<th>ICO</th>'+
                    '<th>DIC</th>'+
                    '</tr>' +
                    '</thead>' +
                    '<tbody>';

                companies.forEach(function(company) {
                    companyHtml += `<tr>
                        <td>
                        <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle no-caret" type="button"
                    id="dropdownMenuButton" data-company-id="${ company.id }"
                    data-bs-toggle="dropdown" aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                    fill="currentColor" class="bi bi-three-dots-vertical"
                    viewBox="0 0 16 16">
                        <path
                    d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"></path>
                </svg>
                </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <li><a href="/company/${ company.id }"
                               class="dropdown-item detail_company" id="detail_company"
                               data-company-id="${ company.id }">Detail</a></li>
                        <li><p class="dropdown-item modify_company" id="modify_company"
                               data-company-id="${ company.id }">Modify</p></li>
                        <li><p class="dropdown-item delete_company" id="delete_company"
                               data-company-id="${ company.id }">Delete</p></li>
                    </ul>
                </div>
                </td>
                    <td><input type="text" class="form-control" name="company_number"
                               value="${ company.id }" disabled></td>
                    <td><input type="text" class="form-control" name="company"
                               value="${ company.company }" ></td>
                    <td><input type="text" class="form-control" name="email"
                               value="${ company.email }" ></td>
                    <td><input type="text" class="form-control" name="phone_number"
                               value="${ company.phone_number }" ></td>
                    <td><input type="text" class="form-control" name="ico"
                               value="${ company.ICO}" ></td>
                    <td><input type="text" class="form-control" name="dic"
                               value="${ company.DIC }" ></td>
                </tr>`;
                });

                companyHtml +=       '</tbody>' +
                    '</table>' +
                    '</div>';

                $('#search_inputs').after(companyHtml);
            },
            error: function(error) {
                console.error('Error searching orders:', error);
            }
        });
    });
});
