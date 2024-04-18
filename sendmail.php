<?php
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
// Send email to user

if(email_to_user($recipientUser, $senderUser, $subject, $message)){
    //echo "success";
    return true;
} else {
    //echo "failed";
    return false;
}

}