<?php 

require_once(__DIR__.'/../../config.php'); 
require_once($CFG->dirroot.'/local/attendance/classes/access.class.php'); 
require_login();

$option = optional_param('options', null, PARAM_TEXT);
// echo $option;

if($option === 'Login'){
    echo "
    <table class='generaltable'>
    <thead>
        <tr>
            <th>Log_event_id</th>
            <th>timestamp</th>
            <th>time_utc</th>
            <th>action</th>
            <th>username</th>
            <th>origin</th>
            <th>ip</th>
        </tr>
    </thead>
    <tbody>
    ";
    
} else if($option === 'Course'){
    echo"
    <table class='table table-bordered table table-striped' style='margin-top:15px;'>
    <thead style='background-color:#8585ff; color:white; font-family: Arial, sans-serif; font-size : 16px;'>
      <tr>
          <th>".get_string('userid', 'local_attendance')."</th>
          <th>".get_string('username', 'local_attendance')."</th>
          <th>".get_string('courseid', 'local_attendance')."</th>
          <th>".get_string('coursename', 'local_attendance')."</th>
          <th>".get_string('sessiondate', 'local_attendance')."</th>
          <th>".get_string('sessionstart', 'local_attendance')."</th>
          <th>".get_string('sessionend', 'local_attendance')."</th>
          <th>".get_string('sessionduration', 'local_attendance')."</th>
      </tr>
    </thead>
    <tbody id='myTable' style='font-family: Arial, sans-serif;font-size: 14px;' class='testrender'>
       
    </tbody>
  </table>
    ";
}else if($option === 'Quiz'){
    echo"
    <table class='generaltable'>
    <thead>
        <tr>
        <th>".get_string('userid', 'local_attendance')."</th>
        <th>".get_string('username', 'local_attendance')."</th>
        <th>".get_string('coursename', 'local_attendance')."</th>
            <th>Quiz Name</th>
            <th>Date</th>
            <th>Time Start</th>
            <th>Time End</th>
            <th>Duration</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
    </table>
    ";
}else if($option === 'Assignment'){
    echo"
    <table class='generaltable'>
    <thead>
        <tr>
            <th>".get_string('userid', 'local_attendance')."</th>
            <th>".get_string('username', 'local_attendance')."</th>
            <th>".get_string('courseid', 'local_attendance')."</th>
            <th>".get_string('coursename', 'local_attendance')."</th>
            <th>Assignment Name</th>
            <th>Submission</th>
            <th>Due Date</th>
            <th>Time Start</th>
            <th>Time End</th>
            <th>Duration</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
    </table>
    ";
}