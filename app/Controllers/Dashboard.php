<?php

namespace App\Controllers;

class Dashboard extends BaseController
{
    protected $db;
    protected $session;
    private $dashboardModel;

    function __construct(){
        $this->session = \Config\Services::session();
        $this->dashboardModel = new \App\Models\DashboardModel;
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $id = $this->session->get('user_id');

        $data = array(
            'id' => $id,
            'title' => 'Dashboard',
            'clock_status' => $this->dashboardModel->get_clock_status($id),
            'missing_logs' => $this->dashboardModel->get_attendance($id),
        );

        $script['js_scripts'] = array();
        $script['css_scripts'] = array();
        $path = array(
            'pages/dashboard/index',
            'pages/dashboard/modal'
        );

        array_push($script['js_scripts'], '/pages/dashboard/dashboard.js');
        array_push($script['css_scripts'], '/pages/dashboard/dashboard.css');

        $this->load_view($data, $script, $path);
    }

    public function log(){

        $check_status = $this->dashboardModel->get_clock_status($this->session->get('user_id'));

        $data = array(
            'id' => $check_status['id'],
            'user_id' => $this->session->get('user_id'),
            'date' => date('Y-m-d'),
            'clock_in' => date('c'),
            'deleted' => 0,
        );

        $result = $this->dashboardModel->log($data);

        if($result){
            echo true;
        }else{
            echo false;
        }
    }

    public function update_password(){
        $encrypter = \Config\Services::encrypter();

        $cipher = "AES-256-CBC";
        $secret = "DelsanVA";
        $option = 0;

        $iv = str_repeat("0", openssl_cipher_iv_length($cipher));

        $posted = $this->request->getVar();

        $password = openssl_encrypt($posted['password'], $cipher, $secret, $option, $iv);

        $result = $this->db->table('users')->where('id', $posted['id'])->update(['password' => $password]);

        if($result){
            $alert = array(
                'header' => 'Success!',
                'message' => 'Password has been updated!',
                'type' => 'success'
            );
        } else {
            $alert = array(
                'header' => 'Oops!',
                'message' => 'Something went wrong...',
                'type' => 'error'
            );
        }

        echo json_encode($alert);
    }
}
