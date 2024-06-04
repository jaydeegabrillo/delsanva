<?php

namespace App\Controllers;

use \Hermawan\DataTables\DataTable;
use Dompdf\Dompdf;

class Payslip extends BaseController
{
    private $_user;
    protected $session;
    protected $db;

    function __construct()
    {
        $this->session = \Config\Services::session();
        // $this->payslip_model = new \App\Models\PayslipModel;
        $this->_user = $this->session->get();
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $data['id'] = $this->session->get('user_id');
        $data['title'] = 'Payslip';

        $script['js_scripts'] = array();
        array_push($script['js_scripts'], '/pages/payslip/payslip.js');
        array_push($script['css_scripts'], '/pages/payslip/payslip.css');

        $path = [ 'pages/payslip/index', ];

        $this->load_view($data, $script, $path);
    }
}
