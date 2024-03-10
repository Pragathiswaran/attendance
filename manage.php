<?php
require_once(__DIR__.'/../../config.php'); 
require_once($CFG->dirroot.'/local/attendance/lib.php'); 

require_login();
$context = context_system::instance();
require_capability('moodle/site:config', $context); 

$PAGE->set_url(new moodle_url('/local/attendance/manage.php'));
$PAGE->set_context($context);
$PAGE->set_title(get_string('pluginname', 'local_attendance'));
$PAGE->set_heading(get_string('pluginname', 'local_attendance'));

echo $OUTPUT->header();

$attendance = new local_attendance();
$userCourseAccess = $attendance->getUserCourseActivity();
// echo "<pre>";
// print_r($userCourseAccess);
// echo "</pre>";
// $dateWiseAccess = [];
// foreach ($userCourseAccess as $userCourseKey => $access) {
//     foreach ($access['sessions'] as $session) {
//     }
// }

// ksort($dateWiseAccess);

// foreach ($dateWiseAccess as $date => $sessions) {
   
// }
//echo $OUTPUT->render_from_template('local_attendance/manage', ['dateWiseAccess' => $dateWiseAccess]);

echo $OUTPUT->footer();
