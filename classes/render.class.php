<?php

class render {
    public function coursenamedata() {
        global $DB, $COURSE;

        // Get all courses
        $Sql = "SELECT * FROM {course}";
        $Query = $DB->get_records_sql($Sql);

        $querydata = [];
        foreach ($Query as $course) {
            if ($course->category > 0) {
                $context = context_course::instance($course->id);
                if (has_capability('moodle/course:update', $context)) {
                    $querydata[] = [
                        'coursenames' => $course->fullname,
                    ];
                } 
            }
        }

        return $querydata;
    }
}
