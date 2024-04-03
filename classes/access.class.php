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
        DATE_FORMAT(FROM_UNIXTIME(l.timecreated),'%Y-%m-%d %H:%i') AS 'Time_UTC',
        l.action,
        u.username,
        l.origin,
        l.ip
        FROM mdl_logstore_standard_log l
        JOIN mdl_user u ON u.id = l.userid
        WHERE l.action IN ('loggedin','loggedout')
        AND l.userid = :userid
        ORDER BY l.timecreated";

        $param = ['userid' => 3];

        $getAccess = $DB->get_records_sql($sql,$param);

       return $getAccess;
    }
 }