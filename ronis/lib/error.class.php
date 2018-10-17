<?php


/*
 * Класс для работы с ошибками, их сбром и редиректом на другую
 * страницу, если пользватель в адресной строке ввел не существующий
 * контроллер или его метод
 */
Class Error{

    protected static $errors;

    public static  function setErrors($key , $value){
        self::$errors[$key] = $value;
    }

    public static function getErrors($key){
        return self::$errors[$key];
    }
    public static function getErrorsAll(){
        return self::$errors;
    }

    public static function view_error(){
        Router::redirect('/admin');
    }


}
