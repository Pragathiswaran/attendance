<?php 
// /local/attendance/renderer.php

defined('MOODLE_INTERNAL') || die();

class local_attendance_renderer extends plugin_renderer_base {
    public function render_attendance_modal_button() {
        $this->page->requires->js_call_amd('local_attendance/attendance_modal', 'init');
        return html_writer::tag('button', 'Open Attendance Form', array('id' => 'openModalButton'));
    }
}
