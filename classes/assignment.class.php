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
 * @author      Pragathiswaran Ramyasri Rukkesh
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class assignment{
    public function assignment(){
        global $DB; 
        
        $sqli = 'SELECT 
        a.id AS assign_id,
        a.`course`,
        c.fullname AS coursename,
        a.`name`,
        a.`intro`,
        a.duedate,
        a.allowsubmissionsfromdate,
        ag.assignment,
        ag.userid,
        u.username,
        ag.timecreated,
        ag.timemodified,
        FROM_UNIXTIME(a.duedate, "%Y-%m-%d") AS duedate_utc,
        FROM_UNIXTIME(a.allowsubmissionsfromdate, "%Y-%m-%d") AS submission_utc,
        FROM_UNIXTIME(ag.timecreated, "%Y-%m-%d %H:%i:%s") AS timecreated_utc,
        FROM_UNIXTIME(ag.timemodified, "%Y-%m-%d %H:%i:%s") AS timemodified_utc
    FROM 
        mdl_assign a
    LEFT JOIN 
        mdl_assign_grades ag ON a.id = ag.assignment
    LEFT JOIN 
        mdl_user u ON ag.userid = u.id
    LEFT JOIN 
        mdl_course c ON a.`course` = c.id;
    ';

        $assignments = $DB->get_records_sql($sqli);

        
        $assignmentData = [];
        
        foreach ($assignments as $entries => $value) {
        
            $startTime = new DateTime($value->timecreated_utc);
            $finishTime = new DateTime($value->timemodified_utc);
            
            $between = $startTime->diff($finishTime);
            $parts = [];
            if ($between->h > 0) {
                $parts[] = $between->h . ' ' . ($between->h == 1 ? 'hour' : 'hours');
            }
            if ($between->i > 0) {
                $parts[] = $between->i . ' ' . ($between->i == 1 ? 'min' : 'mins');
            }
            if ($between->s > 0 || count($parts) == 0) {
                $parts[] = $between->s . ' ' . ($between->s == 1 ? 'sec' : 'secs');
            }
            $duration = implode(' : ', $parts);
            
            $assignmentData[] = [
                'userid' => $value->userid,
                'username' => $value->username,
                'course' => $value->course,
                'coursename' => $value->coursename,
                'name' => $value->name,
                'duedate' => $value->duedate_utc,
                'submission' => $value->submission_utc,
                'timecreated' => $startTime->format('H:i:s'),
                'timemodified' => $finishTime->format('H:i:s'),
                "duration" => $duration
            ];
        }
        
        return $assignmentData;
    }
}