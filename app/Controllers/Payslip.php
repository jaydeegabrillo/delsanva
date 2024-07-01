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

    public function get_payslip_data($id, $date){
        helper('url');

        // $id = $this->request->uri->getSegment(3);
        
        $db = db_connect();
        $payslip_details = $db->table('payslips')->where(['payroll_date' => $date, 'user_id' => $id])->get()->getFirstRow();
        
        $date_start = $payslip_details->period_from;
        $date_end = $payslip_details->period_to;

        $absences = $this->checkWeeks($date_start, $date_end, $id);
        $user_details = $db->table('user_info')->where(['user_id' => $id])->get()->getRow();
        $payroll_period = $db->table('attendance')->join('user_info', 'user_info.user_id = attendance.user_id')->join('overtime', 'overtime.date = attendance.date', 'left')->where(['attendance.user_id' => $id, 'attendance.date >=' => $date_start, 'attendance.date <=' => $date_end])->get()->getResult();

        $data['vacation_hours'] = 0;
        $data['sick_hours'] = 0;
        $data['holidays'] = 0;

        $leaves = $db->table('leaves')->where(['user_id' => $id, 'date_from >=' => $date_start, 'date_to <=' => $date_end, 'status' => 1])->get()->getResult();

        foreach ($leaves as $leave) {
            $date_from = $leave->date_from;
            $date_to = $leave->date_to;
            $diff = strtotime($date_from) - strtotime($date_to);
            $days = abs(round($diff / 86400));
            $hours_rendered = ($days + 1) * 8;

            if($leave->type == 'sick_leave') {
                $data['sick_hours'] += $hours_rendered;
            } else {
                $data['vacation_hours'] += $hours_rendered;
            }
        }

        // Earnings and Deductions
        $earnings = $user_details->salary/2;
        $hourly_rate = $earnings / 80;
        $deductions = ($user_details->tax / 2) + ($user_details->sss / 2) + ($user_details->philhealth / 2) + ($user_details->{'pag-ibig'} / 2) + ($absences * $hourly_rate);

        $data['hours'] = 0;
        $data['unpaid_leaves'] = $absences * $hourly_rate;
        $data['vacation_leaves'] = $data['vacation_hours'] * $hourly_rate;
        $data['sick_leaves'] = $data['sick_hours'] * $hourly_rate;
        $data['total_earnings'] = $earnings + $data['vacation_leaves'] + $data['sick_leaves'];
        $data['net_pay'] = $data['total_earnings'] - $deductions;
        $data['unpaid_leave_hours'] = $absences;
        $data['holiday_hours'] = 0;
        $data['total_deductions'] = $deductions;
        $data['late'] = 0;

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

        return $data;
    }

    public function payslip_details($id, $date){
        $data = $this->get_payslip_data($id, $date);

        $script['js_scripts'] = array();
        $script['css_scripts'] = array();
        array_push($script['js_scripts'], '/pages/payslip/payslip.js');
        array_push($script['css_scripts'], '/pages/payslip/payslip.css');

        $path = [ 'pages/payslip/details', ];

        $this->load_view($data, $script, $path);
    }

    public function checkWeeks($start_date, $end_date, $id){
        $db = db_connect();
        $date_from = strtotime($start_date);
        $date_to = strtotime($end_date);

        if ($date_from > $date_to) {
            $temp = $date_from;
            $date_from = $date_to;
            $date_to = $temp;
        }

        // Loop through each day between the two dates
        $current_timestamp = $date_from;
        $absents = 0;

        while ($current_timestamp <= $date_to) {
            // Get the day of the week for the current timestamp
            $day_of_week = date('l', $current_timestamp);

            if($day_of_week !== 'Saturday' && $day_of_week !== 'Sunday'){
                // $absents[] = $day_of_week;
                $has_attendance = $db->table('attendance')->where(['date' => date("Y-m-d", $current_timestamp), 'user_id' => $id])->get()->getRow();

                if(!$has_attendance){
                    $absents += 8;
                }
            }

            // Move to the next day
            $current_timestamp = strtotime('+1 day', $current_timestamp);
        }

        return $absents;
    }

    public function payslip_pdf($id){
        $db = db_connect();
        $imagePath = base_url().'assets/images/delsanva.jpg';
        $type = pathinfo($imagePath, PATHINFO_EXTENSION);
        $data = file_get_contents($imagePath);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        
        $payslip_detail = $db->table('payslips')->where('id', $id)->get()->getFirstRow();
        
        $data = $this->get_payslip_data($id, $payslip_detail->payroll_date);
        $data['image'] = $base64;

        $dompdf = new Dompdf();
        $dompdf->loadHtml(view('\App\Views\pages\payslip\details_pdf', $data));
        // $dompdf->set_option("enable_remote", true);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream("payslip_report", array("Attachment" => 0));
        exit(0);
    }

    public function payslips_datatable(){
        $db = db_connect();

        $builder =  $db->table('payslips p')
                    ->select('p.id, CONCAT(u.first_name, " ", u.last_name) as full_name, payroll_date, period_from, period_to')
                    ->join('users u', 'u.id = p.user_id');

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
        ->add('action', function($row){
            $payroll_date = date('Y-m-d', strtotime($row->payroll_date));
            return '<a href="'.base_url("/payslip/payslip-details/".$row->id."/".$payroll_date).'"><button class="btn btn-primary"><i class="fa fa-eye"></i> View</button></a>';
        }, 'last')
        ->toJson();
    }
}
