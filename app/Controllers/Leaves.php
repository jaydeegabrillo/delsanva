<?php

namespace App\Controllers;
use \Hermawan\DataTables\DataTable;
use Dompdf\Dompdf;

class Leaves extends BaseController
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
        array_push($script['js_scripts'], '/pages/leaves/leaves.js');
        array_push($script['css_scripts'], '/pages/leaves/leaves.css');

        $path = array(
            'pages/leaves/index',
            'pages/leaves/modal'
        );

        $this->load_view($data,$script,$path);
    }

    public function leave_requests(){

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

    public function apply_leave() {
        $id = $this->session->get('user_id');

        $request = $this->request->getVar();
        unset($request['/leaves/apply_leave']);

        $leave = $this->db->table('leaves')->insert($request);

        if($leave){
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

    public function leave_requests_datatable(){
        $db = db_connect();

        $builder =  $db->table('leaves')
                    ->select('leaves.id, CONCAT(users.first_name, " ", users.last_name) as full_name, type, date_from, date_to, status, reason')
                    ->join('users', 'users.id = leaves.user_id', 'left');

        $leave_type = $this->request->getVar("leave_type");
        $leave_status = $this->request->getVar("leave_status");

        if($leave_type){
            $builder->where("leaves.type", $leave_type);
        }

        if($leave_status != ''){
            $builder->where("leaves.status", $leave_status);
        }

        $builder->orderBy('leaves.id', 'DESC');

        return DataTable::of($builder)
        ->edit('date_from', function($row) {
            return date('M d, Y', strtotime($row->date_from));
        })
        ->edit('date_to', function($row) {
            return date('M d, Y', strtotime($row->date_to));
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
                $actions = '<button class="approve btn btn-primary" id="review_filed_leave" data-id="'.$row->id.'">Review</button>';
            } else {
                $actions = '';
            }

            return $actions;
        }, 'last')
        ->toJson();
    }
}
