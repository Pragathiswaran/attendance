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
require_once($CFG->libdir.'/formslib.php');

class course_user_selector_form extends moodleform {
    protected function definition() {
        $mform = $this->_form;

        // Fetch courses to populate the dropdown
        $courseoptions = $this->get_courses_array();

        // Course dropdown
        $mform->addElement('select', 'courseid', get_string('selectcourse', 'local_attendance'), $courseoptions);
        $mform->addRule('courseid', null, 'required', null, 'client');
        $mform->setType('courseid', PARAM_INT);
        $mform->setDefault('courseid', '0'); // Set default selection to 'Choose a course'

        // Date selector
        $mform->addElement('date_selector', 'date', get_string('selectdate', 'local_attendance'));
        $mform->setType('date', PARAM_INT);

        // Buttons
        $this->add_action_buttons(true, get_string('submit'));
    }

    private function get_courses_array() {
        global $DB, $USER;
        $courses = $DB->get_records('course', ['visible' => 1], '', 'id, fullname');
        $coursearray = ['0' => get_string('choose')]; // Includes a default 'choose' option
        
        foreach ($courses as $course) {
            // Check if the user lacks the update capability in this course
            $context = context_course::instance($course->id);
            if (has_capability('moodle/course:update', $context)) {
                $coursearray[$course->id] = $course->fullname;
            }
        }
        return $coursearray;
    }
}
