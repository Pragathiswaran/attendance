<?php
require_once(__DIR__ . '/../../config.php'); 
require_once($CFG->dirroot . '/local/attendance/lib.php'); 
require_login();
$context = context_system::instance();
require_capability('moodle/site:config', $context); 

$PAGE->set_url('/local/attendance/manage.php');
$PAGE->set_context($context);
$PAGE->set_title(get_string('pluginname', 'local_attendance')); 
$PAGE->set_heading(get_string('activityreport', 'local_attendance')); 

$attendance = new local_attendance();
$userCourseAccess = $attendance->getUserCourseActivity();

echo $OUTPUT->header();

echo '<h3>User Course Access Information</h3>';
echo '<table class="generaltable">';
echo '<thead>';
echo '<tr>';
echo '<th>User ID</th>';
echo '<th>Username</th>';
echo '<th>Course ID</th>';
echo '<th>Course Name</th>';
echo '<th>Date</th>';
echo '<th>Session Start</th>';
echo '<th>Session End</th>';
echo '<th>Duration</th>';
echo '</tr>';
echo '</thead>';
echo '<tbody>';


foreach ($userCourseAccess as $accessKey => $access) {
    foreach ($access['sessions'] as $session) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($access['userid']) . '</td>';
        echo '<td>' . htmlspecialchars($access['username']) . '</td>';
        echo '<td>' . htmlspecialchars($access['courseid']) . '</td>';
        echo '<td>' . htmlspecialchars($access['course_name']) . '</td>';
        echo '<td>' . htmlspecialchars($access['date']) . '</td>';
        echo '<td>' . htmlspecialchars($session['start_time']) . '</td>';
        echo '<td>' . htmlspecialchars($session['end_time']) . '</td>';
        echo '<td>' . htmlspecialchars($session['duration']) . '</td>';
        echo '</tr>';
    }
}

echo '</tbody>';
echo '</table>';

echo $OUTPUT->footer();
