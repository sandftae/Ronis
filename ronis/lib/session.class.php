<?php

class Session{

    protected static $flash_session;

    /*
     * Mетод вызывается в контроллерах.
     */
    public static function setFlash($message){
        self::$flash_session = $message;
    }

    public static function hasFlash(){
        return !is_null(self::$flash_session);
    }

    public static function flash(){
        echo self::$flash_session;
        self::$flash_session = null;
    }

    /*
     * Метод используется для внесения данных по пользователю
     */
    public static function set($key , $value){
        $_SESSION[$key] = $value;
    }

    /*
     * Мнтод получает значения из сессии
     */
    public static function get($key){
        if(isset($_SESSION[$key])){
            return $_SESSION[$key];
        }
        return null;
    }

    /*
     * Метод позволяет удалить запись в сессии
     */
    public static function delete($key){
        if(isset($_SESSION[$key])){
            unset($_SESSION[$key]);
        }
    }

    /*
     * Метод "уничтожает" запись. Вызывается при выходе пользвателя
     * с админской части
     */
    public static function destroy(){
        session_destroy();
    }

}
