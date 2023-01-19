<?php

namespace App\Controllers;

class Dashboard extends BaseController
{
    protected $session;
    private $dashboardModel;

    function __construct(){
        $this->session = \Config\Services::session();
        $this->dashboardModel = new \App\Models\DashboardModel;
    }

    public function index()
    {
        $data['title'] = 'Dashboard';
        $data['name'] = $this->session->get('name');
        $data['clock_status'] = $this->dashboardModel->get_clock_status($this->session->get('user_id'));
        $script['js_scripts'] = array();
        array_push($script['js_scripts'], '/pages/dashboard/dashboard.js');
        
        echo view('layout/header');
        echo view('layout/sidebar', $data);
        echo view('pages/dashboard/index');
        echo view('layout/footer', $script);
    }

    public function log(){

        $check_status = $this->dashboardModel->get_clock_status($this->session->get('user_id'));
        
        $data = array(
            'user_id' => $this->session->get('user_id'),
            'date' => date('Y-m-d'),
            'deleted' => 0,
        );

        if(!$check_status){
            $data['clock_in'] = date('Y-m-d h:i:s');
        }

        $result = $this->dashboardModel->log($data);
        
        if($result){
            echo true;
        }else{
            echo false;
        }
    }
}
