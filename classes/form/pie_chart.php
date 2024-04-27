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
            AND username <> 'admin'
            AND id NOT IN (
                SELECT ra.userid
                FROM {role_assignments} ra
                JOIN {role} r ON ra.roleid = r.id
                JOIN {context} ctx ON ra.contextid = ctx.id
                WHERE r.shortname = 'admin' AND ctx.contextlevel = 10
            )";
    return $DB->get_records_sql($sql);
}