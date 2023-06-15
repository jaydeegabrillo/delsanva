<?php

namespace App\Controllers;

class Dashboard extends BaseController
{
    protected $db;
    protected $session;
    private $dashboardModel;

    function __construct(){
        $this->session = \Config\Services::session();
        $this->dashboardModel = new \App\Models\DashboardModel;
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $id = $this->session->get('user_id');

        $data = array(
            'id' => $id,
            'title' => 'Dashboard',
            'clock_status' => $this->dashboardModel->get_clock_status($id),
            'missing_logs' => $this->dashboardModel->get_attendance($id),
            'check_location' => $this->session->get('check_location')
        );

        $script['js_scripts'] = array();
        $script['css_scripts'] = array();
        $path = array(
            'pages/dashboard/index',
            'pages/dashboard/modal'
        );

        array_push($script['js_scripts'], '/pages/dashboard/dashboard.js');
        array_push($script['css_scripts'], '/pages/dashboard/dashboard.css');

        $this->load_view($data, $script, $path);
    }

    public function log(){
        
        $check_status = $this->dashboardModel->get_clock_status($this->session->get('user_id'));
        
        $data = array(
            'id' => $check_status['id'],
            'user_id' => $this->session->get('user_id'),
            'date' => date('Y-m-d'),
            'clock_in' => date('c'),
            'deleted' => 0,
        );

        $user_details = $this->db->table('users')->where('id', $this->session->get('user_id'))->get()->getRow();
        
        if($user_details->check_location == 0){
            $status = 'OK';
        } else {
            $api_key = 'AIzaSyCqJUd2VUK0J7Hy0uyVsx7uAyyDS2PhtfU';
            $full_address = ($user_details->country != 'philippines') ? $user_details->address . ' ' . $user_details->apt . ' ' . $user_details->state. ' ' . $user_details->city . ' ' . $user_details->zip : $user_details->address;
            $address = str_replace(" ", "+",$full_address);
    
            $google_verify = json_decode(file_get_contents("https://maps.google.com/maps/api/geocode/json?key=".$api_key."&address=".$address));
    
            if($google_verify->status == 'REQUEST_DENIED') {
                $status = 'Denied';
            } else if ($google_verify->status == 'OK') {
                $addresses = $google_verify->results[0]->address_components;
                $validated = false;

                if($user_details->country != 'philippines'){
                    $validate_zipcode = (preg_match('/^[0-9]{5}(-[0-9]{4})?$/', $user_details->zip)) ? true : false;
                    
                    for($c=0;$c<count($addresses);$c++){
                        if(in_array("street_number", $addresses[$c]->types) && ($this->str_contains($address, $addresses[$c]->long_name) || $this->str_contains($address, $addresses[$c]->short_name)) ){
                            $validated = true;
                        }
                    }

                    $success = (count($google_verify->results) > 0 && $validated && $validate_zipcode && !isset($google_verify->results[0]->partial_match)) ? true : false ;
                } else {
                    $success = (count($google_verify->results) > 0) ? true : false ;
                }
    
                if($success){
                    $coordinates = $google_verify->results[0]->geometry->location;
                    $lat = $coordinates->lat;
                    $long = $coordinates->lng;
                    $lat2 = $this->request->getVar('lat');
                    $long2 = $this->request->getVar('lon');
                    
                    $distance = $this->distance($lat, $long, $lat2, $long2, 'M');
    
                    if($distance <= 1){
                        $status = 'OK';
                    } else {
                        $status = $distance;
                    }
                }else{
                    $status = "Invalid";
                }
            } else {
                $status = 'Invalid';
            }
        }
        
        if($status == 'OK'){
            $result = $this->dashboardModel->log($data);

            if($result){
                echo true;
            }else{
                echo false;
            }
        } else {
            echo $status;
        }

    }

    public function update_password(){
        $encrypter = \Config\Services::encrypter();

        $cipher = "AES-256-CBC";
        $secret = "DelsanVA";
        $option = 0;

        $iv = str_repeat("0", openssl_cipher_iv_length($cipher));

        $posted = $this->request->getVar();

        $password = openssl_encrypt($posted['password'], $cipher, $secret, $option, $iv);

        $result = $this->db->table('users')->where('id', $posted['id'])->update(['password' => $password]);

        if($result){
            $alert = array(
                'header' => 'Success!',
                'message' => 'Password has been updated!',
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

    function str_contains( $haystack, $needle)
    {
        return $needle !== '' && mb_strpos($haystack, $needle) !== false;
    }

    function distance($lat1, $lon1, $lat2, $lon2, $unit) {
        if (($lat1 == $lat2) && ($lon1 == $lon2)) {
            return 0;
        } else {
            $theta = $lon1 - $lon2;
            $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
            $dist = acos($dist);
            $dist = rad2deg($dist);
            $miles = $dist * 60 * 1.1515;
            $unit = strtoupper($unit);

            if ($unit == "K") {
            return ($miles * 1.609344);
            } else if ($unit == "N") {
            return ($miles * 0.8684);
            } else {
            return $miles;
            }
        }
    }
}