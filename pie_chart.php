<?php
require_once(__DIR__.'/../../config.php');
require_once($CFG->dirroot.'/local/attendance/classes/form/pie_chart.php');

$PAGE->set_heading('Coursewise User Access Chart');

echo $OUTPUT->header();

function fetch_course_access_data($userid, $selectedDate) {
    global $DB;
    $startOfDay = strtotime("midnight", $selectedDate);
    $endOfDay = strtotime("tomorrow", $startOfDay) - 1;

    $sql = "SELECT courseid, COUNT(*) AS access_count
            FROM {logstore_standard_log}
            WHERE userid = :userid AND component = 'core' AND action = 'viewed' AND target = 'course'
                  AND timecreated BETWEEN :start AND :end
            GROUP BY courseid";
    $params = ['userid' => $userid, 'start' => $startOfDay, 'end' => $endOfDay];
    $records = $DB->get_records_sql($sql, $params);

    $courseAccessData = [];
    foreach ($records as $record) {
        if ($record->courseid) {
            $courseName = get_course_name($record->courseid);
            $courseAccessData[$courseName] = $record->access_count;
        }
    }
    return $courseAccessData;
}


function get_course_name($courseid) {
    global $DB;
    $course = $DB->get_record('course', ['id' => $courseid], 'fullname');
    return $course ? $course->fullname : 'Unknown Course';
}

$form = new select_user_form();
if ($form->is_cancelled()) {
    redirect($CFG->wwwroot . '/local/attendance/manage.php');
} elseif ($fromform = $form->get_data()) {
    $selected_user_id = $fromform->userid;
    $selected_date = $fromform->date;

    $courseAccesses = fetch_course_access_data($selected_user_id, $selected_date);

    if (!empty($courseAccesses)) {
        $labels = array_keys($courseAccesses);
        $values = array_values($courseAccesses);

        $accessSeries = new \core\chart_series('Course Access Count', $values);
        $chart = new \core\chart_pie();
        $chart->add_series($accessSeries);
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
