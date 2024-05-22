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
}
