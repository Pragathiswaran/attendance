// JavaScript code to handle modal popup
document.addEventListener('DOMContentLoaded', function() {
    var button = document.getElementById('open-form-modal');

    if (button) {
        button.addEventListener('click', function(e) {
            e.preventDefault();

            // Load the form in a modal
            var formUrl = '<?php echo $CFG->wwwroot; ?>/local/attendance/email.php'; // Replace {{{url}}} with the URL to your Moodle form processing script
            var options = {
                title: 'Your Form Title',
                iframe: {
                    width: '800px',
                    height: '600px',
                },
                close: true,
            };

            // Open modal with the form
            var formModal = M.core.dialogue({
                url: formUrl,
                options: options
            });

            // Handle form submission
            formModal.done(function(data) {
                // Handle form submission response if needed
                // You can reload the page or update UI here
            });
        });
    }
});
