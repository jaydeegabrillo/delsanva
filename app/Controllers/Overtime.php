<?php

namespace App\Controllers;
use \Hermawan\DataTables\DataTable;
use Dompdf\Dompdf;

class Overtime extends BaseController
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
        $id = $this->session->get('user_id');
        $data['id'] = $id;
        $data['title'] = 'Leaves';
        $data['users'] = $this->db->table('users')->select('id, first_name, last_name')->where('deleted', 0)->get()->getResultArray();
        $data['user_info'] = $this->db->table('user_info')->where('user_id', $id)->get()->getRow();
        $data['pending_sick_leave_count'] = $this->db->table('leaves')->where('user_id', $id)->where('type', 'sick_leave')->where('status', 0)->like('date_from', date('Y'))->countAllResults();
        $data['pending_vacation_leave_count'] = $this->db->table('leaves')->where('user_id', $id)->where('type', 'vacation_leave')->where('status', 0)->like('date_from', date('Y'))->countAllResults();
        $data['approved_sick_leave'] = $this->db->table('leaves')->where('user_id', $id)->where('type', 'sick_leave')->where('status', 1)->like('date_from', date('Y'))->countAllResults();
        $data['approved_vacation_leave'] = $this->db->table('leaves')->where('user_id', $id)->where('type', 'vacation_leave')->where('status', 1)->like('date_from', date('Y'))->countAllResults();
        $data['filed_leaves'] = $this->db->table('leaves')->where('user_id', $id)->like('date_from', date('Y'))->get()->getResult();

        $script['js_scripts'] = array();
        $script['css_scripts'] = array();
        array_push($script['js_scripts'], '/pages/overtime/overtime.js');
        // array_push($script['css_scripts'], '/pages/leaves/leaves.css');

        $path = array(
            'pages/overtime/index',
            'pages/overtime/modal'
        );

        $this->load_view($data,$script,$path);
    }

    public function ot_requests(){

        $data['title'] = 'Leaves';
        $script['js_scripts'] = array();
        $script['css_scripts'] = array();
        array_push($script['js_scripts'], '/pages/leaves/leaves.js');
        array_push($script['css_scripts'], '/pages/leaves/leaves.css');
        $path = array(
            'pages/leaves/leave_requests'
        );

        $this->load_view($data,$script,$path);
    }

    public function apply_ot() {
        $id = $this->session->get('user_id');

        $request = $this->request->getVar();
        $request['date_created'] = date('Y-m-d H:i:s');
        unset($request['/overtime/apply_ot']);

        $ot = $this->db->table('overtime')->insert($request);

        if($ot){
            echo true;
        } else {
            echo false;
        }
    }

    public function update_leave_status() {
        $db = db_connect();
        $request = $this->request->getVar();

        $status = $db->table('leaves')->where('id', $request['id'])->update(['status' => $request['status']]);

        if($status){
            $result = [
                'response' => 1,
                'status' => $request['status']
            ];
        } else {
            $result = [
                'response' => 0
            ];
        }

        echo json_encode($result);
    }

    public function update_ot_status() {
        $db = db_connect();
        $request = $this->request->getVar();

        $status = $db->table('overtime')->where('id', $request['id'])->update(['status' => $request['status']]);

        if($status){
            $result = [
                'response' => 1,
                'status' => $request['status']
            ];
        } else {
            $result = [
                'response' => 0
            ];
        }

        echo json_encode($result);
    }

    public function overtime_requests_datatable(){
        $db = db_connect();

        $builder =  $db->table('overtime')
                    ->select('overtime.id, CONCAT(users.first_name, " ", users.last_name) as full_name, date, hours, time_start, time_end, status, reason')
                    ->join('users', 'users.id = overtime.user_id', 'left');

        $builder->orderBy('overtime.id', 'DESC');

        return DataTable::of($builder)
        ->edit('date', function($row) {
            return date('M d, Y', strtotime($row->date));
        })
        ->edit('time_start', function($row) {
            return date('H:i a', strtotime($row->time_start));
        })
        ->edit('time_end', function($row) {
            return date('H:i a', strtotime($row->time_end));
        })
        ->edit('status', function($row){
            if($row->status == 1){
                $status = "<span class='btn btn-success'>Approved</span>";
            } else if($row->status == 0) {
                $status = "<span class='btn btn-warning'>Pending</span>";
            } else {
                $status =  "<span class='btn btn-danger'>Declined</span>";
            }

            return $status;
        })
        ->add('action', function($row){
            if($row->status == 0){
                $actions = '<button class="approve btn btn-primary" id="review_filed_ot" data-id="'.$row->id.'">Review</button>';
            } else {
                $actions = '';
            }

            return $actions;
        }, 'last')
        ->toJson();
    }
}
