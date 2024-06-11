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
        $date_start = '2024-05-20 00:00:00';
        $date_end = '2024-06-05 00:00:00';
        $user_details = $db->table('user_info')->where(['user_id' => $id])->get()->getRow();
        $payroll_period = $db->table('attendance')->join('user_info', 'user_info.user_id = attendance.user_id')->join('overtime', 'overtime.date = attendance.date', 'left')->where(['attendance.user_id' => $id, 'attendance.date >=' => $date_start, 'attendance.date <=' => $date_end])->groupBy('attendance.date')->orderBy('attendance.date ASC')->get()->getResult();

        $data['vacation_leaves'] = 0;
        $data['sick_leaves'] = 0;
        $data['unpaid_leaves'] = 0;
        $data['holidays'] = 0;

        $leaves = $db->table('leaves')->where(['user_id' => $id, 'date_from >=', $date_start, 'date_to <=', $date_end, 'status' => 1])->get()->getResult();
        
        foreach ($leaves as $leave) {
        
        }
        
        
        // Earnings and Deductions
        $earnings = $user_details->salary/2;
        $deductions = ($user_details->tax / 2) + ($user_details->sss / 2) + ($user_details->philhealth / 2) + ($user_details->{'pag-ibig'} / 2);

        $data['hours'] = 0;
        $data['total_deductions'] = $deductions;
        $data['total_earnings'] = $earnings;
        $data['net_pay'] = $earnings - $deductions;
        
        foreach ($payroll_period as $payroll_detail) {
            $time_start = $payroll_detail->time_start;
            $time_end = $payroll_detail->time_end;

            if($time_start == '' && $time_end == ''){
                $data['hours'] += 8;
            } else {
                $start_ot = strtotime($time_start);
                $end_ot = strtotime($time_end);
                $data['hours'] += round(abs($end_ot - $start_ot) / 3600,2) + 8;
            }
        }

        $data['id'] = $id;
        $data['title'] = 'Payslip Details';
        $data['user_details'] = $user_details;
        $data['payroll_details'] = $payroll_period;

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
