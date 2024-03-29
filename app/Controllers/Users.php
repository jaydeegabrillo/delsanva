<?php

namespace App\Controllers;
use \Hermawan\DataTables\DataTable;

class Users extends BaseController
{

    public function index()
    {
        $data['id'] = $this->session->get('user_id');
        $data['title'] = 'Users';
        $script['js_scripts'] = array();
        
        $path = array(
            'pages/users/index',
            'pages/users/modal',
            'pages/dashboard/modal'
        );

        array_push($script['js_scripts'], '/pages/users/users.js');
        array_push($script['js_scripts'], '/pages/dashboard/dashboard.js');

        $this->load_view($data,$script,$path);
    }

    public function archive(){
        $data['id'] = $this->session->get('user_id');
        $data['title'] = 'Archive';
        $script['js_scripts'] = array();
        
        $path = array(
            'pages/users/archive',
            'pages/users/modal',
            'pages/dashboard/modal'
        );

        array_push($script['js_scripts'], '/pages/users/users.js');
        array_push($script['js_scripts'], '/pages/dashboard/dashboard.js');

        $this->load_view($data,$script,$path);
    }

    public function users_datatable(){
        $db = db_connect();
        $builder = $db->table('users')->select('id, CONCAT(first_name, " ", last_name) AS full_name, email ')->where('deleted',0);
        
        return DataTable::of($builder)
        ->add('action', function($row){
            return '<button type="button" class="btn btn-warning btn-sm view_user" data-toggle="modal" data-target="#add_user_modal" data-id="'.$row->id.'"><i class="fa fa-eye"></i> View</button>
                    <button type="button" class="btn btn-primary btn-sm edit_user" data-toggle="modal" data-target="#add_user_modal" data-id="'.$row->id.'"><i class="fa fa-edit"></i> Edit</button>
                    <button type="button" class="btn btn-danger btn-sm delete_user" data-toggle="modal" data-target="#delete_user_modal" data-id="'.$row->id.'"><i class="fa fa-archive"></i> Archive</button>';
        }, 'last')
        ->toJson();
    }

    public function archives_datatable(){
        $db = db_connect();
        $builder = $db->table('users')->select('id, CONCAT(first_name, " ", last_name) AS full_name, email ')->where('deleted',1);
        
        return DataTable::of($builder)
        ->add('action', function($row){
            return ' <button type="button" class="btn btn-danger btn-sm unarchive_user" data-id="'.$row->id.'"><i class="fa fa-archive"></i> Unarchive</button>'; }, 'last')
        ->toJson();
    }

    public function unarchive_user(){
        $db = db_connect();
        $id = $this->request->getVar('id');
        
        if($id){
            
            $result = $db->table('users')->where('id', $id)->update(['deleted' => 0]);
            
            if($result){
                echo true;
            } else {
                echo false;
            }
        }
    }

    public function get_user(){
        $db = db_connect();
        $id = $this->request->getVar('id');

        if($id){
            $result = $db->table('users u')->where('id', $id)->get()->getResult();

            echo ($result) ? json_encode($result[0]) : 0;
        }
    }

    public function delete_user(){
        $db = db_connect();
        $id = $this->request->getVar('id');
        
        if($id){
            
            $result = $db->table('users')->where('id', $id)->update(['deleted' => 1]);
            
            if($result){
                echo true;
            } else {
                echo false;
            }
        }
    }

    public function add_user(){
        $encrypter = \Config\Services::encrypter();
        $db = db_connect();

        $cipher = "AES-256-CBC";
        $secret = "DelsanVA";
        $option = 0;

        $iv = str_repeat("0", openssl_cipher_iv_length($cipher));

        $posted = $this->request->getVar();

        foreach ($posted as $key => $value) {
            if($key == 'password'){
                if($posted['password'] == ''){
                    unset($posted['password']);
                } else {
                    $data[$key] = openssl_encrypt($value, $cipher, $secret, $option, $iv);
                }
            }else{
                $data[$key] = $value;
            }
        }

        unset($data['/users/add_user']);

        $data['position_id'] = ($data['position_id']) ? $data['position_id'] : 3;
        $data['check_location'] = (!isset($data['check_location'])) ? 0 : $data['check_location'];
        $data['date_added'] = date('Y-m-d H:i:s');
        
        if(isset($data['id']) && $data['id'] != NULL){
            $result = $db->table('users')->where('id', $data['id'])->update($data);

            if($result){
                $alert = array(
                    'header' => 'Success!',
                    'message' => 'User has been updated!',
                    'type' => 'success'
                );
            }else{
                $alert = array(
                    'header' => 'Oops!',
                    'message' => 'Something went wrong...',
                    'type' => 'error'
                );
            }
        } else {
            unset($data['id']);
            $result = $db->table('users')->insert($data);

            if($result){
                $alert = array(
                    'header' => 'Success!',
                    'message' => 'User has been added!',
                    'type' => 'success'
                );
            }else{
                $alert = array(
                    'header' => 'Oops!',
                    'message' => 'Something went wrong...',
                    'type' => 'error'
                );
            }

        }

        echo json_encode($alert);
    }
}
