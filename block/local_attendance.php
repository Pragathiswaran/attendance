<?php

class block_local_attendance extends block_base {
    public function init() {
        $this->title = "block";
    }

    public function get_content() {
        if ($this->content !== null) {
            return $this->content;
        }

        $this->content         = new stdClass();
        $this->content->text   = '<a href="YOUR_BUTTON_URL" class="btn btn-primary">Your Button</a>';
        $this->content->footer = '';

        return $this->content;
    }
}
