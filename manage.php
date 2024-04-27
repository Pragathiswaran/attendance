<?php
// This file is part of Moodle Course Rollover Plugin
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
 * @author      Prgathiswaran
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @var stdClass $plugin
 */

require_once(__DIR__ . '/../../config.php');
require_once('lib.php');
require_once($CFG->dirroot.'/local/attendance/classes/form/email.php');
require_once($CFG->dirroot.'/local/attendance/classes/render.class.php');
require_once($CFG->dirroot.'/local/attendance/sendmail.php');
$context = context_system::instance();
global $DB, $CFG, $PAGE, $USER, $COURSE;
require_login();
if (has_capability('moodle/site:config', context_system::instance()) &&
    !has_capability('moodle/course:update', context_course::instance($COURSE->id))) {
        exit;
}

$PAGE->set_url(new moodle_url('/local/attendance/manage.php'));
$PAGE->set_context($context);
$PAGE->set_title(get_string('pluginname', 'local_attendance'));
$PAGE->set_heading(get_string('pluginname', 'local_attendance'));
$PAGE->requires->jQuery();
$PAGE->requires->js(new moodle_url('/local/attendance/amd/src/script.js'));
$PAGE->requires->js(new moodle_url('/local/attendance/amd/src/options.js'));
$PAGE->requires->js(new moodle_url('/local/attendance/amd/src/datatable.js'));
$PAGE->requires->js(new moodle_url('/local/attendance/amd/src/excel.js'));
$PAGE->requires->js(new moodle_url('/local/attendance/amd/src/print.js'));
$PAGE->requires->js(new moodle_url('/local/attendance/amd/src/copy.js'));
$PAGE->requires->js(new moodle_url('/local/attendance/amd/src/csv.js'));
$PAGE->requires->js(new moodle_url('/local/attendance/amd/src/filter.js'));
// $PAGE->requires->js(new moodle_url('/local/attendance/amd/src/modal_display.js'));
$PAGE->requires->css(new moodle_url('/local/attendance/template.css'));
$PAGE->requires->js(new moodle_url('/local/attendance/amd/src/pdf.js'));
$PAGE->requires->css(new moodle_url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css'));

$course = new render();
$courseData = $course->coursenamedata();

$mform = new email();

$filePath = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the POST request contains the PDF data
    if (isset($_POST['pdfData'])) {
        // Decode the data URL (if needed)
        $pdfData = $_POST['pdfData'];
        $pdfData = str_replace('data:application/pdf;base64,', '', $pdfData);
        $pdfData = base64_decode($pdfData);
        
        // Set the file path where the PDF will be saved
        $filePath = __DIR__.'/output.pdf';
        
        // Save the PDF to the specified location
        file_put_contents($filePath, $pdfData);
        
        // Send response to the client
        http_response_code(200);
        // echo 'PDF saved successfully!';
    } else {
        http_response_code(400);
        // echo 'PDF data not found in request.';
    }
} else {
    http_response_code(405);
    // echo 'Method not allowed.';
}

if ($mform->is_cancelled()) {
    redirect($CFG->wwwroot.'/local/attendance/manage.php');
 } else if ($fromform = $mform->get_data()) {
    if (sendingmail($fromform->email, $fromform->emailtext, $fromform->emailmessage)) {
        redirect($CFG->wwwroot.'/local/attendance/manage.php', 'form submitted', null, \core\output\notification::NOTIFY_SUCCESS);
    } else {
        redirect($CFG->wwwroot.'/local/attendance/manage.php', 'form not submitted', null, \core\output\notification::NOTIFY_ERROR);
    }
   
 }
echo $OUTPUT->header();

$data =(object)[
    'coursetitleData' => $courseData,
    'piechart' => new moodle_url('/local/attendance/pie_chart.php'),
    'barchart' => new moodle_url('/local/attendance/bar_chart.php'),
    'modalform' => $mform->render(),
];
if (has_capability('moodle/site:config', context_system::instance()))
{
    echo $OUTPUT->render_from_template('local_attendance/render',$data);
}
else{
    echo $OUTPUT->render_from_template('local_attendance/renders',$data);
}



echo $OUTPUT->footer();