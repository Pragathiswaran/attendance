<?php

namespace local_attendance\event;

defined('MOODLE_INTERNAL') || die();

class handler {
    public static function your_event_name(local_attendance_event $event) {
        // Handle the event here.
        $data = $event->get_data();
        // Perform actions based on the event data.
    }
}
