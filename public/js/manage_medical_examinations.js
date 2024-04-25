$(document).ready(function() {

    $('#add_new_medical_examination').click(function() {
        var dialog = document.getElementById('medical_examination_dialog');
        dialog.style.display = 'block';
    });
});

document.getElementById('cancel_medical_examination').addEventListener('click', function() {
    var dialog = document.getElementById('medical_examination_dialog');
    dialog.style.display = 'none';
});

document.getElementById('new_medical_examination').addEventListener('click', function() {
    var dialog = document.getElementById('medical_examination_dialog');

    var par_medical_no = document.getElementById('new_medical_examination_no').value;
    var par_description = document.getElementById('new_description').value;

    var errors = [];

    if (!par_medical_no) errors.push("Medical number is required.");
    if (!par_description) errors.push("Description is required.");

    if (errors.length > 0) {
        alert("Please fill out all fields.\n" + errors.join("\n"));
        return;
    }

    $.ajax({
        type: 'post',
        url: '/add_medical_examination',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data : {
            medical_examination: par_medical_no,
            description: par_description,
        },
        success: function (response) {
            dialog.style.display = 'none';
            console.log(response.medical_examination);
            var medical_examination = response.medical_examination;
            var new_row = `
                <tr>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle no-caret" type="button"
                                    id="dropdownMenuButton" data-medical_examination-id="${medical_examination.id}"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                     fill="currentColor" class="bi bi-three-dots-vertical"
                                     viewBox="0 0 16 16">
                                    <path
                                        d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"></path>
                                </svg>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <li><p class="dropdown-item modify_medical_examination" id="modify_medical_examination"
                                       data-medical_examination-id="${medical_examination.id}">Modify</p></li>
                                <li><p class="dropdown-item delete_medical_examination" id="delete_medical_examination"
                                       data-medical_examination-id="${medical_examination.id}">Delete</p></li>
                            </ul>
                        </div>
                    </td>
                    <td><input type="text" class="form-control" name="medical_examination_id"
                               value="${medical_examination.id}" disabled></td>
                    <td><input type="text" class="form-control" name="medical_examination"
                               value="${medical_examination.medical_examination}"></td>
                    <td><input type="text" class="form-control" name="description"
                               value="${medical_examination.description}"></td>
                </tr>`;
            $('.medical_examination_table tbody').append(new_row);
            alert(response.message);
        },
        error: function (error) {
            console.error('Error adding medical examination:', error);
            console.log(par_medical_no, par_description);
        }
    });
});

$(document).on('click', '.dropdown-item.delete_medical_examination', function(e) {
    e.stopPropagation();

    var $this = $(this);
    var par_medical_examination_id = $(this).data('medical_examination-id');

    $.ajax({
        url: '/delete_medical_examination',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        data: {
            medical_examination_id: par_medical_examination_id,
        },
        success: function (response) {
            console.log(response.message);
            $this.closest('tr').fadeOut(500, function() {
                $(this).remove();
            });
            alert(response.message);
        },
        error: function (response) {
            console.error('Error deleting medical examination data:');
        }
    })
});

$(document).on('click', '.dropdown-item.modify_medical_examination', function(e) {

    e.stopPropagation();

    var par_medical_examination_id = $(this).data('medical_examination-id');

    var $row = $(this).closest('tr');

    var par_medical_no = $row.find('input[name="medical_examination"]').val();
    var par_description = $row.find('input[name="description"]').val();

    $.ajax({
        type: 'post',
        url: '/modify_medical_examination',
        method: 'post',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        data: {
            medical_examination_id: par_medical_examination_id,
            medical_examination: par_medical_no,
            description: par_description,
        },
        success: function (response) {
            console.log(response.message);
            alert(response.message);
        },
        error: function (response) {
            console.error('Error modifying medical examination data:');
        }
    })
});

$(document).ready(function() {
    $('#search_medical_examinations').click(function() {
        var search = $('#search_inputs');
        var inputs = '<div class="form-group"><input type="text" id="search_id" name="search_id" class="form-control" placeholder="medical examination id" /></div>\n' +
            '<div class="form-group"><input type="text" id="search_medical_examination" name="search_medical_examination" class="form-control" placeholder="medical examination"/></div>\n' +
            '<div class="form-group"><input type="text" id="search_description" name="search_description" class="form-control" placeholder="description"/></div>\n' +
            '<div class="form-group"><button id="search_button" class="btn btn-primary" style="border-radius: 5px">Search</button></div>';

        if (search.is(':empty')) {
            search.append(inputs);
        } else {
            search.empty();
        }
    });
});

$(document).ready(function() {
    $(document).on('click', '#search_button', function() {
        var par_id = $('#search_id').val();
        var par_medical_examination = $('#search_medical_examination').val();
        var par_description = $('#search_description').val();

        $.ajax({
            url: '/search_medical_examinations',
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                medical_examination_id: par_id,
                medical_examination: par_medical_examination,
                description: par_description,
            },
            success: function(response) {
                console.log(response.message);
                var medical_examinations = response.medical_examinations;
                $('.card.p-3').remove();

                var medical_examinationHtml = '<div class="card p-3">' +
                    '<table class="medical_examination_table" id="medical_examination_table">' +
                    '<thead>' +
                    '<tr>'+
                    '<th></th>'+
                    ' <th>Medical Exam Id</th>'+
                    '<th>Medical Exam</th>'+
                    '<th>Description</th>'+
                    '</tr>' +
                    '</thead>' +
                    '<tbody>';

                medical_examinations.forEach(function(medical_examination) {
                    medical_examinationHtml += `<tr>
                        <td>
                        <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle no-caret" type="button"
                    id="dropdownMenuButton" data-medical_examination-id="${medical_examination.id}"
                    data-bs-toggle="dropdown" aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                    fill="currentColor" class="bi bi-three-dots-vertical"
                    viewBox="0 0 16 16">
                        <path
                    d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"></path>
                </svg>
                </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <li><a href="/medical_examination/${medical_examination.id}"
                               class="dropdown-item detail_medical_examination" id="detail_medical_examination"
                               data-medical_examination-id="${medical_examination.id}">Detail</a></li>
                        <li><p class="dropdown-item modify_medical_examination" id="modify_medical_examination"
                               data-medical_examination-id="${medical_examination.id}">Modify</p></li>
                        <li><p class="dropdown-item delete_medical_examination" id="delete_medical_examination"
                               data-medical_examination-id="${medical_examination.id}">Delete</p></li>
                    </ul>
                </div>
                <td><input type="text" class="form-control" name="medical_examination_id"
                                                   value="${medical_examination.id}" disabled></td>
                                        <td><input type="text" class="form-control" name="medical_examination"
                                                   value="${medical_examination.medical_examination}"></td>
                                        <td><input type="text" class="form-control" name="description"
                                                   value="${medical_examination.description}"></td>
                </tr>`;
                });

                medical_examinationHtml +=       '</tbody>' +
                    '</table>' +
                    '</div>';

                $('#search_inputs').after(medical_examinationHtml);
            },
            error: function(error) {
                console.error('Error searching medical_examinations:', error);
            }
        });
    });
});


