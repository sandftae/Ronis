<?php

/**
 * Класс для работы с ошибками, их сбром и редиректом на другую
 * страницу, если пользватель в адресной строке ввел не существующий
 * контроллер или его метод
 *
 * Class Error
 */
Class Error
{
    /**
     * @var $errors
     */
    protected static $errors;

    /**
     * @param $key
     * @param $value
     */
    public static function setErrors($key, $value)
    {
        self::$errors[$key] = $value;
    }

    /**
     * @param $key
     * @return mixed
     */
    public static function getErrors($key)
    {
        return self::$errors[$key];
    }

    /**
     * @return mixed
     */
    public static function getErrorsAll()
    {
        return self::$errors;
    }

    /**
     * redirected
     */
    public static function view_error()
    {
        Router::redirect('/admin');
    }
}
