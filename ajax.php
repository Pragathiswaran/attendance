<?php 

require_once(__DIR__.'/../../config.php'); 
require_once($CFG->dirroot.'/local/attendance/classes/course.class.php'); 
require_login();

$coursename = optional_param('coursename', null, PARAM_TEXT);
echo $cousername;

$attendance = new course();

$userCourseAccessData = $attendance->getUserCourseActivity($coursename);
 
foreach($userCourseAccessData as $data){
    echo"<tr>";
    echo"<td>".$data['userid']."</td>";
    echo"<td>".$data['username']."</td>";
    echo"<td>".$data['courseid']."</td>";
    echo"<td>".$data['coursename']."</td>";
    echo"<td>".$data['date']."</td>";
    echo"<td>".$data['start_time']."</td>";
    echo"<td>".$data['end_time']."</td>";
    echo"<td>".$data['duration']."</td>";
    echo"</tr>";
    
}