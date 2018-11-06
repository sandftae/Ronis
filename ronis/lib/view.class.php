<?php

/**
 * Class View
 */
class View
{
    /**
     * @var array
     */
    protected $data;

    /**
     * @var bool|null|string
     */
    protected $path;

    /**
     * @return bool|string
     */
    protected static function getDefaultViewPath()
    {
        $router = App::getRouter();

        if (!$router) {
            return false;
        }

        $controller_dir = $router->getController();
        $template_name = $router->getMethodPrefix() . $router->getAction() . '.html';
        return VIEWS_PATH . DS . $controller_dir . DS . $template_name;
    }

    /**
     * View constructor.
     * @param array $data
     * @param null $path
     */
    public function __construct($data = [], $path = null)
    {
        if (!$path) {
            $path = self::getDefaultViewPath();
        }

        if (!file_exists($path)) {
            Error::view_error();
        }

        $this->path = $path;
        $this->data = $data;
    }


    /**
     * @return false|string
     */

    public function render()
    {
        $data = $this->data;

        ob_start();
        include_once($this->path);

        $content = ob_get_clean();

        return $content;
    }
}
