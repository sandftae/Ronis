<?php

/**
 * Class ImagesController
 */
class ImagesController extends Controller
{
    /**
     * ImagesController constructor.
     * @param array $data
     */
    public function __construct($data = array())
    {
        parent::__construct($data);
        $this->model = new Image();
    }

    /**
     * @return void
     */
    public function show()
    {
        $this->data = $this->model->endMassForRecord;
    }
}
