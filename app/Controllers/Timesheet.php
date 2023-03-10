<?php

namespace App\Controllers;
use \Hermawan\DataTables\DataTable;
use Dompdf\Dompdf;

class Timesheet extends BaseController
{
    private $timesheet_model;
    private $_user;
    protected $session;

    function __construct(){
        $this->session = \Config\Services::session();
        $this->timesheet_model = new \App\Models\TimesheetModel;
        $this->_user = $this->session->get();
    }

    public function index()
    {
        $data['title'] = 'Timesheet';
        $script['js_scripts'] = array();
        array_push($script['js_scripts'], '/pages/timesheet/timesheet.js');
        array_push($script['js_scripts'], '/pages/timesheet/timesheet.js');

        $path = array(
            'pages/timesheet/index',
            'pages/timesheet/modal',
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

    public function timesheet_pdf(){
        $posted = $this->request->getVar();
        $date_from  = $posted['date_from'];
        $date_to = $posted['date_to'];

        $id = $this->session->get('user_id');
        $timesheet = $this->timesheet_model->timesheet_pdf($id,$posted)->get()->getResult();
        $ctr = 0;
        $data['timesheet'][$ctr] = array();

        foreach ($timesheet as $key => $value) {
            $data['timesheet'][$ctr] = array();
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
