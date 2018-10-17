<?php


/*
 * Данный класс отвечает за обработку и вызов контроллеров
 */

class App extends Database
{

    protected static $router;
    protected static $error;

    public static function getRouter(){
        return self::$router;
    }

    public static function getError(){
        return self::$error;
    }


    /*
     * Метод отвечает за обработку запросов к приложению. В качетве
     * параметорв он получеате uri. этот параметр будет использваться
     * для создания обьекта роутера.
     */
    public static function run($uri){
        self::$router = new Router($uri);
        self::$error = new Error();


        $controller_class = ucfirst(self::$router -> getController()). 'Controller';
        $controller_method = strtolower(self::$router -> getMethodPrefix().self::$router -> getAction());

        $layout = self::$router -> getRoute();
        if($layout == 'admin' && Session::get('role') != 'admin'){
            if($controller_method != 'admin_login'){
                Router::redirect('/default');
            }
        }


        /*
         * ЗДесь вызываю методы контроллеров. Здесь и происходит
         * переброс на все шаблоны/обработчики. Такой вариант я видел в Codeigniter,
         * т.к. с ним работаю. Однаго здесь его сделал проще:
         * 1) создаю обьект
         * 2) проверяю внем наличие метода, по которому обратился пользватель
         *          - метод есть?
         *              -генерирую путь, для вывода
         *              -после передаю путь и и дата для вывода
         */

        $controller_object = new $controller_class();
        if(method_exists($controller_object , $controller_method)){
            $view_path = $controller_object -> $controller_method();
            $view_object = new View($controller_object -> getData() , $view_path);
            $content = $view_object -> render();

        }else{
            Error::view_error();
        }


        if($layout == 'admin' && Session::get('role') != 'admin'){
            if($controller_method != 'admin_login'){
                Router::redirect('/admin/users/login');
            }
        }
        $layout_path = VIEWS_PATH.DS.$layout.'.html';
        $layout_view_object = new View(compact('content') , $layout_path);
        echo $layout_view_object -> render();
    }
}
