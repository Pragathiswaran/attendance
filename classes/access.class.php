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

 class access{
    public function getAccess(){
        global $DB;

        $sql = "SELECT
        l.id AS 'Log_event_id',
        l.timecreated AS 'Timestamp',
        DATE_FORMAT(FROM_UNIXTIME(l.timecreated), '%Y-%m-%d %H:%i:%s') AS 'Time_UTC',
        DATE_FORMAT(FROM_UNIXTIME(l.timecreated), '%H:%i:%s') AS 'time',
        DATE_FORMAT(FROM_UNIXTIME(l.timecreated), '%Y-%m-%d') AS 'date',
        l.action,
        u.id AS 'userid', -- Correct alias for userid
        u.username
    FROM mdl_logstore_standard_log l
    JOIN mdl_user u ON u.id = l.userid
    WHERE l.action IN ('loggedin', 'loggedout')
    AND u.id <> 2 
    ORDER BY l.timecreated;
    ";

      //   $param = ['userid' => 3];

        $getAccess = $DB->get_records_sql($sql);

        $userlogin = [];
$duration = [];

// Iterate through the log events
foreach ($getAccess as $data) {
    if ($data->action == "loggedin") {
        // Store login time with user ID as the key
        $userlogin[$data->userid] = new DateTime($data->time);
    } elseif ($data->action == "loggedout") {
        // Check if there was a corresponding login event for this logout
        if (isset($userlogin[$data->userid])) {
            $loginTime = $userlogin[$data->userid];
            $logoutTime = new DateTime($data->time);

            // Calculate the duration in seconds
            $durationInSeconds = $logoutTime->getTimestamp() - $loginTime->getTimestamp();
            if ($durationInSeconds < 60) {
                // Duration is less than a minute, display in seconds only
                $durationFormatted = $durationInSeconds . " sec";
            } elseif ($durationInSeconds < 3600) {
                // Duration is less than an hour, but at least a minute, display in minutes and seconds
                $minutes = floor($durationInSeconds / 60);
                $seconds = $durationInSeconds % 60;
                $durationFormatted = $minutes . " min " . $seconds . " sec";
            } else {
                // Duration is an hour or more, display in hours, minutes, and seconds
                $hours = floor($durationInSeconds / 3600);
                $minutes = floor(($durationInSeconds % 3600) / 60);
                $seconds = $durationInSeconds % 60;
                $durationFormatted = $hours . " hour " . $minutes . " min " . $seconds . " sec";
            }

            // Store duration information in the array
            $duration[] = [
                'userid' => $data->userid,
                'username' => $data->username,
                'date' => $data->date,
                'login_time' => $loginTime->format('H:i:s'),
                'logout_time' => $logoutTime->format('H:i:s'),
                'duration' => $durationFormatted,
            ];

            // Remove the login time entry from the array
            unset($userlogin[$data->userid]);
        }
    }
}

// Output the duration array (you can use this array as needed in your application)
// print_r($duration);
       return $duration;
    }
 }