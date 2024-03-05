<?php
require_once(__DIR__ . '/../../config.php'); // Make sure this path is correct
require_once($CFG->dirroot . '/local/attendance/lib.php'); // Adjust the path to include the local_attendance class

require_login();
$context = context_system::instance();
require_capability('moodle/site:viewreports', $context); // Ensure this capability aligns with your use case

$PAGE->set_url(new moodle_url('/local/attendance/manage.php'));
$PAGE->set_context($context);
$PAGE->set_title('Course Access Report');
$PAGE->set_heading('Detailed Course Access Report');

$attendance = new local_attendance();
$userCourseAccess = $attendance->getUserCourseActivity();

echo $OUTPUT->header();
echo '<table class="generaltable">';
echo '<thead>';
echo '<tr>';
echo '<th>User ID</th>';
echo '<th>Username</th>';
echo '<th>Course ID</th>';
echo '<th>Course Name</th>';
echo '<th>Date</th>';
echo '<th>Session Start Time</th>';
echo '<th>Session End Time</th>';
echo '<th>Duration (HH:MM:SS)</th>';
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
        echo '<td>' . htmlspecialchars($session['date']) . '</td>';
        echo '<td>' . htmlspecialchars($session['start_time']) . '</td>';
        echo '<td>' . htmlspecialchars($session['end_time']) . '</td>';
        echo '<td>' . htmlspecialchars($session['duration']) . '</td>';
        echo '</tr>';
    }
}

echo '</tbody>';
echo '</table>';

echo $OUTPUT->footer();
