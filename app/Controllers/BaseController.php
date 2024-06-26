<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

abstract class BaseController extends Controller
{
    protected $request;
    protected $helpers = [];
    protected $session;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);
        $this->session = \Config\Services::session();

        // Preload any models, libraries, etc, here.

        // E.g.: $this->session = \Config\Services::session();
    }

    public function load_view($data = array(), $script = array(), $paths = ''){
        $data['name'] = $this->session->get('name');
        $data['position_id'] = $this->session->get('position_id');

        echo view('layout/header', $script);
        echo view('layout/sidebar', $data);
        foreach ($paths as $path) {
            echo view($path);
        }
        echo view('layout/footer', $script);
    }
}
