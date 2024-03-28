function saveCheckedInputId() {
    const radioButtons = document.querySelectorAll('.rating-account input[type="radio"]');

    radioButtons.forEach(function(radioButton) {
        if (radioButton.checked) {
            const checkedId = radioButton.id;

            const textarea = document.getElementById('infos');

            textarea.value = checkedId;
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form'); 

    form.addEventListener('submit', function(event) {
        event.preventDefault();
        saveCheckedInputId();
        this.submit();
    });
});

function back() {
    window.history.back();
}