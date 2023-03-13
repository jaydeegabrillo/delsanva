<?php

namespace App\Models;

use CodeIgniter\Model;

class TimesheetModel extends Model
{
    public function timesheet($id){
        $db = \Config\Database::connect();
        if($id == 0){
            $timesheet = $db->table('daily_logs')->select('date, clock_in, clock_out');
        }else{
            $timesheet = $db->table('daily_logs')->select('date, clock_in, clock_out')->where('user_id', $id);
        }
        return $timesheet;
    }

    public function timesheet_pdf($id,$data=array()){
        $db = \Config\Database::connect();

        if($id == 0){
            $timesheet = $db->table('attendance a')->select('date, clock_in, clock_out')->where('date >=', $data['date_from'])->where('date <=', $data['date_to'])->orderBy('date');
        }else{
            if($data){
                $timesheet = $db->table('attendance a')->select('date, clock_in, clock_out')->where('user_id', $id)->where('date >=', $data['date_from'])->where('date <=', $data['date_to'])->orderBy('date');
            }else{
                $timesheet = $db->table('attendance a')->select('date, clock_in, clock_out')->where('user_id', $id)->orderBy('date');
            }
        }

        return $timesheet;
    }

}
