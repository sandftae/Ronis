<?php
/**
 * Created by PhpStorm.
 * User: 111
 * Date: 12.02.2018
 * Time: 0:00
 */

/*
 * Этот клас отвечает за обработку и отображение формы обратной связи
 */
class ContactsController extends Controller{

    public function __construct($data = array()){
        parent::__construct($data);
        $this -> model = new Message();
    }



    public function index(){
        if(!empty($_POST)) {
            if ($this->model->validMessage($_POST)) {
                if ($this->model->save($_POST)) {
                    Session::setFlash('Thank You! Message was sent successful');
                }
            } else {
                $this->data = $this->model->getMessageErrors();
            }
        }
    }



    public function admin_index(){
        $this -> data = $this -> model -> getList();
    }
}