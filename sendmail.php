<?php
require_once(__DIR__.'/../../config.php');

require_login();

function sendingmail($user,$subjectcontent,$messages){

    global $DB, $USER;

    // Replace with recipient's user ID
    // Get the recipient user object
    $recipientUser = $DB->get_record('user', array('username' => $user));

    // Sender user object (optional)
    $senderUser = $USER; // Assuming $USER is the currently logged-in user

    // Email parameters
    $subject = $subjectcontent;
    $message = $messages;
    $attachement_path = 'file.pdf';
    $attachement_name = 'attachment.pdf';

    // Send email to user

    if(email_to_user($recipientUser, $senderUser, $subject, $message,"",$attachement_path,$attachement_name)){
        return true;
    } else {
        return false;
    }
}

