// /local/attendance/amd/src/attendance_modal.js

define(['jquery', 'core/modal_factory', 'core/modal_events'], function($, modalFactory, modalEvents) {
    return {
        init: function() {
            $(document).on('click', '#openModalButton', function(e) {
                e.preventDefault();

                var form = new M.core.ajaxForm({
                    url: M.cfg.wwwroot + '/local/attendance/classes/form/email.php',
                    title: 'Attendance Form'
                });

                form.setCallback(function(data) {
                    // Handle form submission response if needed
                });

                form.show();
            });
        }
    };
});
