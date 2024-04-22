<?php
// This file is part of Moodle Course Rollover Plugin
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

class email extends moodleform {
    public function definition() {
        global $CFG, $OUTPUT, $PAGE, $USER;

        $mform = $this->_form;
        
        // Add form elements
        $mform->addElement('text', 'email', 'Enter the Email Address'); // Input for email address
        $mform->setType('email', PARAM_EMAIL); // Set to PARAM_EMAIL to ensure valid email addresses
        $mform->addRule('email', 'Please enter a valid email address', 'required', null, 'client');
        $mform->addElement('text', 'emailtext', 'Enter the Subject');
        $mform->setType('emailtext', PARAM_TEXT); // Ensure text
        $mform->addElement('textarea', 'emailmessage', 'Enter the message', 'wrap="virtual" rows="5" cols="50"');
        $mform->setType('emailmessage', PARAM_TEXT); // Ensure text

        // Add action buttons
        $this->add_action_buttons(true, 'Send Email');
    }

    function validation($data, $files) {
        $errors = array();
        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Invalid email address';
        }
        return $errors;
    }
}
