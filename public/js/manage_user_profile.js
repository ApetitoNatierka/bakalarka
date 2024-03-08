document.getElementById('modify_user_info').addEventListener('click', function() {
    var par_name = document.getElementById('name').value;
    var par_email = document.getElementById('email').value;
    var par_phone_number = document.getElementById('phone_number').value;

    $.ajax({
        type: 'post',
        url: '/modify_user_info',
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        data: {
            name: par_name,
            email: par_email,
            phone_number: par_phone_number,
        },
        success: function (response) {
            console.log(response.message);
        },
        error: function (response) {
            console.error('Error saving user data:');
        }
    });

});
