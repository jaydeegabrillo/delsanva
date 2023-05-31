<?php

namespace App\Models;

use CodeIgniter\Model;

class DashboardModel extends Model
{
    public function log($data = array()){
        $db = \Config\Database::connect();

        if($data['id'] != ''){
            $result = $db->table('attendance')->where('id', $data['id'])->update(['clock_out' => date('Y-m-d h:i:s')]);
        }else{
            $result = $db->table('attendance')->insert($data);
        }

        if($result){
            echo "true";
        }else{
            echo "false";
        }
    }

    public function get_clock_status($user_id){
        $db = \Config\Database::connect();

        $result = $db->table('attendance')->where('date', date('Y-m-d'))->where('user_id', $user_id)->orderBy('id', 'desc')->get()->getRow();
        $res['id'] = '';
        $res['time'] = '';
        $res['time_out'] = '';

        if(!$result){
            $res['status'] = 'out';
        } else {
            $res['id'] = $result->id;
            $res['time'] = $result->clock_in;
            $res['time_out'] = $result->clock_out;
            if($result->clock_out == '0000-00-00 00:00:00'){
                $res['status'] = 'in';
            }else{
                $res['status'] = 'out';
                $res['id'] = '';
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
