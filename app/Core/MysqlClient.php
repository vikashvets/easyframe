<?php
/**
 * Created by PhpStorm.
 * User: Nyashka
 * Date: 02.04.2019
 * Time: 22:30
 */

namespace App\Core;


class MysqlClient
{
    private $db;
    private $config;

    /**
     * MysqlClient constructor.
     *
     * Подгрузка конфигов базы
     */
    public function __construct()
    {
        $this->config = include(__DIR__ . '/../../config.php');
    }

    /**
     * Соединение с базой
     */
    private function createDBconnection()
    {
       try {
           $this->db = new \PDO(
               "mysql:host={$this->config['host']}:{$this->config['port']};dbname={$this->config['database']}",
               $this->config['login'],
               $this->config['password']
           );
       }
       catch (\PDOException $e)
       {
           echo "Connection failed: " . $e->getMessage();
       }
    }

    /**
     * Разрыв соединения
     */
    private function dropDBconnection()
    {
        $this->db = null;
    }

    /**
     * Достаем данные
     *
     * @param string $sql
     *
     * @return mixed
     */
    private function getQueryResult($sql)
    {
        $this->createDBconnection();
        $data = $this->db->query($sql);
        $this->dropDBconnection();
        return $data;
    }

    /**
     * Просто выполняем запрос
     *
     * @param string $sql
     *
     * @return mixed
     */
    public function query($sql)
    {
        $this->createDBconnection();
        $data = $this->db->exec($sql);
        $this->dropDBconnection();
        return $data;
    }

    /**
     * Достаем 1 переменную
     *
     * @param string $sql
     *
     * @return mixed
     */
    public function getValue($sql)
    {
        $query = $this->getQueryResult($sql);
        if($query)
        {
            return $query->fetch()[0];
        }
        return false;
    }

    /**
     * Достаем большое количество переменных
     *
     * @param string $sql
     * @param bool $oneRow
     *
     * @return array|bool
     */
    public function getValues($sql, $oneRow = false)
    {
        $query = $this->getQueryResult($sql);
        if($query)
        {
            if($oneRow)
            {
                return $query->fetch();
            }

            return $query->fetchAll();
        }

        return false;
    }
}