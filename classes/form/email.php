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
        global $CFG, $DB, $OUTPUT, $PAGE, $USER;

        $mform = $this->_form;
        
        $sql = "SELECT u.username FROM {user} u"; 
        $users = array();
        $users[''] = 'Select User'; 
        $userRecords = $DB->get_records_sql($sql);

        $count = 0; 

        foreach ($userRecords as $user) {
            $count++;

            if ($count <= 2) {
                continue;
            }

            $users[$user->username] = $user->username; 
        } 
        $mform->addElement('text', 'emailtext', 'Enter the Subject');
        $mform->addElement('text', 'emailmessage', 'Enter the message');
        $mform->addElement('select', 'email', 'Select the User',$users);

        $this->add_action_buttons('Email','Email');
    
    }

    function validation($data,$files){
        return array();
    }
}