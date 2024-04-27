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
class render {
    public function coursenamedata() {
        global $DB, $COURSE;

        // Get all courses
        $Sql = "SELECT * FROM {course}";
        $Query = $DB->get_records_sql($Sql);

        $querydata = [];
        foreach ($Query as $course) {
            if ($course->category > 0) {
                $context = context_course::instance($course->id);
                if (has_capability('moodle/course:update', $context)) {
                    $querydata[] = [
                        'coursenames' => $course->fullname,
                    ];
                } 
            }
        }

        return $querydata;
    }
}
