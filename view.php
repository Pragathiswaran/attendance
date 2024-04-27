<?php
require_once(__DIR__ . '/../../config.php'); // Include Moodle configuration
require_once($CFG->libdir . '/pagelib.php');

// Initialize Moodle page
$PAGE = new moodle_page();
$PAGE->set_context(context_system::instance()); // Set the context (replace with appropriate context)

// Require login to access this page
require_login();

// Now you can access Moodle global variables like $PAGE
$renderer = $PAGE->get_renderer('local_attendance');

// Render the modal button
echo $renderer->render_attendance_modal_button();
