<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

class MyFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null) {
        $logged_in = session()->get('logged_in');
        $login_uri = explode('?', $_SERVER['REQUEST_URI']);
        
        if(!isset($logged_in)){
            if($login_uri[0] != '/login' && $login_uri[0] != '/login/forgot_password'){
                if($login_uri[0] != '/login/login' && $login_uri[0] != '/login/send_forgot_password'){
                    return redirect()->to(base_url('/login'));
                }
            }
        }else{
            if($login_uri[0] == '/login'){
                return redirect()->to(base_url());
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {
        // Do something here
    }
}
