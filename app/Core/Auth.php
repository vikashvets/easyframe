<?php

namespace App\Core;


class Auth
{
    /**
     * Проверка авторизации пользователя
     * @param int|bool $user_id
     * @return bool
     */
    static public function checkAuth($user_id = false)
    {
        $hash = '';
        if(isset($_SESSION['user_hash']))
        {
            $hash = $_SESSION['user_hash'];
        } elseif (isset($_COOKIE['user_hash']))
        {
            $hash = $_COOKIE['user_hash'];
        }

        return (bool)Core::getMysql()->getValue("SELECT id 
                                                      FROM user 
                                                      WHERE MD5(CONCAT(email, password)) = '{$hash}'
                                                      " . ($user_id ? ' AND id = ' . $user_id : ''));

    }

    static public function getCurrentUser()
    {
        $hash = '';
        if(isset($_SESSION['user_hash']))
        {
            $hash = $_SESSION['user_hash'];
        } elseif (isset($_COOKIE['user_hash']))
        {
            $hash = $_COOKIE['user_hash'];
        }

        return Core::getMysql()->getValues("SELECT *
                                                 FROM user 
                                                 WHERE MD5(CONCAT(email, password)) = '{$hash}'", true);
    }

    /**
     * Авторизация пользователя
     * @param string $login
     * @param string $password
     * @param bool $remember_me
     * @return bool
     */
    static public function userAuth($login, $password = '', $remember_me = false)
    {
        $user = Core::getMysql()->getValues("SELECT email, password
                                                  FROM user 
                                                  WHERE email = '{$login}' AND password = '{$password}'", true);
        $hash = md5($user['email'] . $user['password']);
        $_SESSION['user_hash'] = $hash;
        if($remember_me)
        {
            $_COOKIE['user_hash'] = $hash;
        }

        return true;
    }

    /**
     * Выход
     *
     * @return bool
     */
    static public function logout()
    {
        if(Auth::checkAuth())
        {
            $_SESSION['user_hash'] = '';
            $_COOKIE['user_hash'] = '';
            return true;
        }

        return false;
    }

    /**
     * Регистрация пользователя
     *
     * @param array $info
     * @return bool
     */
    static public function registration($info)
    {
        $str = '';
        if(!$info['email'] && !$info['password'] && !$info['name'])
        {
            return false;
        }

        if(Core::getMysql()->getValue("SELECT id FROM user WHERE email = '{$info['email']}'"))
        {
            return false;
        }

        $info['password'] = md5($info['password']);
        $array = Core::prepareArrayToInsert($info);
        $str .= 'INSERT INTO user (' . implode(', ', $array['column']) . ') VALUES (' . implode(', ', $array['value']). ')';
        if(Core::getMysql()->query($str))
        {
            return Auth::userAuth($info['email'], $info['password'], true);
        }

        return false;
    }
}