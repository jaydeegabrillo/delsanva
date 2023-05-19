<?php

namespace App\Controllers;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Login extends BaseController
{
    private $CI;
    protected $db;
    protected $session;

    function __construct(){
        $this->session = \Config\Services::session();
        $this->session->start();
        $this->db = \Config\Database::connect();
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
            $data['position_id'] = $validate['position_id'];

            $this->session->set($data);

            // $check = $loginModel->user_daily_log($data['user_id']);

            echo "true";
        }else{
            echo "false";
        }

    }

    public function send_forgot_password(){
        $email       = $this->request->getVar('email');
        $user_exists = $this->db->table('users')->where('email', $email)->get()->getResultArray();
        
        if($user_exists){
            $encrypter = \Config\Services::encrypter();

            $cipher = "AES-256-CBC";
            $secret = "DelsanVA";
            $option = 0;

            $iv = str_repeat("0", openssl_cipher_iv_length($cipher));
            $password = openssl_encrypt('delsanva'.date('c'), $cipher, $secret, $option, $iv);

            $subject        = 'Reset Password';
            $message        = 'Your new temporary password is '.$password.' do not share this information. Please update to a newer password after the next time you log in. Thank you!<br>Click <a href="' . base_url('/login') . '">here</a> to login.';

            $mail = new PHPMailer(true);

            $mail->isSMTP();
            $mail->Host         = 'cloud.delsanva.com';
            $mail->SMTPAuth     = true;
            $mail->Username     = 'support@delsanva.com';
            $mail->Password     = 'I8bAWRsPBc8Q';
            $mail->SMTPSecure   = 'tls';
            $mail->Port         = 587;
            $mail->From         = 'noreply@delsanva.com';
            $mail->FromName     = 'Delsan VA';
            $mail->Subject      = $subject;
            $mail->Body         = $message;

            $mail->addAddress($email);
            $mail->isHTML(true);

            $data = array(
                'password' => openssl_encrypt($password, $cipher, $secret, $option, $iv)
            );

            $result = $this->db->table('users')->where('email', $email)->update($data);

            if($result){
                $mailed = $mail->send();

                if(!$mailed) {
                    echo 'false';
                } else {
                    echo 'true';
                }
            } else {
                echo "false";
            }
        } else {
            echo 'not_exist';
        }
    }

    public function forgot_password(){
        $data['title'] = 'Forgot Password';

        echo view('layout/login_header');
        echo view('pages/login/forgot_password');
        echo view('layout/login_footer');
    }

    public function logout(){
        $this->session->destroy();
        return redirect()->to(base_url());
    }
}
