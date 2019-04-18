<?php
namespace App\Core\Classes;

use App\Core\Auth;
use App\Core\Core;

class Controller
{
    protected $header;
    protected $footer;
    protected $title;
    protected $styles;
    protected $scripts;

    public function __construct()
    {
        $this->title = 'Competition';
        $this->header = 'header';
        $this->footer = 'footer';
        $this->styles = [];
        $this->scripts = [];
    }

    /**
     * @param string $str
     * @param array $data
     *
     * @return array
     */
    protected function load($str, array $data)
    {
        if(Auth::checkAuth())
        {
            $data['user'] = Auth::getCurrentUser();
        }
        $data['page'] = (int)($this->getParam('page') ?: 1);
        $data['limit'] = (int)($this->getParam('limit') ?: 10);
        $data['page'] = $data['page'] > 0 ? $data['page'] : 1;
        $data['limit'] = $data['limit'] > 0 ? $data['limit'] : 10;
        $data['title'] = $this->title;
        $data['styles']= $this->styles;
        $data['scripts']= $this->scripts;
        $data['base_url'] = $_SERVER['HTTP_HOST'];
        return [
            'header'   =>  __DIR__ . '/../../templates/' . $this->header . '.phtml',
            'data'     =>  $data,
            'footer'   =>  __DIR__ . '/../../templates/' . $this->footer . '.phtml',
            'template' =>  __DIR__ . '/../../templates/' . ($str ?: '404') . '.phtml',
        ];
    }

    /**
     * Переход на другой путь
     *
     * @param string $str
     */
    protected function redirect($str = '')
    {
        header('Location: /' . $str);
    }

    /**
     * Выставляет хедер темплейту
     *
     * @param string $str
     */
    protected function head($str)
    {
        $this->header = $str;
    }

    protected function setTitle($str)
    {
        $this->title = $str;
    }

    /**
     * Выставляет футер темплейту
     *
     * @param $str
     */
    protected function footer($str)
    {
        $this->footer = $str;
    }

    /**
     * @param string $param
     *
     * @return mixed
     */
    protected function getParam($param)
    {
        if(isset($_GET[$param]))
        {
            return $_GET[$param];
        }

        if(isset($_POST[$param]))
        {
            return $_POST[$param];
        }

        return '';
    }
}