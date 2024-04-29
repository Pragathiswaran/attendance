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
require_once(__DIR__.'/../../config.php'); 
require_once($CFG->dirroot.'/local/attendance/classes/course.class.php'); 
require_login();

$coursename = optional_param('coursename', null, PARAM_TEXT);
// echo $cousername;

$attendance = new course();

$userCourseAccessData = $attendance->getUserCourseActivity($coursename);
echo"
    <thead>
      <tr>
          <th>".get_string('userid', 'local_attendance')."</th>
          <th>".get_string('username', 'local_attendance')."</th>
          <th>".get_string('courseid', 'local_attendance')."</th>
          <th>".get_string('coursename', 'local_attendance')."</th>
          <th>".get_string('sessiondate', 'local_attendance')."</th>
          <th>".get_string('sessionstart', 'local_attendance')."</th>
          <th>".get_string('sessionend', 'local_attendance')."</th>
          <th>".get_string('sessionduration', 'local_attendance')."</th>
      </tr>
    </thead>
    <tbody id='testrender' style='color:black; font-size:14px;'>
    ";
    $totalItems = count($userCourseAccessData);

    for ($i = $totalItems - 1; $i >= 0; $i--) {
        $data = $userCourseAccessData[$i];
        if ($data['userid'] === $USER->id) {
            continue;
        }
        echo "<tr>";
        echo "<td>".$data['userid']."</td>";
        echo "<td>".$data['username']."</td>";
        echo "<td>".$data['courseid']."</td>";
        echo "<td>".$data['coursename']."</td>";
        echo "<td>".$data['date']."</td>";
        echo "<td>".$data['start_time']."</td>";
        echo "<td>".$data['end_time']."</td>";
        echo "<td>".$data['duration']."</td>";
        echo "</tr>";
    }