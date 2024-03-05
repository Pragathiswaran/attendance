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
            $userCourseKey = $userId . '_' . $courseId;

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

            // Check for a new session or continuation of an existing one
            if (empty($sessions) || $activity->timestamp - end($sessions)['end_timestamp'] > 1800) {
                // New session
                $sessions[] = [
                    'date' => $date,
                    'start_time' => $activity->time_only,
                    'end_time' => $activity->time_only, // Initially the same, will update
                    'start_timestamp' => $activity->timestamp,
                    'end_timestamp' => $activity->timestamp, // Initially the same, will update
                ];
            } else {
                // Continuing an existing session, update the end time
                $lastKey = key(array_slice($sessions, -1, 1, TRUE));
                $sessions[$lastKey]['end_time'] = $activity->time_only;
                $sessions[$lastKey]['end_timestamp'] = $activity->timestamp;
            }
        }

        // Calculate session durations
        foreach ($userCourseAccess as &$userCourse) {
            foreach ($userCourse['sessions'] as &$session) {
                $durationSeconds = $session['end_timestamp'] - $session['start_timestamp'];
                $session['duration'] = gmdate('H:i:s', $durationSeconds);
            }
        }

        return $userCourseAccess;
    }
}
