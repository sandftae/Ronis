<?php
/**
 * Created by PhpStorm.
 * User: 111
 * Date: 18.02.2018
 * Time: 23:48
 */


/*
 * Данный контроллер отвечает за передачу во вьюшку
 * инфо по массиву, в котором определен порядок отображения
 * слайдов.
 */
class ImagesController extends Controller{


    public function __construct($data = array()){
        parent::__construct($data);
        $this -> model = new Image();
    }
    public function show(){
        $this -> data = $this -> model -> endMassForRecord;
    }



}