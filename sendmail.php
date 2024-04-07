<?php
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
    global $USER, $CFG;

    $subject = 'Attendance Report';
    $message = "Dear User,\n\n";
    $message .= "Please find the attendance report attached.\n\n";
    $message .= "Report Data:\n";
    $message .= $reportData;

    // Get recipient user object by email
    $recipient = $DB->get_records_sql('user', array('email' => $toEmail));
    if (!$recipient) {
        echo "User with email $toEmail not found.";
        return;
    }

    // Send email using Moodle's email_to_user() function
    $result = email_to_user($recipient, $USER, $subject, $message);

    if ($result) {
        echo "Attendance report sent successfully to $toEmail.";
    } else {
        echo "Failed to send attendance report. Check SMTP logs for details.";
    }
}

// Example: Fetching report data
$reportData = fetchAttendanceReport();

// Example: Sending report to a specific email address
$toEmail = 'mpragathiswaran@gmail.com';
sendAttendanceReportByEmail($toEmail, $reportData);
?>
