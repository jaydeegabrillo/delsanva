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
        $path = 'pages/timesheet/index';
        
        $this->load_view($data,$script,$path);
    }

    public function update_log(){
        $id = $this->request->getVar('id');
        $in = $this->request->getVar('in');
        $out = $this->request->getVar('out');

        $data['set'] = array(
            'clock_in' => $in,
            'clock_out' => $out,
        );

        $data['where'] = array( 'id' => $id );

        $update = $this->db->update('')

        if($update){
            echo json_encode(array('success' => true));
        }else{
            echo json_encode(array('success' => false));
        }
    }

    public function timesheet_datatable(){
        $db = db_connect();
        $builder = $db->table('attendance a');
        $builder->select("a.id, CONCAT(u.first_name, ' ', u.last_name) as full_name, clock_in, clock_out");
        $builder->join('users u', 'a.user_id = u.id');

        return DataTable::of($builder)
        ->edit('clock_in', function($row){
            return date('H:i A', strtotime($row->clock_in));
        })
        ->edit('clock_out', function($row){
            return date('H:i A', strtotime($row->clock_out));
        })
        ->add('action', function($row){
            $in = ($row->clock_in == '') ? '' : date("H:i", strtotime($row->clock_in));
            $out = ($row->clock_out == '') ? '' : date("H:i", strtotime($row->clock_out));

            return '<button type="button" class="btn btn-warning btn-sm edit_attendance" data-toggle="modal" data-target="#edit_attendance" data-in="'.$in.'" data-out="'.$out.'" data-id="'.$row->id.'"><i class="fa fa-edit"></i> Edit</button>';
        }, 'last')
        ->toJson();
    }
}
