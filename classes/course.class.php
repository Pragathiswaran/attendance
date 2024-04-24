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
class course {
    public function getUserCourseActivity($course) {
        global $DB;

        $param = ['exclude_userid' => 2, 'course_name' => $course];

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
        WHERE c.fullname = :course_name 
        AND l.userid != :exclude_userid
        ORDER BY l.userid, l.courseid, l.timecreated ASC;
        ";

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
                if ($durationSeconds < 60) {
                    // Duration is less than a minute, display in seconds only
                    $secondsPlural = $durationSeconds == 1 ? " sec" : " secs";
                    $durationFormatted = $durationSeconds . $secondsPlural;
                } elseif ($durationSeconds < 3600) {
                    // Duration is less than an hour, but at least a minute, display in minutes and seconds
                    $minutes = floor($durationSeconds / 60);
                    $seconds = $durationSeconds % 60;
                    $minutesPlural = $minutes == 1 ? " min " : " mins ";
                    $secondsPlural = $seconds == 1 ? " sec" : " secs";
                    $durationFormatted = $minutes . $minutesPlural . $seconds . $secondsPlural;
                } else {
                    // Duration is an hour or more, display in hours, minutes, and seconds
                    $hours = floor($durationSeconds / 3600);
                    $minutes = floor(($durationSeconds % 3600) / 60);
                    $seconds = $durationSeconds % 60;
                    $hoursPlural = $hours == 1 ? " hour :" : " hours :";
                    $minutesPlural = $minutes == 1 ? " min :" : " mins :";
                    $secondsPlural = $seconds == 1 ? " sec" : " secs";
                    $durationFormatted = $hours . $hoursPlural . $minutes . $minutesPlural . $seconds . $secondsPlural;
                }
                // Assign the formatted duration back to the session
                $session['duration'] = $durationFormatted;

            }
        }
        

        // return $userCourseAccess;
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
}