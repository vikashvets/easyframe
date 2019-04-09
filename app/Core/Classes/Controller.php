<?php
namespace App\Core\Classes;

class Controller
{
    protected $header;
    protected $footer;

    public function __construct()
    {
        $this->header = 'header';
        $this->footer = 'footer';
    }

    /**
     * @param string $str
     * @param array $data
     *
     * @return array
     */
    protected function load($str, array $data)
    {
        return [
            'header'   =>  __DIR__ . '/../../templates/' .$this->header . '.phtml',
            'data'     =>  $data,
            'footer'   =>  __DIR__ . '/../../templates/' . $this->footer . '.phtml',
            'template' =>  __DIR__ . '/../../templates/' . ($str ?: '404') . '.phtml',
        ];
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
     * @return string
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