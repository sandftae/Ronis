<?php

/*
 * Этот контроллер отвечаает за работу с графическими фалами
 * и выводоа результата валидации
 */
Class UpdatesController extends Controller{

    public function __construct($data = array()){
        parent::__construct($data);
        /*
         * Инициализация самого класса Model
         */

        $this -> model = new Update();
    }


    public function admin_index(){
        $this -> data['pages'] = $this -> model -> getAllSrc();
    }

    public function admin_add(){
        // Здесь реализуется добавление записей

        if(isset($_POST)){
            if(!$this -> model -> validField($_POST)){
                $this -> data['error'] = $this -> model -> getErrors();
            }
        }
        if(isset($_FILES)){
            if(!$this -> model -> validFiles($_FILES)){
                $this -> data['error'] = $this -> model -> getErrors();
              }
          }

        if($this -> model -> validFiles($_FILES) && $this -> model -> validField($_POST)){
            $this -> model -> insertInDir($_FILES , $_POST);
            $this -> model -> setNewSizeImg($_FILES);
            $this -> model -> insertPosition($_POST['position']);
            //Если все ок, то редирект на страницу с обновлениями
            Router::redirect('/admin/updates/');
        }
        else{
            $this -> data['error'] = $this -> model -> getErrors();
        }

    }

    public function admin_update(){
        //Здесь будет происходить обновление картинок





        if (isset($this->params[1])) {
                $res = [];
                $res[] = array_shift($this -> model -> getImgById($this -> params[1]));
                $res[] = array_shift($this -> model -> getPositionById($this -> params[1]));
                $this -> data['banner_info'] = $res;
            }


            if(!$this -> model -> validField($_POST)){
                $this -> data['error'] = $this -> model -> getErrors();

        }

            if(!$this -> model -> validFiles($_FILES)){
                $this -> data['error'] = $this -> model -> getErrors();

        }
        if($this -> model -> validFiles($_FILES) && $this -> model -> validField($_POST)){
            $this -> model -> insertInDir($_FILES , $_POST);
            $this -> model -> setNewSizeImg($_FILES);
            $this -> model -> updateAllPosition(
                $this -> model -> setNewNumber($this -> params[1] , $_POST['position'])
            );
            //Если все ок, то редирект на страницу с обновлениями
            Router::redirect('/admin/updates/');
        }else{
            $this -> data['error'] = $this -> model -> getErrors();
        }
    }

    public function admin_delete(){
        //Здесь осуществляется удаление изображения
        if(isset ($this -> params[1])){
            $result = $this -> model -> deleteInBanners($this -> params[1]);
            $this -> model -> deleteInPosition($this -> params[1]);
            Router::redirect('/admin/updates/');
        }

        if($result){
            Session::setFlash('Page deleted!');
        }else{
            Session::setFlash('Error.');
        }

//        Router::redirect('/admin/updates/');


    }

    public function admin_up(){
        // Здесь будет выполняться логика для поднятия
        // приоритета (position)
        if(isset($this -> params[1])){
            $arr_id = array_shift($this -> model -> getPositionById($this -> params[1]));
            $new_position_number = $arr_id['number_position'] + 1;
            $this -> model -> setNewNumber($this -> params[1] , $new_position_number);
            $this -> model -> updateAllPosition($this -> model -> setNewNumber($this -> params[1] , $new_position_number));
            Router::redirect('/admin/updates/');
        }



    }

    public function admin_down()
    {
        // Здесь будет выполняться логика для понижения
        // приоритета (position)

        if (isset($this->params[1])) {
            $arr_id = array_shift($this->model->getPositionById($this->params[1]));
            $new_position_number = $arr_id['number_position'] - 1;
            $this->model->setNewNumber($this->params[1], $new_position_number);
            $this->model->updateAllPosition($this->model->setNewNumber($this->params[1], $new_position_number));
            Router::redirect('/admin/updates/');
        }
    }


    public function showImage(){
        //Здесь будет отображаться картинка.
        // лучше реализовать на JS
    }



}