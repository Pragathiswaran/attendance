<?php

/**
 * Short description for class.
 *
 * Long description for class (if any)...
 *
 * @package    local_attendance
 * @author     Prgathiswaran, Ramyasri, Rukkesh
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class quiz{
    public function quizAttempt(){
        global $DB;
    
        $sql ='SELECT qa.id AS attempt_id,
        qa.timestart,
        qa.timefinish,
        FROM_UNIXTIME(qa.timestart, "%Y-%m-%d") AS date_utc,
        FROM_UNIXTIME(qa.timestart, "%Y-%m-%d %H:%i:%s") AS timestart_utc,
        FROM_UNIXTIME(qa.timefinish, "%Y-%m-%d %H:%i:%s") AS timefinish_utc,
        qa.userid,
        u.username,
        q.name AS quiz_name,
        c.fullname AS course_name
        FROM mdl_quiz_attempts qa
        JOIN mdl_user u ON qa.userid = u.id
        JOIN mdl_quiz q ON qa.quiz = q.id
        JOIN mdl_course c ON q.course = c.id
        WHERE c.id = :your_course_id;';
    
        $param = ['your_course_id' => 2];
    
        $quiz = $DB->get_records_sql($sql,$param);
    
        $quizData = [];
    
        
        foreach ($quiz as $entries => $value) {
    
            $startTime = new DateTime($value->timestart_utc);
            $finishTime = new DateTime($value->timefinish_utc);
    
            $between = $startTime->diff($finishTime);
            $parts = [];
            if ($between->h > 0) {
                $parts[] = $between->h . ' ' . ($between->h == 1 ? 'hour' : 'hours');
            }
            if ($between->i > 0) {
                $parts[] = $between->i . ' ' . ($between->i == 1 ? 'min' : 'mins');
            }
            if ($between->s > 0 || count($parts) == 0) {
                $parts[] = $between->s . ' ' . ($between->s == 1 ? 'sec' : 'secs');
            }

            $duration = implode(' : ', $parts);
            $quizData[] = [
                'userid' => $value->userid,
                'username' => $value->username,
                'course_name' => $value->course_name,
                'quiz_name' => $value->quiz_name,
                'date' => $value->date_utc,
                'timestart' => $startTime->format('H:i:s'),
                'timefinish' =>$finishTime->format('H:i:s'),
                'duration' => $duration
            ];
        }
    
        return $quizData;
    }
}
 
