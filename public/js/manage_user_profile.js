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

