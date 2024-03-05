<?php

class local_attendance {
    public function getUserCourseActivity() {
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
                FROM mdl_logstore_standard_log l
                JOIN mdl_user u ON u.id = l.userid
                JOIN mdl_course c ON c.id = l.courseid
                WHERE l.courseid != 0 AND l.userid != :exclude_userid
                ORDER BY l.timecreated";

        $activityData = $DB->get_records_sql($sql, $param);
        $userCourseAccess = [];

        foreach ($activityData as $activity) {
            $userId = $activity->userid;
            $courseId = $activity->courseid;
            $userCourseKey = "{$userId}_{$courseId}";

            if (!isset($userCourseAccess[$userCourseKey])) {
                $userCourseAccess[$userCourseKey] = [
                    'userid' => $userId,
                    'username' => $activity->username,
                    'courseid' => $courseId,
                    'course_name' => $activity->course_name,
                    'sessions' => [],
                    'date' => $activity->date
                ];
            }

            $isNewSession = true;
            $sessions = &$userCourseAccess[$userCourseKey]['sessions'];
            if (!empty($sessions)) {
                $lastSession = &$sessions[count($sessions) - 1];
                $timeSinceLastActivity = $activity->timestamp - $lastSession['end_timestamp'];
                if ($timeSinceLastActivity <= 1800) {
                    $isNewSession = false;
                    $lastSession['end_time'] = $activity->time_only;
                    $lastSession['end_timestamp'] = $activity->timestamp;
                }
            }

            if ($isNewSession) {
                $sessions[] = [
                    'start_time' => $activity->time_only,
                    'end_time' => $activity->time_only,
                    'start_timestamp' => $activity->timestamp,
                    'end_timestamp' => $activity->timestamp
                ];
            }
        }

        foreach ($userCourseAccess as &$userCourse) {
            foreach ($userCourse['sessions'] as &$session) {
                $durationSeconds = $session['end_timestamp'] - $session['start_timestamp'];
                $session['duration'] = gmdate('H:i:s', $durationSeconds);
            }
        }

        return $userCourseAccess;
    }

    public function saveUserCourseActivityToDatabase() {
        global $DB;

        $userCourseAccess = $this->getUserCourseActivity();

        
        foreach ($userCourseAccess as $access) {
            foreach ($access['sessions'] as $session) {
                $record = new stdClass();
                $record->userid = $access['userid'];
                $record->username = $access['username'];
                $record->courseid = $access['courseid'];
                $record->course_name = $access['course_name'];
                $record->date = $access['date'];
                $record->start_time = $session['start_time'];
                $record->end_time = $session['end_time'];
                $duration = explode(":", $session['duration']);
                $record->duration = ($duration[0] * 3600) + ($duration[1] * 60) + $duration[2];

                // Assuming 'user_course_sessions' is your database table name
                $DB->insert_record('user_course_sessions', $record);
            }
        }
    }
}
