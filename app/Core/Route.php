<?php
namespace App\Core;

class Route
{
    private $url;
    private $path_arr;

    /**
     * Route constructor.
     *
     * @param string $url
     */
    public function __construct($url)
    {
        $this->url = $url;
        $this->path_arr = explode('/', $this->url);
        if($this->path_arr[1] === '')
        {
            $this->path_arr[1] = 'main';
        }
    }

    /**
     * Проверка пути
     *
     * @return bool
     */
    public function checkControllers()
    {
        if(file_exists(__DIR__.'/../Controller/' . ucwords($this->path_arr[1]) . 'Controller.php'))
        {
            return method_exists('App\\Controller\\' . ucwords($this->path_arr[1]) . 'Controller', count($this->path_arr) > 2 ? $this->path_arr[2] : 'index');
        }

        return false;
    }

    /**
     * Исполнение скрипта контроллера
     *
     * return mixed
     */
    public function executeRoute()
    {
        $class = 'App\\Controller\\' . ucwords($this->path_arr[1]) . 'Controller';
        $method = count($this->path_arr) > 2 ? $this->path_arr[2] : 'index';
        $controller = new $class;
        return $controller->$method();
    }
}