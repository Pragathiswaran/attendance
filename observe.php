<?php
class local_attendance_observer
{
	//Users observers
	public static function user_loggedin(\core\event\user_created $event)
    {
        $event_data = $event->get_data();
        var_dump(json_encode($event_data));
        die();
    }
}