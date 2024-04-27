<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * @package     local_attendance
 * @author      Prgathiswaran Ramyasri Rukkesh
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once(__DIR__.'/../../config.php');
require_once($CFG->dirroot.'/lib/formslib.php');
require_once($CFG->dirroot.'/local/attendance/classes/form/bar_chart.php');

$PAGE->set_heading('Users Course Access');

echo $OUTPUT->header();


function fetch_course_access_durations($courseid, $selectedDate) {
    global $DB;
    $startOfDay = strtotime("midnight", $selectedDate);
    $endOfDay = strtotime("tomorrow", $startOfDay) - 1;

    // Assuming you have a way to determine the session length, e.g., average or fixed duration per view
    $viewDurationInSeconds = 60; // Assuming each 'view' is logged and equates to a minute spent.

    // Fetch all user sessions for the course within the specified day.
    $sql = "SELECT userid, COUNT(*) AS views
            FROM {logstore_standard_log}
            WHERE courseid = :courseid AND action = 'viewed' AND target = 'course'
                  AND timecreated BETWEEN :start AND :end
            GROUP BY userid";
    $params = [
        'courseid' => $courseid, 
        'start' => $startOfDay, 
        'end' => $endOfDay
    ];
    $records = $DB->get_records_sql($sql, $params);

    $durationData = [];
    foreach ($records as $record) {
        // Check if each user has the 'moodle/course:update' capability.
        $context = context_course::instance($courseid);
        if (!has_capability('moodle/course:update', $context, $record->userid)) {
            $userName = get_user_name($record->userid);
            $totalDuration = $record->views * $viewDurationInSeconds; // Calculate total duration
            $durationData[$userName] = $totalDuration; // Store duration in seconds
        }
    }
    return $durationData;
}    

function get_user_name($userid) {
    global $DB;
    $user = $DB->get_record('user', ['id' => $userid], 'firstname, lastname');
    return $user ? $user->firstname . ' ' . $user->lastname : 'Unknown User';
}


$form = new course_user_selector_form();
if ($form->is_cancelled()) {
    redirect($CFG->wwwroot . '/local/attendance/manage.php');
} elseif ($fromform = $form->get_data()) {
    $selected_course_id = $fromform->courseid;
    $selected_date = $fromform->date;

    $userDurations = fetch_course_access_durations($selected_course_id, $selected_date);

    if (!empty($userDurations)) {
        $labels = array_keys($userDurations);
        $values = array_values($userDurations);

        $durationSeries = new \core\chart_series('Time Spent', $values);
        $chart = new \core\chart_bar();
        $chart->add_series($durationSeries);
        $chart->set_labels($labels);

        echo '<div class="chart-container" style="width: 600px; height: 400px;">';
        echo $OUTPUT->render($chart);
        echo '</div>';
    } else {
        echo '<div class="alert alert-info">No data found for the selected date.</div>';
    }
} else {
    $form->display();
}

echo $OUTPUT->footer();
