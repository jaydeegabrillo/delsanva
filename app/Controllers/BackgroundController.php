<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class BackgroundController extends BaseController
{
    public function update_payroll()
    {
        $db = db_connect();
        $date_now = date('d');
        $data = [];
        $users = $db->table('users')->join('user_info', 'user_info.user_id = users.id')->where(['deleted' => 0])->get()->getResultArray();

        if ($date_now == 5) {
            // Get the date range from the 16th to the last day of the previous month
            $previous_month = date('Y-m-d', strtotime('first day of last month'));
            $last_day_previous_month = date('Y-m-t', strtotime('last day of last month'));
            $date = date('Y-m-5');
            $start_date = date('Y-m-16', strtotime('first day of last month'));
            $end_date = $last_day_previous_month;
        } else if ($date_now == 20) {
            // Get the date range from the 1st to the 15th of the current month
            $date = date('Y-m-20');
            $start_date = date('Y-m-01');
            $end_date = date('Y-m-15');
        }

        if (isset($start_date) && isset($end_date)) {
            foreach ($users as $user) {
                // Check if payslip already exists
                $exists = $db->table('payslips')->where(['user_id' => $user['user_id'], 'payroll_date' => date('Y-m-d')])->get()->getRow();

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
}
