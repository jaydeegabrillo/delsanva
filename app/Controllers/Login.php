<?php

namespace App\Controllers;

class Login extends BaseController
{
    private $CI;
    protected $session;

    function __construct(){
        $this->session = \Config\Services::session();
        $this->session->start();
    }

    public function index()
    {   
        $data['title'] = 'Login';

        echo view('layout/login_header');
        echo view('pages/login/index');
        echo view('layout/login_footer');
    }

    public function login(){
        $loginModel = new \App\Models\LoginModel;
        $encrypter = \Config\Services::encrypter();
        
        $cipher = "AES-256-CBC";
        $secret = "DelsanVA";
        $option = 0;

        $iv = str_repeat("0", openssl_cipher_iv_length($cipher));
        
        if($this->request->getMethod() == 'get'){     
            $password = $this->request->getVar('password');       
            $data = array(
                'email' => $this->request->getVar('email'),
                'password' => openssl_encrypt($password, $cipher, $secret, $option, $iv),
                'logged_in'=> true
            );
        }

        $validate = $loginModel->check_user($data);

        if($validate){
            $data['user_id'] = $validate['id'];
            $data['name'] = $validate['name'];
            $data['email'] = $validate['email'];

            $this->session->set($data);

            // $check = $loginModel->user_daily_log($data['user_id']);
            
            echo "true";
        }else{
            echo "false";
        }
        
    }
}
