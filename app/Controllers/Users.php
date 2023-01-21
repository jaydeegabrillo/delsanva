<?php

namespace App\Controllers;
use \Hermawan\DataTables\DataTable;

class Users extends BaseController
{
    public function index()
    {
        $data['title'] = 'Users';
        $script['js_scripts'] = array();
        $path = 'pages/users/index';
        array_push($script['js_scripts'], '/pages/users/users.js');
        
        $this->load_view($data,$script,$path);
    }

    public function users_datatable(){
        $db = db_connect();
        $builder = $db->table('users');

        return DataTable::of($builder)->toJson();
    }
}
