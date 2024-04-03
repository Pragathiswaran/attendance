<?php

class render{
    public function coursenamedata(){
        global $DB;

        $Sql = "SELECT * FROM {course}";
        $Query = $DB->get_records_sql($Sql);

        $querydata = [];
        foreach($Query as $logs){
            if($logs->category > 0){
                $querydata[] = [
                    'coursename'=>$logs->fullname,
                ];
            }
        }
        
        return $querydata;
    }
}