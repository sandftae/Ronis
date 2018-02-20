<?php
/**
 * Created by PhpStorm.
 * User: 111
 * Date: 11.02.2018
 * Time: 22:13
 */

/*
 * Обьекты данного класса создаются только в тех сслучаях, когда
 * необходимо передать определенное представление и передвать в него же
 * данный, для дальнейшего отображения в html коде
 */

class View{

    protected $data;
    protected $path;


    /*
     * Определяется путь к шаблону в этом методе
     */
    protected static function getDefaultViewPath(){
        $router = App::getRouter();

        if(!$router){
            return false;
        }

        $controller_dir = $router -> getController();
        $template_name =  $router -> getMethodPrefix().$router -> getAction().'.html';
        return VIEWS_PATH.DS.$controller_dir.DS.$template_name;
    }


    public function __construct($data = [] , $path = null){
        if(!$path){
        /*
         * Если переменная $path не задана или пустая, то ее опрделение
         * происходит самостоятельно (в ручном режиме, иначе говоря)
         */
            $path = self::getDefaultViewPath();

        }


        /*
         * Если файла по указанному пути не существует, то "отлавливаю" ошибку
         * и сообщаю об этом
         */
        if(!file_exists($path)){
            Error::view_error();
//            throw new Exception('This file is not found in path. You have  error in: ' . $path);
        }

        /*
         * Если все ок, то инициализированные свойства записываются с
         * пришедшими значениями
         */
        $this -> path = $path;
        $this -> data = $data;

    }


    /*
     * Данный метод отвечает за рендеринг шаблона. Он возвращает готовый
     * html код. Этот метод использует атрибуты обьекта
     */

    public function render(){
        $data = $this -> data;

        /*
         * Провожу буферизацию. Конкретно эту часть кода я писал не самп, т.е. не я придумал.
         * Здесь потратил очень много времени, что-бы разобраться, посвему код "ворованный" из сети,
         * т.е. сам метод render и его логика была взята из вне. Все методв класса и некоторые дркгие
         * пришлось адаптировать именно по этот метод этого класса.
         */

        ob_start();
        include_once ($this -> path);
        $content = ob_get_clean();
        return $content;
    }
}