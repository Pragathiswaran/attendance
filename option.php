<?php 

require_once(__DIR__.'/../../config.php'); 
require_once($CFG->dirroot.'/local/attendance/classes/access.class.php'); 
require_once($CFG->dirroot.'/local/attendance/classes/assignment.class.php');
require_once($CFG->dirroot.'/local/attendance/classes/quiz.class.php');
require_once($CFG->dirroot.'/lib/accesslib.php');
require_login();

$option = optional_param('options', null, PARAM_TEXT);
// echo $option;
if(has_capability('moodle/site:config', context_system::instance())){
if($option === 'Login'){
    $login = new access();
    $loginData = $login->getAccess();
    echo "
    <thead>
        <tr>
            <th>userid</th>
            <th>username</th>
            <th>Date</th>
            <th>Login</th>
            <th>Logout</th>
            <th>Duration</th>
        </tr>
    </thead>
    ";
    foreach($loginData as $data){
        if ($data['userid'] === $USER->id) 
            {
                continue;
            }
        echo "<tbody style='color:black; font-size:14px;'>";
        echo "<tr>";
        echo "<td>".$data['userid']."</td>";
        echo "<td>".$data['username']."</td>";
        echo "<td>".$data['date']."</td>";
        echo "<td>".$data['login_time']."</td>";
        echo "<td>".$data['logout_time']."</td>";
        echo "<td>".$data['duration']."</td>";
        echo "</tr>";
        echo "</tbody>";
    }
    
} else if($option === 'Course'){
    echo"
    <thead>
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
    <tbody id='testrender' style='color:black; font-size:14px;'>
    </tbody>
    ";
}else if ($option === 'Quiz') {
    echo "
    <table id='example'>
    <thead>
        <tr>
            <th>" . get_string('userid', 'local_attendance') . "</th>
            <th>" . get_string('username', 'local_attendance') . "</th>
            <th>" . get_string('coursename', 'local_attendance') . "</th>
            <th>Quiz Name</th>
            <th>Date</th>
            <th>Time Start</th>
            <th>Time End</th>
            <th>Duration</th>
        </tr>
    </thead>
    <tbody style='color:black; font-size:14px;'>
    </tbody>
    </table>";

    $quiz = new quiz();
    $quizData = $quiz->quizAttempt();
   
            foreach ($quizData as $data) {
               
                echo "<tbody style='color:black; font-size:14px;'>";
                echo "<tr>";
                echo "<td>".$data['userid']."</td>";
                echo "<td>".$data['username']."</td>";
                echo "<td>".$data['course_name']."</td>";
                echo "<td>".$data['quiz_name']."</td>";
                echo "<td>".$data['date']."</td>";
                echo "<td>".$data['timestart']."</td>";
                echo "<td>".$data['timefinish']."</td>";
                echo "<td>".$data['duration']."</td>";
                echo "</tr>";
                echo "</tbody>";
            }
        }
    }
else if($option === 'Assignment'){
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
    <tbody style='color:black; font-size:14px;'>
    </tbody>
    </table>
    ";
    $assignment = new assignment();
    $assignments = $assignment->Assignment();
    foreach($assignments as $data){
        if ($data['userid'] === $USER->id) 
            {
                continue;
            }
        echo "<tbody style='color:black; font-size:14px;'>";
        echo "<tr>";
        echo "<td>".$data['userid']."</td>";
        echo "<td>".$data['username']."</td>";
        echo "<td>".$data['course']."</td>";
        echo "<td>".$data['coursename']."</td>";
        echo "<td>".$data['name']."</td>";
        echo "<td>".$data['submission']."</td>";
        echo "<td>".$data['duedate']."</td>";
        echo "<td>".$data['timecreated']."</td>";
        echo "<td>".$data['timemodified']."</td>";
        echo "<td>".$data['duration']."</td>";
        echo "</tr>";
        echo "</tbody>";
    }
}

else{
    if($option === 'Course'){
        echo"
        <thead>
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
        <tbody id='testrender' style='color:black; font-size:14px;'>
        </tbody>
        ";
    }else if ($option === 'Quiz') {
        echo "
        <table id='example'>
        <thead>
            <tr>
                <th>" . get_string('userid', 'local_attendance') . "</th>
                <th>" . get_string('username', 'local_attendance') . "</th>
                <th>" . get_string('coursename', 'local_attendance') . "</th>
                <th>Quiz Name</th>
                <th>Date</th>
                <th>Time Start</th>
                <th>Time End</th>
                <th>Duration</th>
            </tr>
        </thead>
        <tbody style='color:black; font-size:14px;'>
        </tbody>
        </table>";
    
        $quiz = new quiz();
        $quizData = $quiz->quizAttempt();
function is_teacher_in_course($userid, $courseid) {
            $context = context_course::instance($courseid);
            return has_capability('moodle/course:update', $context, $userid);
        }
        
        // Get courses where the current user is enrolled
        $courses = enrol_get_users_courses($USER->id, true, 'id, fullname');
    
        foreach ($courses as $course) {
            if (is_teacher_in_course($USER->id, $course->id)) {
    
                foreach ($quizData as $data) {
                    if ($data['course_name'] !== $course->fullname || $data['userid'] === $USER->id) {
                        continue;
                    }
                    echo "<tbody style='color:black; font-size:14px;'>";
                    echo "<tr>";
                    echo "<td>".$data['userid']."</td>";
                    echo "<td>".$data['username']."</td>";
                    echo "<td>".$data['course_name']."</td>";
                    echo "<td>".$data['quiz_name']."</td>";
                    echo "<td>".$data['date']."</td>";
                    echo "<td>".$data['timestart']."</td>";
                    echo "<td>".$data['timefinish']."</td>";
                    echo "<td>".$data['duration']."</td>";
                    echo "</tr>";
                    echo "</tbody>";
                }
            }
        }
    }
    else if ($option === 'Assignment') {
        echo "
        <table id='example'>
        <thead>
            <tr>
                <th>" . get_string('userid', 'local_attendance') . "</th>
                <th>" . get_string('username', 'local_attendance') . "</th>
                <th>" . get_string('courseid', 'local_attendance') . "</th>
                <th>" . get_string('coursename', 'local_attendance') . "</th>
                <th>Assignment Name</th>
                <th>Submission</th>
                <th>Due Date</th>
                <th>Time Start</th>
                <th>Time End</th>
                <th>Duration</th>
            </tr>
        </thead>
        <tbody style='color:black; font-size:14px;'>
        </tbody>
        </table>";
    
        $assignment = new assignment();
        $assignments = $assignment->Assignment();
        function is_teacher_in_course($userid, $courseid) {
            $context = context_course::instance($courseid);
            return has_capability('moodle/course:update', $context, $userid);
        }
        
        $courses = enrol_get_users_courses($USER->id, true, 'id, fullname');
    
        foreach ($courses as $course) {
            if (is_teacher_in_course($USER->id, $course->id)) {
    
                foreach ($assignments as $data) {
                    if ($data['course'] !== $course->id || $data['userid'] === $USER->id) {
                        continue;
                    }
                    echo "<tbody style='color:black; font-size:14px;'>";
                    echo "<tr>";
                    echo "<td>".$data['userid']."</td>";
                    echo "<td>".$data['username']."</td>";
                    echo "<td>".$data['course']."</td>";
                    echo "<td>".$data['coursename']."</td>";
                    echo "<td>".$data['name']."</td>";
                    echo "<td>".$data['submission']."</td>";
                    echo "<td>".$data['duedate']."</td>";
                    echo "<td>".$data['timecreated']."</td>";
                    echo "<td>".$data['timemodified']."</td>";
                    echo "<td>".$data['duration']."</td>";
                    echo "</tr>";
                    echo "</tbody>";
                }
            }
        }
    }
    
}

// echo $OUTPUT->header();