<?php
namespace App\Controller;

use App\Core\Classes\Controller;
use App\Core\Auth;
use App\Core\Core;

class MainController extends Controller
{
    public function index()
    {
        return $this->load('main/index', []);
    }
}