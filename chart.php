<?php
require_once(__DIR__.'/../../config.php');
require_once($CFG->libdir . '/formslib.php');

echo $OUTPUT->header();
echo $OUTPUT->heading('User Moodle Access Duration Chart');

class select_user_form extends moodleform {
    protected function definition() {
        $mform = $this->_form;
        
        // Fetch non-admin users
        $users = get_non_admin_users();
        $mform->addElement('select', 'userid', 'Select User:', $users);
        $mform->setType('userid', PARAM_INT);
        $mform->addElement('submit', 'submitbutton', 'Show Data');
    }
}

function get_non_admin_users() {
    global $DB;
    $users = [];
    $all_users = $DB->get_records_sql("SELECT * FROM {user} WHERE deleted = 0 AND suspended = 0 AND id <> 2 AND id <> 1");
    foreach ($all_users as $user) {
        if (!in_array($user->id, $admin_ids)) {
            $users[$user->id] = fullname($user);
        }
    }
    return $users;
}

function fetch_user_access_data($userid) {
    global $DB;
    $sql = "SELECT FROM_UNIXTIME(timecreated) as timecreated, action 
            FROM {logstore_standard_log} 
            WHERE userid = :userid AND action IN ('loggedin', 'loggedout')
            ORDER BY timecreated ASC";
    $params = ['userid' => $userid];
    $records = $DB->get_records_sql($sql, $params);

    $durations = [];
    $lastLogin = null;

    foreach ($records as $record) {
        if ($record->action == 'loggedin') {
            $lastLogin = new DateTime($record->timecreated);
        } elseif ($record->action == 'loggedout' && $lastLogin !== null) {
            $logoutTime = new DateTime($record->timecreated);
            $duration = $logoutTime->getTimestamp() - $lastLogin->getTimestamp();
            $date = $lastLogin->format('Y-m-d');
            if (!isset($durations[$date])) {
                $durations[$date] = 0;
            }
            $durations[$date] += $duration;
            $lastLogin = null;
        }
    }

    return $durations;
}

$form = new select_user_form();

if ($form->is_cancelled()) {
    // Handle form cancellation
} elseif ($fromform = $form->get_data()) {
    $selected_user_id = $fromform->userid;
    $durations = fetch_user_access_data($selected_user_id);

    $labels = array_keys($durations);
    $values = array_values($durations);

    // Convert seconds to hours
    $values = array_map(function ($sec) { return round($sec / 3600, 2); }, $values);

    // Create series for the bar chart
    $access = new \core\chart_series('Daily Access Duration (hours)', $values);

    // Initialize a new bar chart
    $chart = new \core\chart_bar();
    $chart->add_series($access);
    $chart->set_labels($labels);

    // Correctly create and set the axes
    $xaxis = new \core\chart_axis();
    $xaxis->set_label('<------- Date ------->');
    $chart->set_xaxis($xaxis);

    $yaxis = new \core\chart_axis();
    $yaxis->set_label('<----- Duration in Hours ----->');
    $chart->set_yaxis($yaxis);

    // Render the chart inside a container
    echo '<div class="chart-container">';
    echo $OUTPUT->render($chart);
    echo '</div>';

} else {
    // Display the form
    $form->display();
}

echo $OUTPUT->footer();
