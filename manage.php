<?php
require_once(__DIR__.'/../../config.php'); // Adjust the path as necessary to point to Moodle's config.php
require_once($CFG->dirroot.'/local/attendance/lib.php'); // Adjust the path to your local plugin's library file

require_login();
$context = context_system::instance();
require_capability('moodle/site:config', $context); // Adjust required capability as necessary

$PAGE->set_url('/local/attendance/manage.php'); // Adjust URL as necessary
$PAGE->set_context($context);
$PAGE->set_title(get_string('pluginname', 'local_attendance'));
$PAGE->set_heading(get_string('activityreport', 'local_attendance'));

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('activityreport', 'local_attendance'));

$attendance = new local_attendance();
$activitySummary = $attendance->getUserCourseActivity();

$test = array();
foreach ($activitySummary as $userId => $courses) {
    foreach ($courses as $courseId => $data) {
        $test[] = $data;
    }
}
// render all the value by using mustache template
$templeteValue = [
    'result' => $test,
];

echo $OUTPUT->render_from_template('local_attendance/manage', $templeteValue);


echo $OUTPUT->footer();

