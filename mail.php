<?php 

require_once(__DIR__.'/../../config.php'); 
//require_once($CFG->dirroot.'/local/attendance/classes/course.class.php'); 
require_login();

$table = optional_param('table', null, PARAM_RAW);
echo $table;