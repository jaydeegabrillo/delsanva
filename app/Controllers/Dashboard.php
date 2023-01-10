<?php

namespace App\Controllers;

class Dashboard extends BaseController
{
    public function index()
    {
        $data['title'] = 'Dashboard';
        
        echo view('layout/header');
        echo view('layout/sidebar', $data);
        echo view('pages/dashboard/index');
        echo view('layout/footer');
    }
}
