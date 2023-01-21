<?php

namespace App\Controllers;

class Timesheet extends BaseController
{
    public function index()
    {
        $data['title'] = 'Timesheet';
        $script = array();
        $path = 'pages/timesheet/index';
        
        $this->load_view($data,$script,$path);
    }
}
