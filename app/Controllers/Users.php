<?php

namespace App\Controllers;
use \Hermawan\DataTables\DataTable;

class Users extends BaseController
{
    protected $db;

    public function __construct(){
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $data['title'] = 'Users';
        $script['js_scripts'] = array();
        // $path = 'pages/users/index';
        $path = array(
            'index' => 'pages/users/index',
            'modal' => 'pages/users/modal'
        );
        array_push($script['js_scripts'], '/pages/users/users.js');

        $this->load_view($data,$script,$path);
    }

    public function users_datatable(){
        $db = db_connect();
        $builder = $db->table('users');

        return DataTable::of($builder)->toJson();
    }

    public function add_user(){
        $encrypter = \Config\Services::encrypter();

        $cipher = "AES-256-CBC";
        $secret = "DelsanVA";
        $option = 0;

        $iv = str_repeat("0", openssl_cipher_iv_length($cipher));

        $posted = $this->request->getVar();
        unset($posted['/users/add_user']);

        $posted['password'] = openssl_encrypt($posted['password'], $cipher, $secret, $option, $iv);
        $posted['position_id'] = 1;
        $posted['date_added'] = date('Y-m-d H:i:s');

        $result = $this->db->table('users')->insert($posted);

        if($result){
            echo "true";
        } else {
            echo "false";
        }
    }
}
