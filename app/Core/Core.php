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

    /**
     * Вывод файла
     *
     * @param array $route_data
     */
    static public function compileFile($route_data)
    {
        echo '<html lang="ru-RU">';
        $Core = new Core();
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
}