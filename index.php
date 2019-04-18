<?php
namespace Course;

use App\Core\Core;

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$loader = require 'vendor/autoload.php';
$loader->add('App', __DIR__.'/app/');

if(!isset($_SERVER['REDIRECT_URL']))
{
    $_SERVER['REDIRECT_URL'] = '/';
}

$request = $_SERVER['REDIRECT_URL'];
if($route_data = Core::getRoute($request))
{
    if(file_exists($route_data['template']))
    {
        Core::compileFile($route_data);
    }
    else
    {
        echo 'No template found!';
    }
}