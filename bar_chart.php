<?php
require_once(__DIR__.'/../../config.php');
require_once($CFG->dirroot.'/lib/formslib.php');
require_once($CFG->dirroot.'/local/attendance/classes/form/bar_chart.php');

$PAGE->set_heading('Users Course Access');

echo $OUTPUT->header();


function fetch_course_access_durations($courseid, $selectedDate) {
    global $DB;
    $startOfDay = strtotime("midnight", $selectedDate);
    $endOfDay = strtotime("tomorrow", $startOfDay) - 1;

    // First, fetch all user durations for the course within the specified day.
    $sql = "SELECT userid, SUM(timecreated - timecreated % 60) AS duration
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
            $durationData[$userName] = round($record->duration); // Convert seconds to hours, rounded to 2 decimals
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
