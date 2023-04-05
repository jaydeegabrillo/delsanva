<?php

namespace App\Models;

use CodeIgniter\Model;

class LoginModel extends Model
{
    public function check_user($data = array()){
        $db = \Config\Database::connect();

        $sql = "SELECT id, CONCAT(u.first_name, ' ', u.last_name) as name, email, position_id FROM users u WHERE u.email = '{$data['email']}' AND u.password = '".$data['password']."' AND deleted = 0";

        $user = $db->query($sql)->getRowArray();

        if($user){
            return $user;
        }else{
            return 0;
        }
    }

    public function user_daily_log($id){
        $db = \Config\Database::connect();
        $date = date('Y-m-d');

        $sql = "SELECT * FROM assignments a WHERE a.start_date <= '$date' AND a.end_date >= '$date' AND assigned_user = $id AND deleted = 0";

        $user_log = $db->query($sql)->getRow();

        if($user_log){
            $log_query = "SELECT id FROM daily_logs WHERE assignment_id = $user_log->id AND date = '$date'";
            $log_exists = $db->query($log_query)->getRow();

            if(!$log_exists){
                $insert = array(
                    'user_id' => $user_log->assigned_user,
                    'assignment_id' => $user_log->id,
                    'date' => date('Y-m-d')
                );

                $db->table('daily_logs')->insert($insert);
            }

            return 1;
        }else{
            return 0;
        }
    }
}
