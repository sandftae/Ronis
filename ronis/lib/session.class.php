<?php

/**
 * Class Session
 */
class Session
{
    /**
     * @var $flash_session
     */
    protected static $flash_session;

    /**
     * @param $message
     */
    public static function setFlash($message)
    {
        self::$flash_session = $message;
    }

    /**
     * @return bool
     */
    public static function hasFlash()
    {
        return !is_null(self::$flash_session);
    }

    public static function flash()
    {
        echo self::$flash_session;
        self::$flash_session = null;
    }

    /**
     * @param $key
     * @param $value
     */
    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * @param $key
     * @return null
     */
    public static function get($key)
    {
        if (isset($_SESSION[$key])) {
            return $_SESSION[$key];
        }
        return null;
    }

    /**
     * @param $key
     */
    public static function delete($key)
    {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    /**
     * session destroy method
     */
    public static function destroy()
    {
        session_destroy();
    }
}
