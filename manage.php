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
// echo "<pre>";
$mergedSessions = [];
foreach ($userCourseAccess as $entries) {
    $data = [
        'courseid' => $entries['courseid'],
        'coursename' => $entries['course_name'],
        'userid' => $entries['userid'],
        'username' => $entries['username'],
    ];
    foreach($entries['sessions'] as $session) {
    $data1=['date' => $session['date'],
        'start_time' => $session['start_time'],
        'end_time' => $session['end_time'],
        'duration' => $session['duration']];   
    }
    $mergedSessions = array_merge($mergedSessions, $data,$data1);
}

// echo "<pre>";
// print_r($mergedSessions);
// echo "<pre>";
echo $OUTPUT->render_from_template('local_attendance/manage', ['dateWiseAccess' => $mergedSessions]);

echo $OUTPUT->footer();
