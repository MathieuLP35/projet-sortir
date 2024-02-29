document.addEventListener('DOMContentLoaded', function () {
    let isRegistered = document.getElementById('event_filter_isRegistered');
    let isNotRegistered = document.getElementById('event_filter_isNotRegistered');

    isRegistered.addEventListener('change', function() {
        if (isRegistered.checked) {
            isNotRegistered.checked = false;
        }
    });

    isNotRegistered.addEventListener('change', function() {
        if (isNotRegistered.checked) {
            isRegistered.checked = false;
        }
    });
});