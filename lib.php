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
 * Strings for component 'local_message', language 'en'
 *
 * @package   local_attendance
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

function get_attendance(){
    global $DB, $USER;
    $sql = '
        SELECT l.id AS "Log_event_id",
        l.timecreated AS "Timestamp",
        DATE_FORMAT(FROM_UNIXTIME(l.timecreated), "%H:%i") AS "Time",
        DATE_FORMAT(FROM_UNIXTIME(l.timecreated), "%Y-%m-%d") AS "Date",
        l.action,
        u.id,
        u.username,
        l.origin,
        l.ip
        FROM
            mdl_logstore_standard_log l
        JOIN
            mdl_user u ON u.id = l.userid
        WHERE
            l.action = "loggedin"
            AND l.userid = :userid
            AND l.timecreated = (
                SELECT MAX(timecreated)
                FROM mdl_logstore_standard_log
                WHERE action = "loggedin" AND userid = l.userid
            )
        ORDER BY
            l.timecreated;
';


    $param =['userid' => $USER->id];
    $attendance = $DB->get_record_sql($sql,$param);

    print_r($attendance);
    $param = ['userid' => $USER->id];
    $attendance = $DB->get_record_sql($sql, $param);
    
    if ($attendance) {
        $record = new stdClass();
        $record->id = null;
        $record->username = $attendance->username;
        $record->date = $attendance->date;
        $record->login = $attendance->time;
        $record->logout = "Not logged out";
        $record->timespend = "0:00";
    
        $result = $DB->insert_record('local_attendance', $record);
    
        if ($result) {
            echo "Record inserted successfully";
        } else {
            echo "Record insertion failed";
        }
    } else {
        echo "No 'loggedin' record found for the user.";
    }


}
 

function local_attendance_before_footer(){
    if (isloggedin()) {
        // get_attendance();
    } else {
        echo "User is not logged in.";
     }
}