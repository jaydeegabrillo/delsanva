<?php

namespace App\Controllers;

use \Hermawan\DataTables\DataTable;
use Dompdf\Dompdf;

class Payslip extends BaseController
{
    private $_user;
    protected $session;
    protected $db;
    protected $holidays;

    function __construct()
    {
        $this->session = \Config\Services::session();
        // $this->payslip_model = new \App\Models\PayslipModel;
        $this->_user = $this->session->get();
        $this->db = \Config\Database::connect();
        $this->holidays = [
            '01-01', // New Year's Day
            '04-09', // Araw ng Kagitingan
            '05-01', // Labor Day
            '06-12', // Independence Day
            '08-26', // National Heroes Day
            '11-01', // All Saints' Day
            '11-30', // Bonifacio Day
            '12-25', // Christmas Day
            '12-30', // Rizal Day
        ];
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

    public function get_payslip_data($id){
        helper('url');

        $db = db_connect();
        $payslip_details = $db->table('payslips')->where('id', $id)->get()->getFirstRow();
        $date_start = date("Y-m-d", strtotime($payslip_details->period_from));
        $date_end = date("Y-m-d", strtotime($payslip_details->period_to));
        $absences = $this->checkWeeks($date_start, $date_end, $id);
        $holidays = $this->checkHolidays($date_start, $date_end);
        $user_details = $db->table('user_info')->where(['user_id' => $payslip_details->user_id])->get()->getRow();
        $payroll_period = $db->table('attendance')->join('user_info', 'user_info.user_id = attendance.user_id')->join('overtime', 'overtime.date = attendance.date', 'left')->where(['attendance.user_id' => $id, 'attendance.date >=' => $date_start, 'attendance.date <=' => $date_end])->get()->getResult();

        $data['holiday_hours'] = $holidays;
        $data['vacation_hours'] = 0;
        $data['sick_hours'] = 0;

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
        $data['holidays'] = $holidays * $hourly_rate;
        $data['unpaid_leaves'] = $absences * $hourly_rate;
        $data['vacation_leaves'] = $data['vacation_hours'] * $hourly_rate;
        $data['sick_leaves'] = $data['sick_hours'] * $hourly_rate;
        $data['total_earnings'] = $earnings + $data['vacation_leaves'] + $data['sick_leaves'] + $data['holidays'];
        $data['net_pay'] = $data['total_earnings'] - $deductions;
        $data['unpaid_leave_hours'] = $absences;
        $data['total_deductions'] = $deductions;
        $data['late'] = 0;
        $data['undertime'] = 0;

        foreach ($payroll_period as $payroll_detail) {
            $time_start = $payroll_detail->time_start;
            $time_end = $payroll_detail->time_end;

            $start_shift = strtotime(date("H:i:s", strtotime($payroll_detail->clock_in)));
            $end_shift = strtotime(date("H:i:s", strtotime($payroll_detail->clock_out)));

            $late_time = strtotime('09:00:00');
            if ($start_shift > $late_time) {
                $data['late'] += round(($start_shift - $late_time) / 60);
            }

            $time_out = strtotime('18:00:00');


            if($end_shift < $time_out){
                $data['undertime'] += round(($time_out - $end_shift) / 60);
            }

            if($time_start == '' && $time_end == ''){
                $data['hours'] += 8;
            } else {

                $start_ot = strtotime($time_start);
                $end_ot = strtotime($time_end);
                $data['hours'] += round(abs($end_ot - $start_ot) / 3600,2) + 8;
            }
        }

        $late_hours = $data['late'] / 60;
        $undertime = $data['undertime'] / 60;

        // Convert to hours and minutes
        $late_hours = floor($data['late'] / 60);
        $late_remaining_minutes = $data['late'] % 60;

        $undertime_hours = floor($data['undertime'] / 60);
        $undertime_remaining_minutes = $data['undertime'] % 60;

        $data['late'] = sprintf("%d:%02d", $late_hours, $late_remaining_minutes);
        $data['undertime'] = sprintf("%d:%02d", $undertime_hours, $undertime_remaining_minutes);

        // Calculate late deductions
        $late_deductions = ($late_hours * $hourly_rate) + (($late_remaining_minutes / 60) * $hourly_rate);

        // Calculate undertime deductions
        $undertime_deductions = $undertime_hours * $hourly_rate;
        $undertime_deductions = ($undertime_hours * $hourly_rate) + (($undertime_remaining_minutes / 60) * $hourly_rate);
        // Add late deductions to total deductions
        $data['late_deductions'] = $late_deductions;
        $data['undertime_deductions'] = $undertime_deductions;
        $data['total_deductions'] += $late_deductions + $undertime_deductions;

        $data['id'] = $id;
        $data['title'] = 'Payslip Details';
        $data['user_details'] = $user_details;
        $data['payroll_details'] = $payroll_period;

        return $data;
    }

    public function payslip_details($id){
        $data = $this->get_payslip_data($id);

        $script['js_scripts'] = array();
        $script['css_scripts'] = array();
        array_push($script['js_scripts'], '/pages/payslip/payslip.js');
        array_push($script['css_scripts'], '/pages/payslip/payslip.css');

        $path = [ 'pages/payslip/details', ];

        $this->load_view($data, $script, $path);
    }

    public function checkHolidays($start_date, $end_date){
        $holiday_ctr = 0;
        while ($start_date <= $end_date) {
            $formatted_date = date('m-d', strtotime($start_date));

            if (in_array($formatted_date, $this->holidays)) {
                // Assuming 8 hours per holiday
                $holiday_ctr += 8;
            }

            // Move to the next day
            $start_date = date('Y-m-d', strtotime('+1 day', strtotime($start_date)));
        }

        return $holiday_ctr;
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

        $data = $this->get_payslip_data($id);
        $data['image'] = $base64;

        $dompdf = new Dompdf();
        $dompdf->loadHtml(view('\App\Views\pages\payslip\details_pdf', $data));
        // $dompdf->set_option("enable_remote", true);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream("payslip_report", array("Attachment" => 0));
        exit(0);
    }

    public function release_payslips(){
        $db = db_connect();
        $date_now = date('d');
        $data = [];
        // $users = $db->table('users')->join('user_info', 'user_info.user_id = users.id')->where(['deleted' => 0])->get()->getResultArray();
        $users = $db->table('users')->where(['deleted' => 0])->get()->getResultArray();

        if ($date_now >= 5 && $date_now < 20) {
            // Get the date range from the 16th to the last day of the previous month
            $previous_month = date('Y-m-d', strtotime('first day of last month'));
            $last_day_previous_month = date('Y-m-t', strtotime('last day of last month'));
            $date = date('Y-m-05');
            $start_date = date('Y-m-16', strtotime('first day of last month'));
            $end_date = $last_day_previous_month;
        } else {
            // Get the date range from the 1st to the 15th of the current month
            $date = date('Y-m-20');
            $start_date = date('Y-m-01');
            $end_date = date('Y-m-15');
        }

        if (isset($start_date) && isset($end_date)) {
            foreach ($users as $user) {
                // Check if payslip already exists
                $exists = $db->table('payslips')->where(['user_id' => $user['user_id'], 'payroll_date' => $date])->get()->getRow();

                if(!$exists){
                    $data = [
                        'payroll_date' => $date,
                        'period_from' => $start_date,
                        'period_to' => $end_date,
                        'gross' => '',
                        'net' => '',
                        'user_id' => $user['user_id']
                    ];

                    $payslip = $db->table('payslips')->insert($data);
                }

            }
        }
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
