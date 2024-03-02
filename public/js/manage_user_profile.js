document.getElementById('modify_user_info').addEventListener('click', function() {
    var name = document.getElementById('name').value;
    var email = document.getElementById('email').value;
    var phone_number = document.getElementById('phone_number').value;

    fetch('/modify_user_info', {
        method: 'POST',
        body: JSON.stringify({ name: name, email: email, phone_number: phone_number }),
        headers: {
            'Content-Type': 'application/json'
        }
    }).then(function(response) {
        console.log('User data saved successfully');
    }).catch(function(error) {
        console.error('Error saving user data:', error);
    });
});

document.getElementById('add_address_line').addEventListener('click', function() {
    var dialog = document.getElementById('address_dialog');
    dialog.style.display = 'block';
});

document.getElementById('new_address').addEventListener('click', function() {
    var formData = new FormData(document.getElementById('address_form'));
    var dialog = document.getElementById('address_dialog');
    fetch('/add_new_address_line', {
        method: 'POST',
        body: formData
    }).then(response => {
        if (response.ok) {
            dialog.style.display = 'none';
        } else {
            console.error('Server error:', response.status);
        }
    }).catch(error => {
        console.error('Fetch error:', error);
    });
});

document.getElementById('cancel_address').addEventListener('click', function() {
    var dialog = document.getElementById('address_dialog');
    dialog.style.display = 'none';
});
