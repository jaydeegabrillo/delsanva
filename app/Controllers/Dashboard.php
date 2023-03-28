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
        $id = $this->session->get('user_id');

        $data = array(
            'title' => 'Dashboard',
            'clock_status' => $this->dashboardModel->get_clock_status($id),
            'missing_logs' => $this->dashboardModel->get_attendance($id),
        );

        $script['js_scripts'] = array();
        $script['css_scripts'] = array();
        $path = array(
            'pages/dashboard/index'
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
            'clock_in' => date('Y-m-d h:i:s'),
            'deleted' => 0,
        );

        $result = $this->dashboardModel->log($data);

        if($result){
            echo true;
        }else{
            echo false;
        }
    }
}
