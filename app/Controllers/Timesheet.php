<?php

namespace App\Controllers;
use \Hermawan\DataTables\DataTable;

class Timesheet extends BaseController
{
    public function index()
    {
        $data['title'] = 'Timesheet';
        $script['js_scripts'] = array();
        array_push($script['js_scripts'], '/pages/timesheet/timesheet.js');

        $path = array(
            'index' => 'pages/timesheet/index',
        );

        $this->load_view($data,$script,$path);
    }

    public function timesheet_datatable(){
        $db = db_connect();
        $builder = $db->table('attendance a');
        $builder->select("a.id, CONCAT(u.first_name, ' ', u.last_name) as full_name, clock_in, clock_out, date");
        $builder->join('users u', 'a.user_id = u.id');
        $builder->orderBy('date', 'desc');

        return DataTable::of($builder)
        ->edit('clock_in', function($row){
            return date('H:i A', strtotime($row->clock_in));
        })
        ->edit('clock_out', function($row){
            return date('H:i A', strtotime($row->clock_out));
        })
        ->edit('date', function($row){
            return date('M d, Y', strtotime($row->date));
        })
        ->add('action', function($row){
            return '';
        })
        ->toJson();
    }
}
