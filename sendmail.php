<?php
require_once(__DIR__.'/../../config.php');

// Replace with recipient's user ID
$recipientUserId = 4;

// Get the recipient user object
$recipientUser = $DB->get_record('user', array('id' => $recipientUserId));

// Sender user object (optional)
$senderUser = $USER; // Assuming $USER is the currently logged-in user

// Email parameters
$subject = 'Test Email from Moodle to send emaol eith attachment programmatically';
$message = 'This is a test email sent from Moodle programmatically';
$attachement_path = 'file.pdf';
$attachement_name = 'attachment.pdf';

// Send email to user

if(email_to_user($recipientUser, $senderUser, $subject, $message,"",$attachement_path,$attachement_name)){
    echo "success";
} else {
    echo "failed";
}

