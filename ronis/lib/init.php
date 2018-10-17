<?php
/*
 * Это узловой файл для подключения вфайла config.php
 * ко всем другим файлам
 */
require_once ('..'.DS.'config'.DS.'config.php');

/*
 * Данная функция автоматически загружет тот класс/модель/сонтроллер,
 * который ей был передан. также проверяет наличие его
 */
function __autoload($class_name){
    $lib_path = ROOT.DS.'lib'.DS.strtolower($class_name).'.class.php';
    $controllers_path = ROOT.DS.'controllers'.DS.str_replace('controller' , '' , strtolower($class_name)).'.controller.php';
    $model_path = ROOT.DS.'models'.DS.strtolower($class_name).'.php';

    if(file_exists($lib_path)){
        require_once ($lib_path);
    }elseif(file_exists($controllers_path)){
        require_once ($controllers_path);
    }elseif(file_exists($model_path)){
        require_once ($model_path);
    }else{
        Error::view_error();
    }
}
