<?php

/**
 * Short description for class.
 *
 * Long description for class (if any)...
 *
 * @package    local_attendance
 * @author     Prgathiswaran, Ramyasri, Rukkesh
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
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