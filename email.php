<?php

// display_form.php

require_once(__DIR__ . '/../../config.php');
require_once($CFG->dirroot.'/local/attendance/classes/form/email.php');
require_once($CFG->dirroot.'/local/attendance/sendmail.php');

$PAGE->set_url(new moodle_url('/local/attendance/email.php'));
$PAGE->set_context($context);
$PAGE->set_title(get_string('pluginname', 'local_attendance'));
$PAGE->set_heading('Email');
$PAGE->requires->jQuery();
$PAGE->requires->js(new moodle_url('/local/attendance/amd/src/mail.js'));

// Ensure user is logged in

// Initialize the form
$mform = new email();

if ($mform->is_cancelled()) {
   // redirect($CFG->wwwroot.'/local/attendance/manage.php', 'You have cancelled the form');
    redirect($CFG->wwwroot.'/local/attendance/manage.php', 'You have cancelled the form', null, \core\output\notification::NOTIFY_INFO);
} else if ($fromform = $mform->get_data()) {
    if (sendingmail($fromform->email, $fromform->emailtext, $fromform->emailmessage)) {
        // Display a success notification
        redirect($CFG->wwwroot.'/local/attendance/manage.php', 'You successfullr send the email', null, \core\output\notification::NOTIFY_SUCCESS);
    } else {
        // Display an error notification
        redirect($CFG->wwwroot.'/local/attendance/email.php', 'You fail to sending email', null, \core\output\notification::NOTIFY_ERROR);
    }
    
}
// Output the form
echo $OUTPUT->header();
//$mform->display();
$data = (object)[
    'form' => $mform->display()
];
echo $OUTPUT->render_from_template('local_attendance/example',$data);
echo $OUTPUT->footer();
