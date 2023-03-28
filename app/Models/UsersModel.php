<?php

namespace App\Models;

use CodeIgniter\Model;

class UsersModel extends Model
{
    public function check_user($data = array()){
        $db = \Config\Database::connect();

        $sql = "SELECT id, CONCAT(u.first_name, ' ', u.last_name) as name, email FROM users u WHERE u.email = '{$data['email']}' AND u.password = '".$data['password']."' AND deleted = 0";

        $user = $db->query($sql)->getRowArray();

        if($user){
            return $user;
        }else{
            return 0;
        }
    }
}
