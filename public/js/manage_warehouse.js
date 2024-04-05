$(document).ready(function() {

    $('#add_new_warehouse').click(function() {
        var dialog = document.getElementById('warehouse_dialog');
        dialog.style.display = 'block';
    });
});

document.getElementById('cancel_warehouse').addEventListener('click', function() {
    var dialog = document.getElementById('warehouse_dialog');
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
    $('#user_select_form').select2({
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

document.getElementById('new_warehouse').addEventListener('click', function() {
    var dialog = document.getElementById('warehouse_dialog');

    var par_user = $('#user_select').val();

    var par_warehouse = document.getElementById('new_warehouse_name').value;
    var par_capacity = document.getElementById('new_capacity').value;
    var par_location = document.getElementById('new_location').value;


    $.ajax({
        type: 'post',
        url: '/add_warehouse_form',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data : {
            user_id: par_user,
            warehouse: par_warehouse,
            capacity: par_capacity,
            location: par_location,
        },
        success: function (response) {
            dialog.style.display = 'none';
            console.log(response.warehouse);
            window.location.replace('/warehouse/' + response.warehouse.id);
        },
        error: function (error) {
            console.error('Error adding warehouse:', error);
            console.log(par_user,par_capacity,par_location,par_warehouse);
        }
    });
});

$(document).ready(function() {
    $('#modify_warehouse').click(function() {
        var par_warehouse_id = document.getElementById('warehouse_id').value;
        var par_warehouse = document.getElementById('warehouse').value;
        var par_location = document.getElementById('location').value;
        var par_capacity = document.getElementById('capacity').value;
        var par_user = document.getElementById('user_select_form').value;

        $.ajax({
            type: 'post',
            url: '/modify_warehouse',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            data: {
                warehouse_id: par_warehouse_id,
                warehouse: par_warehouse,
                location: par_location,
                capacity: par_capacity,
                user_id: par_user,
            },
            success: function (response) {
                console.log(response.message);
            },
            error: function (response) {
                console.error('Error saving order data:');
            }
        });

    });
});

$('#delete_warehouse').on('click', function(e) {
    e.stopPropagation();

    var par_warehouse_id = document.getElementById('warehouse_id').value;

    $.ajax({
        url: '/delete_warehouse_form',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        data: {
            warehouse_id: par_warehouse_id,
        },
        success: function (response) {
            console.log(response.message);
            window.location.replace('/warehouses');
        },
        error: function (response) {
            console.error('Error deleting product data:');
        }
    })
});


$(document).ready(function() {
    //$(document).on('click', '.dropdown-item.delete_item', function(e) {
    $('.dropdown-item.delete_item').on('click', function(e) {
        e.stopPropagation();

        var $this = $(this);
        var par_item_id = $(this).data('item-id');

        $.ajax({
            url: '/delete_item',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            data: {
                item_id: par_item_id,
            },
            success: function (response) {
                console.log(response.message);
                $this.closest('tr').fadeOut(500, function() {
                    $(this).remove();
                });
            },
            error: function (response) {
                console.error('Error deleting item data:');
            }
        })
    });
});

//$('.dropdown-item.modify_animal_number').on('click', function(e) {

$('.dropdown-item.modify_item').on('click', function(e) {

        e.stopPropagation();
        e.preventDefault();

        var par_item_id = $(this).data('item-id');

        var $row = $(this).closest('tr');

        var par_warehouse_id = $row.find('select[name="warehouse_id"]').val();

        $.ajax({
            type: 'post',
            url: '/modify_item',
            method: 'post',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            data: {
                item_id: par_item_id,
                warehouse_id: par_warehouse_id,
            },
            success: function (response) {
                console.log(response.items);
                console.log(par_warehouse_id, par_item_id);
            },
            error: function (response) {
                console.error('Error modifying item data:');
            }
        })
    });
$(document).ready(function () {
    $('.dropdown-menu').on('click', function (event) {
        event.stopPropagation();
    });

    $('.dropdown-item').on('click', function () {
        $(this).closest('.dropdown').find('[data-bs-toggle="dropdown"]').dropdown('hide');
    });
});

