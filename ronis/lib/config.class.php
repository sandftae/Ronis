<?php

/*
 * Данный класс отвечает за параметры настроек прилождения
 */


class Config
{
    protected static $settings = [];

    /*
     * Метод возвращает значение из массива сеттинг
     */
    public static function get($key){
        return isset(self::$settings[$key]) ? self::$settings[$key] : null;
    }


    /*
     * В методе выполняется присваивания значений элеметнам сеттинг
     * с указаным ключем
     */
    public static function set($key , $value){
        self::$settings[$key] = $value;
    }

}