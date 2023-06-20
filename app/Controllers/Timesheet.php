<?php

namespace App\Controllers;
use \Hermawan\DataTables\DataTable;
use Dompdf\Dompdf;

class Timesheet extends BaseController
{
    private $timesheet_model;
    private $_user;
    protected $session;
    protected $db;

    function __construct(){
        $this->session = \Config\Services::session();
        $this->timesheet_model = new \App\Models\TimesheetModel;
        $this->_user = $this->session->get();
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $data['title'] = 'Timesheet';
        $data['users'] = $this->db->table('users')->select('id, first_name, last_name')->where('deleted', 0)->get()->getResultArray();
        
        $script['js_scripts'] = array();
        array_push($script['js_scripts'], '/pages/timesheet/timesheet.js');

        $path = array(
            'pages/timesheet/index',
            'pages/timesheet/modal',
        );

        $this->load_view($data,$script,$path);
    }

    public function add_timesheet(){
        $data = $this->request->getVar();
        
        $data['date'] = date('Y-m-d', strtotime($data['clock_in']));
        unset($data['/timesheet/add_timesheet']);

        $insert = $this->db->table('attendance')->insert($data);

        if($insert){
            $alert = array(
                'header' => 'Success!',
                'message' => 'Timesheet has been added',
                'type' => 'success'
            );
        } else {
            $alert = array(
                'header' => 'Oops!',
                'message' => 'Something went wrong...',
                'type' => 'error'
            );
        }

        echo json_encode($alert);
    }

    public function update_log(){
        $id = $this->request->getVar('id');
        $in = $this->request->getVar('clock_in');
        $out = $this->request->getVar('clock_out');

        $data = array(
            'clock_in' => ($in) ? date('c', strtotime($in)) : '0000-00-00 00:00:00',
            'clock_out' => ($out) ? date('c', strtotime($out)) : '0000-00-00 00:00:00',
        );

        $update = $this->db->table('attendance')->where('id', $id)->update($data);

        if($update){
            echo json_encode(array('success' => true));
        }else{
            echo json_encode(array('success' => false));
        }
    }

    public function timesheet_datatable(){
        $db = db_connect();
        $builder = $db->table('attendance a');
        $builder->select("a.id, CONCAT(u.first_name, ' ', u.last_name) as full_name, clock_in, clock_out, date");
        $builder->join('users u', 'a.user_id = u.id');
        if($this->session->get('position_id') > 1){
            $builder->where('user_id', $this->session->get('user_id'));
        }
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
            if($this->session->get('position_id') == 1){
                $in = ($row->clock_in == '') ? '' : date("H:i", strtotime($row->clock_in));
                $out = ($row->clock_out == '') ? '' : date("H:i", strtotime($row->clock_out));

                return '<button type="button" class="btn btn-warning btn-sm edit_attendance" data-toggle="modal" data-target="#edit_attendance" data-in="'.$in.'" data-out="'.$out.'" data-id="'.$row->id.'"><i class="fa fa-edit"></i> Edit</button>';
            } else {
                return '';
            }
        }, 'last')
        ->toJson();
    }

    public function timesheet_pdf(){
        $posted = $this->request->getVar();
        $date_from  = $posted['date_from'];
        $date_to = $posted['date_to'];

        $id = $this->session->get('user_id');
        $timesheet = $this->timesheet_model->timesheet_pdf($id,$posted)->get()->getResult();

        $ctr = 0;
        $data['timesheet'][$ctr] = array();

        foreach ($timesheet as $key => $value) {
            array_push($data['timesheet'][$ctr], $value);
        }

        $dompdf = new Dompdf();
        $dompdf->loadHtml(view('\App\Views\pages\timesheet\timesheet_pdf', $data));
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream("timesheet_report", array("Attachment" => 0));
        exit(0);
    }
}
