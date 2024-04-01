$(document).ready(function() {

    $('#add_new_animals').click(function() {
        var dialog = document.getElementById('animal_dialog');
        dialog.style.display = 'block';
    });
});

document.getElementById('cancel_animal').addEventListener('click', function() {
    var dialog = document.getElementById('animal_dialog');
    dialog.style.display = 'none';
});
