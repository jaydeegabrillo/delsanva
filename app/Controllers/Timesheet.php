<?php

namespace App\Controllers;

class Timesheet extends BaseController
{
    public function index()
    {
        $data['title'] = 'Timesheet';
        echo view('layout/header');
        echo view('layout/sidebar', $data);
        echo view('pages/timesheet/index');
        echo view('layout/footer');
    }
}
