<?php

namespace App\Models;

use CodeIgniter\Model;

class DashboardModel extends Model
{
    public function log($data = array()){
        $db = \Config\Database::connect();
        
        if($data['clock_in'] == ''){
            $result = $db->table('attendance')->where('user_id', $data['user_id'])->where('date', date('Y-m-d'))->update(['clock_out' => date('Y-m-d h:i:s')]);
        }else{
            $result = $db->table('attendance')->insert($data);
        }
        
        if($result){
            return true;
        }else{
            return false;
        }
    }

    public function get_clock_status($user_id){
        $db = \Config\Database::connect();

        $result = $db->table('attendance')->where('date', date('Y-m-d'))->where('user_id', $user_id)->get()->getRow();
        
        if(!$result){
            $res = false;
        } else {
            if($result->clock_out == '0000-00-00 00:00:00'){
                $res = 'in';
            }else{
                $res = 'out';
            }
        }
        return $res;
    }

    public function get_attendance($user_id){
        $db = \Config\Database::connect();

        $attendance = $db->table('attendance')->where('user_id', $user_id)->get()->getResult();
        $ctr = 0;
        foreach ($attendance as $key => $value) {
            if($value->clock_in == '0000-00-00 00:00:00' || $value->clock_out == '0000-00-00 00:00:00'){
                $ctr++;
            }
        }
        
        return $ctr;
    }
}
