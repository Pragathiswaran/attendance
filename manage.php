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
require_once($CFG->dirroot.'/local/attendance/lib.php'); 

require_login();
$context = context_system::instance();
require_capability('moodle/site:config', $context); 

$PAGE->set_url(new moodle_url('/local/attendance/manage.php'));
$PAGE->set_context($context);
$PAGE->set_title(get_string('pluginname', 'local_attendance'));
$PAGE->set_heading(get_string('pluginname', 'local_attendance'));

echo $OUTPUT->header();

$attendance = new local_attendance();
$userCourseAccess = $attendance->quizAttempt();
// $userCourseAccess = $attendance->ShowData();
// $userCourseAccess = $attendance->assignment();
// echo $OUTPUT->render_from_template('local_attendance/manage', ['dateWiseAccess' => $userCourseAccess]);
echo $OUTPUT->render_from_template('local_attendance/quiz', ['quizData' => $userCourseAccess]);

echo $OUTPUT->footer();
