<?php
require_once ('../lib/controller.class.php');
/*
 * Данный контроллер отвечает за работу со статьями и сообщениями
 */

class PagesController extends Controller{

/*
 *  Конструктор позволит получить доступ к обьекту модели при помощи
 *  атрибута model-контроллера.
 */
    public function __construct($data = array()){
        parent::__construct($data);
        /*
         * Инициализация самого класса Model
         */

        $this -> model = new Page();
    }


    public function index(){
        $this -> data['pages'] = $this -> model -> getList();
    }

    public function showpages(){

    }


    public function view(){
        $params = App::getRouter() -> getParams();

        if(isset($params[1])){
            $alias = strtolower($params[1]);
            $this -> data['page'] = $this -> model -> getByAlias($alias);
        }

    }

    public function admin_index(){
        $this -> data['pages'] = $this -> model -> getList();
    }

    public function admin_add(){
        if($_POST){
            $result = $this -> model -> save($_POST);
            if($result){
                Session::setFlash('Page was saved!');
            }else{
                Session::setFlash('Error!');
            }
            Router::redirect('/admin/pages/');
        }
    }

    public function admin_edit(){
        if($_POST){
            $id = isset($_POST['id']) ? $_POST['id'] : null;
            $result = $this -> model -> save($_POST , $id);
            if($result){
                Session::setFlash('Page was saved!');
            }else{
                Session::setFlash('Error!');
            }
            Router::redirect('/admin/pages/');
        }


        if(isset($this -> params[1])){
            $this -> data['page'] = $this -> model -> getById($this -> params[1]);
        }else{
            Session::setFlash('Wrong page id.');
            Router::redirect('/admin/pages/');
        }
    }


    public function admin_delete(){
        if(isset ($this -> params[1])){
           $result = $this -> model -> delete($this -> params[1]);
        }

        if($result){
            Session::setFlash('Page deleted!');
        }else{
            Session::setFlash('Error.');
        }

        Router::redirect('/admin/pages/');
    }

    public function admin_upload(){

    }
}