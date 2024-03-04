function validateRegister() {
    let name = document.getElementById('name').value;
    let email = document.getElementById('email').value;
    let password = document.getElementById('password').value;
    let passwordConfirmation = document.getElementById('password_confirmation').value;

    return validateEmail(email) && validateName(name) && validatePass(password) && validateConfPass(password, passwordConfirmation);
}

function validateSignIn() {
    let name = document.getElementById('loginname').value;
    let password = document.getElementById('loginpassword').value;

    return validateName(name) && validatePass(password);
}

function validateEmail(email) {
    let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (email === '') {
        alert('Please enter your email address.');
        return false;
    }

    if (!emailRegex.test(email)) {
        alert('Enter a valid email address.');
        return false;
    }

    return true;

}

function validatePass(password) {
    if (password === '') {
        alert('Please enter a password.');
        return false;
    }


    if (password.length < 8) {
        alert('Password must be at least 8 characters long.');
        return false;
    }

    return true;

}

function validateName(name) {
    if (name === '') {
        alert('Please enter your username.');
        return false;
    }

    if (!/^[a-zA-Z]+$/.test(name)) {
        alert('Enter a valid username (only letters).');
        return false;
    }

    return true;
}

function validateConfPass(pass, confPass) {
    return pass === confPass;
}
