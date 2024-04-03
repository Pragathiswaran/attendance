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
// require_once($CFG->libdir . '/sessionlib.php');
$folderPath = $CFG->dirroot.'/local/attendance/classes/';

$directory = opendir($folderPath);

while ($file = readdir($directory)) {
   
    if (pathinfo($file, PATHINFO_EXTENSION) == 'php') {
       
        require_once $folderPath . $file;
    }
}

require_login();
$context = context_system::instance();
require_capability('moodle/site:config', $context); 

$PAGE->set_url(new moodle_url('/local/attendance/manage.php'));
$PAGE->set_context($context);
$PAGE->set_title(get_string('pluginname', 'local_attendance'));
$PAGE->set_heading(get_string('pluginname', 'local_attendance'));
$PAGE->requires->js(new moodle_url('/local/attendance/amd/src/script.js'));
$PAGE->requires->js(new moodle_url('/local/attendance/amd/src/options.js'));


echo $OUTPUT->header();

$course = new render();
$courseData = $course->coursenamedata();

$data = [
    'coursetitleData' => $courseData,
];

echo $OUTPUT->render_from_template('local_attendance/render',$data);

echo $OUTPUT->footer();
