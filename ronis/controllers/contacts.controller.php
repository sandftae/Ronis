<?php

/**
 * Class ContactsController
 */
class ContactsController extends Controller
{
    /**
     * ContactsController constructor.
     * @param array $data
     */
    public function __construct($data = array())
    {
        parent::__construct($data);
        $this->model = new Message();
    }

    /**
     * @return void
     */
    public function index()
    {
        if (!empty($_POST)) {
            if ($this->model->validMessage($_POST)) {
                if ($this->model->save($_POST)) {
                    Session::setFlash('Thank You! Message was sent successful');
                }
            } else {
                $this->data = $this->model->getMessageErrors();
            }
        }
    }

    /**
     * @return void
     */
    public function admin_index()
    {
        $this->data = $this->model->getList();
    }
}
