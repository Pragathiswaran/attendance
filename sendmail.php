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
 * @author      Prgathiswaran Ramyasri Rukkesh
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once(__DIR__.'/../../config.php');

// Replace with recipient's user ID

function sendingmail($user, $emailtext, $emailmessage) {
    global $CFG, $DB, $USER;

    // Get the recipient user obje
$recipientUserId = $user;

// Get the recipient user object
$recipientUser = $DB->get_record('user', array('username' => $recipientUserId));

// Sender user object (optional)
$senderUser = $USER; // Assuming $USER is the currently logged-in user

// Email parameters
$subject = $emailtext;
$message = $emailmessage;
$attachmentname = 'Attendance_Report.pdf';
$path = __DIR__.'/output.pdf';
// Send email to user

if(email_to_user($recipientUser, $senderUser, $subject, $message,"",$path, $attachmentname)){
    //echo "success";
    return true;
} else {
    //echo "failed";
    return false;
}

}