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
 * @author      Prgathiswaran
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @var stdClass $plugin
 */

class local_attendance {
    private function getUserCourseActivity() {
        global $DB;

        $param = ['exclude_userid' => 2];

        $sql = "SELECT l.id AS log_event_id,
                       l.timecreated AS timestamp,
                       FROM_UNIXTIME(l.timecreated, '%Y-%m-%d %H:%i:%s') AS time_utc,
                       FROM_UNIXTIME(l.timecreated, '%H:%i:%s') AS time_only,
                       FROM_UNIXTIME(l.timecreated, '%Y-%m-%d') AS date,
                       l.action,
                       u.username,
                       u.id AS userid,
                       c.id AS courseid,
                       c.fullname AS course_name,
                       l.origin,
                       l.ip
                FROM {logstore_standard_log} l
                JOIN {user} u ON u.id = l.userid
                JOIN {course} c ON c.id = l.courseid
                WHERE l.courseid != 0 AND l.userid != :exclude_userid
                ORDER BY l.userid, l.courseid, l.timecreated ASC";

        $activityData = $DB->get_records_sql($sql, $param);

        $userCourseAccess = [];
        foreach ($activityData as $activity) {
            $userId = $activity->userid;
            $courseId = $activity->courseid;
            $date = $activity->date;
            $userCourseKey = "{$userId}_{$courseId}";

            if (!isset($userCourseAccess[$userCourseKey])) {
                $userCourseAccess[$userCourseKey] = [
                    'userid' => $userId,
                    'username' => $activity->username,
                    'courseid' => $courseId,
                    'course_name' => $activity->course_name,
                    'sessions' => [],
                ];
            }

            $sessions = &$userCourseAccess[$userCourseKey]['sessions'];
           
            $sessionGap = 600; 
            if (empty($sessions) || $activity->timestamp - end($sessions)['end_timestamp'] > $sessionGap) {
                $sessions[] = [
                    'date' => $date,
                    'start_time' => $activity->time_only,
                    'end_time' => $activity->time_only, 
                    'start_timestamp' => $activity->timestamp,
                    'end_timestamp' => $activity->timestamp,
                ];
            } else {
            
                $lastSessionKey = array_key_last($sessions);
                $sessions[$lastSessionKey]['end_time'] = $activity->time_only;
                $sessions[$lastSessionKey]['end_timestamp'] = $activity->timestamp;
            }
        }


        foreach ($userCourseAccess as &$userCourse) {
            foreach ($userCourse['sessions'] as &$session) {
                $durationSeconds = $session['end_timestamp'] - $session['start_timestamp'];
                $session['duration'] = gmdate('H:i:s', $durationSeconds);
            }
        }

        return $userCourseAccess;
       // return $activityData;
    }

    public function ShowData() {
        $userCourseAccess = $this->getUserCourseActivity();
        $showData = [];
        foreach ($userCourseAccess as $entries => $value) {
            foreach($value['sessions'] as $entry){
                $showData[] = [
                    'userid' => $value['userid'],
                    'username' => $value['username'],
                    'courseid' => $value['courseid'],
                    'coursename' => $value['course_name'],
                    'date' => $entry['date'],
                    'start_time' => $entry['start_time'],
                    'end_time' => $entry['end_time'],
                    'start_timestamp' => $entry['start_timestamp'],
                    'end_timestamp' => $entry['end_timestamp'],
                    'duration' => $entry['duration']
                ];  
            }
            
        }

        return $showData;
    }

    public function quizAttempt(){
        global $DB;

        $sql ='SELECT qa.id AS attempt_id,
        qa.timestart,
        qa.timefinish,
        FROM_UNIXTIME(qa.timestart, "%Y-%m-%d") AS date_utc,
        FROM_UNIXTIME(qa.timestart, "%Y-%m-%d %H:%i:%s") AS timestart_utc,
        FROM_UNIXTIME(qa.timefinish, "%Y-%m-%d %H:%i:%s") AS timefinish_utc,
        qa.userid,
        u.username,
        q.name AS quiz_name,
        c.fullname AS course_name
        FROM mdl_quiz_attempts qa
        JOIN mdl_user u ON qa.userid = u.id
        JOIN mdl_quiz q ON qa.quiz = q.id
        JOIN mdl_course c ON q.course = c.id
        WHERE c.id = :your_course_id;';

        $param = ['your_course_id' => 2];
        // $sql = 'SELECT * FROM mdl_quiz_attempts;';

        $quiz = $DB->get_records_sql($sql,$param);

        $quizData = [];

        
        foreach ($quiz as $entries => $value) {

            $startTime = new DateTime($value->timestart_utc);
            $finishTime = new DateTime($value->timefinish_utc);

            $duration = $startTime->diff($finishTime)->format('%H:%i:%s');
            $quizData[] = [
                'userid' => $value->userid,
                'username' => $value->username,
                'course_name' => $value->course_name,
                'quiz_name' => $value->quiz_name,
                'date' => $value->date_utc,
                'timestart' => $startTime->format('H:i:s'),
                'timefinish' =>$finishTime->format('H:i:s'),
                'duration' => $duration
            ];
        }

        return $quizData;
    }

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
            $duration = $startTime->diff($finishTime)->format('%H:%i:%s');
        
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
        
        // echo "<pre>";
        // print_r($assignmentData);
        // echo "</pre>";

        return $assignmentData;
    }
}

function local_attendance_extend_navigation(global_navigation $navigation)
{
    // Check if the current user has the capability to configure the site
    if (!has_capability('moodle/site:config', context_system::instance())) {
        return; // If the user doesn't have the capability, exit the function
    }

    // Add a new node to the global navigation menu
    $main_node = $navigation->add("Report", '/local/attendance/manage.php/');

    // Set properties for the newly added node
    $main_node->nodetype = 1;
    $main_node->collapse = false;
    $main_node->forceopen = true;
    $main_node->isexpandable = false;
    $main_node->showinflatnavigation = true;
}
