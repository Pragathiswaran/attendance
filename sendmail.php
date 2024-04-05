<?php

// Test SMTP connection

// Include Moodle configuration file
require_once('../../config.php');
global $CFG, $DB, $USER;

// Enable SMTP debugging
$CFG->debugsmtp = true;

// Ensure script is accessed within Moodle
require_login();

// Function to fetch report data (replace with your logic)
function fetchAttendanceReport() {
    // Example: Fetching report data from your plugin's database tables
    $reportData = "Attendance Report Data Here...";
    return $reportData;
}

// Function to send email with report data
function sendAttendanceReportByEmail($toEmail, $reportData) {
    global $USER;

    $subject = 'Attendance Report';
    $message = "Dear User,\n\n";
    $message .= "Please find the attendance report attached.\n\n";
    $message .= "Report Data:\n";
    $message .= $reportData;

    $headers = 'From: ' . $USER->email . "\r\n" .
               'Reply-To: ' . $USER->email . "\r\n" .
               'X-Mailer: PHP/' . phpversion();

    // Send email using Moodle's email_to_user() function
    $result = email_to_user($toEmail, $USER, $subject, $message);

    if ($result) {
        echo "Attendance report sent successfully to $toEmail.";
    } else {
        echo "Failed to send attendance report. Check SMTP logs for details.";
    }
}

// Example: Fetching report data
$reportData = fetchAttendanceReport();

// Example: Sending report to a specific email address
$toEmail = 'mihaan59@example.com';
sendAttendanceReportByEmail($toEmail, $reportData);


