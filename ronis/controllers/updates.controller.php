<?php

Class UpdatesController extends Controller
{

    public function __construct($data = array())
    {
        parent::__construct($data);

        $this->model = new Update();
    }

    public function admin_index()
    {
        $this->data['pages'] = $this->model->getAllSrc();
    }

    public function admin_add()
    {
        if (isset($_POST)) {
            if (!$this->model->validField($_POST)) {
                $this->data['error'] = $this->model->getErrors();
            }
        }

        if (isset($_FILES)) {
            if (!$this->model->validFiles($_FILES)) {
                $this->data['error'] = $this->model->getErrors();
            }
        }

        if ($this->model->validFiles($_FILES) && $this->model->validField($_POST)) {
            $this->model->insertInDir($_FILES, $_POST);
            $this->model->setNewSizeImg($_FILES);
            $this->model->insertPosition($_POST['position']);
            Router::redirect('/admin/updates/');
        } else {
            $this->data['error'] = $this->model->getErrors();
        }
    }

    public function admin_update()
    {
        if (isset($this->params[1])) {
            $res = [];
            $res[] = array_shift($this->model->getImgById($this->params[1]));
            $res[] = array_shift($this->model->getPositionById($this->params[1]));
            $this->data['banner_info'] = $res;
        }

        if (!$this->model->validField($_POST)) {
            $this->data['error'] = $this->model->getErrors();

        }

        if (!$this->model->validFiles($_FILES)) {
            $this->data['error'] = $this->model->getErrors();

        }

        if ($this->model->validFiles($_FILES) && $this->model->validField($_POST)) {
            $this->model->insertInDir($_FILES, $_POST);
            $this->model->setNewSizeImg($_FILES);
            $this->model->updateAllPosition(
                $this->model->setNewNumber($this->params[1], $_POST['position'])
            );
            Router::redirect('/admin/updates/');
        } else {
            $this->data['error'] = $this->model->getErrors();
        }
    }

    public function admin_delete()
    {
        if (isset ($this->params[1])) {
            $result = $this->model->deleteInBanners($this->params[1]);
            $this->model->deleteInPosition($this->params[1]);
            Router::redirect('/admin/updates/');
        }

        if ($result) {
            Session::setFlash('Page deleted!');
        } else {
            Session::setFlash('Error.');
        }
    }

    public function admin_up()
    {
        if (isset($this->params[1])) {
            $arr_id = array_shift($this->model->getPositionById($this->params[1]));
            $new_position_number = $arr_id['number_position'] + 1;
            $this->model->setNewNumber($this->params[1], $new_position_number);
            $this->model->updateAllPosition($this->model->setNewNumber($this->params[1], $new_position_number));
            Router::redirect('/admin/updates/');
        }
    }


    public function admin_down()
    {
        if (isset($this->params[1])) {
            $arr_id = array_shift($this->model->getPositionById($this->params[1]));
            $new_position_number = $arr_id['number_position'] - 1;
            $this->model->setNewNumber($this->params[1], $new_position_number);
            $this->model->updateAllPosition($this->model->setNewNumber($this->params[1], $new_position_number));
            Router::redirect('/admin/updates/');
        }
    }
}

