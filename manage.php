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

require_once(__DIR__.'/../../config.php'); 
require_once('lib.php');
// require_once($CFG->dirroot.'/local/attendance/formlib.php');
$folderPath = $CFG->dirroot.'/local/attendance/classes/';
$directory = opendir($folderPath);

while ($file = readdir($directory)) {
    if (pathinfo($file, PATHINFO_EXTENSION) == 'php') {
        require_once $folderPath . $file;
    }
}

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
$PAGE->requires->css(new moodle_url('/local/attendance/template.css'));
$PAGE->requires->js(new moodle_url('https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js'));
$PAGE->requires->js(new moodle_url('https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js'));
// $PAGE->requires->css(new moodle_url('https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css'));
$PAGE->requires->css(new moodle_url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'));




echo $OUTPUT->header();

$course = new render();
$courseData = $course->coursenamedata();

$data = [
    'coursetitleData' => $courseData,
];

// $access = new access();
// $accessData = $access->getAccess();
// echo "<pre>";
// print_r($accessData);
// echo "</pre>";
//this example command for checking the sync changes
//other command for checking the sync changes


echo $OUTPUT->render_from_template('local_attendance/render',$data);
// echo $OUTPUT->render_from_template('local_attendance/example', $data);

echo $OUTPUT->footer();