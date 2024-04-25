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
         global $CFG, $DB;
 
         $mform = $this->_form;
 
         // Fetch all usernames from the user table
         $users = $DB->get_records_sql("SELECT username FROM {user}");
 
         $usernames = array();

        $count = 0; // Counter to track usernames

        // Loop through the users to retrieve usernames
        foreach ($users as $user) {
            $username = $user->username;

            // Skip the first two usernames
            if ($count < 2) {
                $count++;
                continue;
            }

            // Add username to the array if not empty
            if (!empty($username)) {
                $usernames[$username] = $username; // Use username as both key and value
            }
        }
 
         // Add form elements
         
         $mform->addElement('text', 'emailtext', 'Enter the Subject');
         $mform->addElement('text', 'emailmessage', 'Enter the Message');
         $mform->addElement('select', 'email', 'Select a User', $usernames);
 
         // Add action buttons
         $this->add_action_buttons(true, 'Send Email');
     }
 
     function validation($data, $files) {
         // Perform validation if needed
         return array(); // Return empty array if validation is successful
     }
 }
 