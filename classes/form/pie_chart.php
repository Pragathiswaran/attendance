
<?php
require_once("$CFG->libdir/formslib.php");
require_once($CFG->dirroot.'/user/lib.php');
require_once($CFG->libdir . '/accesslib.php'); // Required for capability checking functions

class select_user_form extends moodleform {
    protected function definition() {
        global $DB, $USER, $COURSE;
        $mform = $this->_form;

        // Check if the user has the capability to update the course
        if (has_capability('moodle/course:update', context_course::instance($COURSE->id))) {
            // Fetch users enrolled in the specific course
            $users = get_course_users($COURSE->id);
        } else {
            // Fetch non-admin users
            $users = get_non_admin_users();
        }

        // Prepare an array for dropdown options
        $useroptions = [];
        foreach ($users as $user) {
            $useroptions[$user->id] = fullname($user); // Use Moodle's fullname function to display full user names
        }

        // Adding user dropdown
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

function get_course_users($course_id) {
    global $DB;
    $enrolled_users = get_enrolled_users(context_course::instance($course_id), 'mod/assign:view', 0, 'u.id, u.firstname, u.lastname');
    return $enrolled_users;
}

function get_non_admin_users() {
    global $DB;
    $sql = "SELECT id, firstname, lastname FROM {user} WHERE deleted = 0 AND suspended = 0 AND id NOT IN (1, 2)";
    $all_users = $DB->get_records_sql($sql);
    return $all_users;
}

