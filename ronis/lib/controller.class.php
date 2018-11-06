<?php

/**
 * Class Controller
 */
class Controller
{
    /**
     * @var array
     */
    protected $data;

    /**
     * @var $model
     */
    protected $model;

    /**
     * @var $params
     */
    public $params;

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return mixed
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @return mixed
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Controller constructor.
     * @param array $data
     */
    public function __construct($data = array())
    {
        $this->data = $data;
        $this->params = App::getRouter()->getParams();
    }
}
