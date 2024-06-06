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
        $script['css_scripts'] = array();
        array_push($script['js_scripts'], '/pages/payslip/payslip.js');
        array_push($script['css_scripts'], '/pages/payslip/payslip.css');

        $path = [ 'pages/payslip/index', ];

        $this->load_view($data, $script, $path);
    }

    public function payslip_details(){
        helper('url');

        $id = $this->request->uri->getSegment(3);
        $db = db_connect();
        $date_start = '2024-03-05';
        $date_end = '2024-03-20';
        $user_details = $db->table('user_info')->where(['user_id' => $id])->get()->getRow();
        $payroll_period = $db->table('attendance')->where(['user_id' => $id, 'date >=' => $date_start, 'date <=' => $date_end])->get()->getResult();

        echo "<pre>";
        print_r($payroll_period);
        echo "</pre>";
        exit;

        $data['id'] = $id;
        $data['title'] = 'Payslip Details';

        $script['js_scripts'] = array();
        $script['css_scripts'] = array();
        array_push($script['js_scripts'], '/pages/payslip/payslip.js');
        array_push($script['css_scripts'], '/pages/payslip/payslip.css');

        $path = [ 'pages/payslip/details', ];

        $this->load_view($data, $script, $path);
    }

    public function pasylips_datatable(){
        $db = db_connect();

        $builder =  $db->table('payslips')
                    ->select('id, payroll_date, period_from, period_to, net, gross');

        return DataTable::of($builder)
        ->edit('payroll_date', function($row){
            return date('M d, Y', strtotime($row->payroll_date));
        })
        ->edit('period_from', function($row){
            return date('M d, Y', strtotime($row->period_from));
        })
        ->edit('period_to', function($row){
            return date('M d, Y', strtotime($row->period_to));
        })
        ->edit('net', function($row){
            return "₱ ".number_format($row->net);
        })
        ->edit('gross', function($row){
            return "₱ ".number_format($row->gross);
        })
        ->add('action', function($row){
            return '<a href="'.base_url("/payslip/payslip-details/".$row->id).'"><button class="btn btn-primary"><i class="fa fa-eye"></i> View</button></a>';
        }, 'last')
        ->toJson();
    }
}
