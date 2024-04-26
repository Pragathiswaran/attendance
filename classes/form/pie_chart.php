<?php
require_once("$CFG->libdir/formslib.php");

class select_user_form extends moodleform {
    protected function definition() {
        global $DB; // Include this if you need direct database access within this method
        $mform = $this->_form;

        // Fetch non-admin users
        $users = get_non_admin_users();
        
        // Adding user dropdown
        $mform->addElement('select', 'userid', 'Select User:', $users);
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
    $users = [];
    // Fetch all users that are not deleted or suspended and are not admin users
    $sql = "SELECT * FROM {user} WHERE deleted = 0 AND suspended = 0 AND id NOT IN (1, 2)"; // ID 1 and 2 usually refer to admin accounts in Moodle
    $all_users = $DB->get_records_sql($sql);
    foreach ($all_users as $user) {
        $users[$user->id] = fullname($user); // Assuming fullname is a valid function that combines first name and last name
    }
    return $users;
}

