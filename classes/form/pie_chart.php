<?php
require_once("$CFG->libdir/formslib.php");
require_once($CFG->dirroot . '/user/lib.php');
require_once($CFG->libdir . '/accesslib.php'); // Required for capability checking functions

class select_user_form extends moodleform {
    protected function definition() {
        global $DB, $USER, $COURSE;
        $mform = $this->_form;

        // Fetch non-admin users
        $users = get_non_admin_users();
        $useroptions = [];
        foreach ($users as $user) {
            $useroptions[$user->id] = $user->firstname . ' ' . $user->lastname;
        }

        // Add a select element with the user options
        $mform->addElement('select', 'userid', 'Select User:', $useroptions);
        $mform->setType('userid', PARAM_INT);

        // Adding a date selector to the form
        $mform->addElement('date_selector', 'date', 'Select Date:');
        $mform->setType('date', PARAM_INT);

        // Adding buttons
        $mform->addElement('submit', 'submitbutton', 'Show Data');
        $mform->addElement('cancel', 'cancelbutton', 'Cancel', array('onclick' => 'window.location.href=\'http://localhost/moodle/local/attendance/manage.php\'; return false;'));
    }
}

function get_non_admin_users() {
    global $DB;
    $sql = "SELECT id, firstname, lastname 
            FROM {user} 
            WHERE deleted = 0 AND suspended = 0 
            AND username <> 'guest' 
            AND id NOT IN (
                SELECT ra.userid
                FROM {role_assignments} ra
                JOIN {role} r ON ra.roleid = r.id
                JOIN {context} ctx ON ra.contextid = ctx.id
                WHERE r.shortname = 'admin' AND ctx.contextlevel = 10
            )";
    return $DB->get_records_sql($sql);
}