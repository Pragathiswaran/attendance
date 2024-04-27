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
// require_once($CFG->libdir.'/templates/modal.mustache');
require_once($CFG->dirroot.'/local/attendance/classes/render.class.php');
// require_once($CFG->dirroot.'/lib/templates/modal.mustache');
// require_once($CFG->dirroot.'/local/attendance/formlib.php');
// require_once($CFG->dirroot.'/local/attendance/classes/form/email.php');


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
$PAGE->requires->js(new moodle_url('/local/attendance/amd/src/mail.js'));
// $PAGE->requires->js(new moodle_url('/local/attendance/amd/src/modal.js'));
$PAGE->requires->css(new moodle_url('/local/attendance/template.css'));
$PAGE->requires->css(new moodle_url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css'));

$course = new render();
$courseData = $course->coursenamedata();


$mform = new email();

echo $OUTPUT->header();

$data =(object) [
    'coursetitleData' => $courseData,
    'emailurl' => new moodle_url('/local/attendance/email.php'),
    'barchart' => new moodle_url('/local/attendance/bar_chart.php'),
    'piechart' => new moodle_url('/local/attendance/pie_chart.php'),
    'email' => new moodle_url('/local/attendance/amd/src/modal.js')
];


echo $OUTPUT->render_from_template('local_attendance/render',$data);

echo $OUTPUT->footer();