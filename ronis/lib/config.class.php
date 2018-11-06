<?php

/*
 * Класс отвечает за параметры настроек прилождения
 */

class Config
{
    protected static $settings = [];

    /**
     * @param $key
     * @return mixed|null
     */
    public static function get($key)
    {
        return isset(self::$settings[$key]) ? self::$settings[$key] : null;
    }


    /**
     * В методе выполняется присваивания значений элеметнам сеттинг
     * с указаным ключем
     *
     * @param $key
     * @param $value
     */
    public static function set($key, $value)
    {
        self::$settings[$key] = $value;
    }
}
