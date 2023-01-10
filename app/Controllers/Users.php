<?php

namespace App\Controllers;
use \Hermawan\DataTables\DataTable;

class Users extends BaseController
{
    public function index()
    {
        $data['title'] = 'Users';
        $script['js_scripts'] = array();
        array_push($script['js_scripts'], '/pages/users/users.js');
        echo view('layout/header');
        echo view('layout/sidebar', $data);
        echo view('pages/users/index');
        echo view('layout/footer',$script);
    }

    public function users_datatable(){
        $db = db_connect();
        $builder = $db->table('users');

        return DataTable::of($builder)->toJson();
    }
}
