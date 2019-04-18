<?php
namespace App\Core;

use App\Core\Classes\Model;

class Core
{
    /**
     * Подгрузка модели
     *
     * @param $name
     *
     * @return Model|bool
     */
    static public function getModel($name)
    {
        $model_file  = __DIR__ . '/../Model/' . ucfirst($name) . 'Model.php';
        $model_class = 'App\\Model\\' . ucfirst($name) .'Model';

        if(file_exists($model_file)) {
            return new $model_class;
        }

        return false;
    }

    /**
     * Переход по URL
     *
     * @param $request_url
     * @return array|bool
     */
    static public function getRoute($request_url)
    {
        $route = new Route($request_url);
        if($route->checkControllers())
        {
            return $route->executeRoute();
        }

        return false;
    }

    static public function insert($str, $vars)
    {
        $str = __DIR__ . '/../templates/' . $str . '.phtml';
        if(file_exists($str))
        {
            $core = new Core();
            $data = $vars;
            require $str;
        }
    }

    /**
     * Вывод файла
     *
     * @param array $route_data
     */
    static public function compileFile($route_data)
    {
        echo '<html lang="ru-RU">';
        $core = new Core();
        $data = $route_data['data'];
        require $route_data['header'];
        require $route_data['template'];
        require $route_data['footer'];
        echo '</html>';
    }

    /**
     * Запросы в базу
     *
     * @return MysqlClient|bool
     */
    static public function getMysql()
    {
        return new MysqlClient();
    }

    /**
     * @param $array
     * @return array
     */
    static function prepareArrayToInsert($array)
    {
        $result = [];
        foreach ($array as $key => $value)
        {
            if($value)
            {
                $result['column'][] = '`' . $key . '`';
                $result['value'][] = is_string($value) ? "'" . addslashes($value) . "'" : $value;
            }
            else
            {
                $result['column'][] = '`' . $key . '`';
                $result['value'][] = '0';
            }
        }

        return $result;
    }

    /**
     * @param string $date
     * @return false|string
     */
    static function convertDate($date)
    {
       return date('Y-m-d H:i', (is_string($date) ? strtotime($date) : $date));
    }


    static public function print_val($val)
    {
        echo '<pre>';
        print_r($val);
        echo '</pre>';
    }

    static public function getAsset($asset)
    {
        return ($_SERVER['SERVER_PROTOCOL'] === 'HTTP/1.1' ? 'http' : 'https') . '://' . $_SERVER['HTTP_HOST'] . '/app/assets/' . $asset;
    }
}