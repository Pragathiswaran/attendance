<?php
require_once(__DIR__.'/../../config.php'); 
require_once($CFG->dirroot.'/local/attendance/lib.php'); 

require_login();
$context = context_system::instance();
require_capability('moodle/site:config', $context); 

$PAGE->set_url(new moodle_url('/local/attendance/manage.php'));
$PAGE->set_context($context);
$PAGE->set_title(get_string('pluginname', 'local_attendance'));
//$PAGE->set_heading(get_string('User Activity Report', 'local_attendance'));

echo $OUTPUT->header();
echo "<h2><u><b>User Activity Report </b></u></h2><br>" ;


$attendance = new local_attendance();
$userCourseAccess = $attendance->getUserCourseActivity();

$dateWiseAccess = [];
foreach ($userCourseAccess as $userCourseKey => $access) {
    foreach ($access['sessions'] as $session) {
        $date = $session['date'];
        if (!isset($dateWiseAccess[$date])) {
            $dateWiseAccess[$date] = [];
        }
        $dateWiseAccess[$date][] = $session + ['username' => $access['username'], 'userid' => $access['userid'], 'courseid' => $access['courseid'], 'course_name' => $access['course_name']];
    }
}

ksort($dateWiseAccess);

foreach ($dateWiseAccess as $date => $sessions) {
    echo "<h4>Date: " . htmlspecialchars($date) . "</h4>";
    echo '<table border="1" style="width:100%;">';
    echo '<thead>';
    echo '<tr>
            <th>User ID</th>
            <th>Username</th>
            <th>Course ID</th>
            <th>Course Name</th>
            <th>Start</th>
            <th>End</th>
            <th>Duration</th>
          </tr>';
    echo '</thead>';
    echo '<tbody>';

    foreach ($sessions as $session) {
        echo '<tr>';
        echo '<td>'.htmlspecialchars($session['userid']).'</td>';
        echo '<td>'.htmlspecialchars($session['username']).'</td>';
        echo '<td>'.htmlspecialchars($session['courseid']).'</td>';
        echo '<td>'.htmlspecialchars($session['course_name']).'</td>';
        echo '<td>'.htmlspecialchars($session['start_time']).'</td>';
        echo '<td>'.htmlspecialchars($session['end_time']).'</td>';
        echo '<td>'.htmlspecialchars($session['duration']).'</td>';
        echo '</tr>';
    }

    echo '</tbody>';
    echo '</table><br>';
}

echo $OUTPUT->footer();
