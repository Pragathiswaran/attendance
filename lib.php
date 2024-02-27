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

  require_once($CFG->dirroot . '/config.php');

 function get_user_activity(){
    
    global $DB;
    $sql = 'SELECT
    l.id AS "Log_event_id",   
   l.timecreated AS "Timestamp",
   DATE_FORMAT(FROM_UNIXTIME(l.timecreated),"%H:%i") AS "Time",
   DATE_FORMAT(FROM_UNIXTIME(l.timecreated),"%Y-%m-%d") AS "Date",
   u.username,
   u.id AS "User_ID",
   l.action AS "Last_Action"
FROM {logstore_standard_log} l
JOIN {user} u ON u.id = l.userid
WHERE l.action = "loggedin"
   AND l.userid = :userid
ORDER BY l.timecreated DESC
LIMIT 1;
';

$params = ['userid' => 2];

$logEntries = $DB->get_records_sql($sql, $params);

echo "<pre>";
print_r($logEntries);
echo "</pre>";
}

function get_recods(){
    global $DB;
    $sql = 'SELECT
     l.id AS "Log_event_id",   
    l.timecreated AS "Timestamp",
    DATE_FORMAT(FROM_UNIXTIME(l.timecreated),"%H:%i") AS "Time",
    DATE_FORMAT(FROM_UNIXTIME(l.timecreated),"%Y-%m-%d") AS "Date",
    u.username,
    u.id AS "User_ID",
    l.action AS "Last_Action"
        FROM {logstore_standard_log} l
        JOIN {user} u ON u.id = l.userid
        WHERE l.action = "loggedout"
        AND l.userid = :userid
        ORDER BY l.timecreated DESC
        LIMIT 1;
        ;
';

$params = ['userid' => 2];

$logEntries = $DB->get_records_sql($sql, $params);

echo "<pre>";
print_r($logEntries);
echo "</pre>";

$sql = 'SELECT
l.id AS "Log_event_id",   
l.timecreated AS "Timestamp",
DATE_FORMAT(FROM_UNIXTIME(l.timecreated),"%H:%i") AS "Time",
DATE_FORMAT(FROM_UNIXTIME(l.timecreated),"%Y-%m-%d") AS "Date",
u.username,
u.id AS "User_ID",
l.action AS "Last_Action"
FROM {logstore_standard_log} l
JOIN {user} u ON u.id = l.userid
WHERE l.action = "loggedin"
AND l.userid = :userid
ORDER BY l.timecreated DESC
LIMIT 1;
;
';

$params = ['userid' => 2];

$logEntries1 = $DB->get_records_sql($sql, $params);

echo "<pre>";
print_r($logEntries1);
echo "</pre>";


$record = new stdClass();
$record->username = $logEntries1["username"];
$record->id = $logEntries1["user_id"];
$record->date = $logEntries1["date"];
$record->login = $logEntries["login"];
$record->logout = $logEntries1["logout"];
$record->timespend = "";

}

function local_attendance_before_footer(){
    // get_user_activity();
    get_recods();
}
