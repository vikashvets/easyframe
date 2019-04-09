<?php
namespace App\Controller;

use App\Core\Classes\Controller;
use App\Core\Core;

class MainController extends Controller
{
    public function index()
    {
        $data = Core::getMysql()->getValues('SELECT * FROM test');
        return $this->load('main', $data);
    }

    public function load_users()
    {
        return $this->load('users', ['message' => 'Users Okay']);
    }
}