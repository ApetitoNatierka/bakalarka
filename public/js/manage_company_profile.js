document.getElementById('add_company_info').addEventListener('click', function() {
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
