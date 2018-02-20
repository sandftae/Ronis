<?php
/*
 *  Это основной конфигурационный файл прилождения. Он хранит
 *  различные данные, которые можно будет изменить и не повредить
 * при этьом исполнение самого приложения
 */

    Config::set('site_name' , 'Ronis');


/*
 * Устанавливая возможные заданные языки.
 * Это не обязательно в рамках ТЗ, однако во всех фреймах
 * привсутствует
 */

    Config::set('languages' , array('en' , 'ru'));


/*
 *  Здесь проводится установка роутов. В примере роутами будут
 * 'default' и 'admin'. Представление будет в ввиде ассоциативного массива,
 * где ключом есть назваание роута, а значением - префикс метода.
 * Класс router будет автоматимчески определят переданный роут и
 * использовать нужный ему префикс
 */

    Config::set('routes' , [
            'default' => '' ,
            'admin' => 'admin_'
    ]);



/*
 * Вношу дефолтное значение (значения по умолчанию) для default_routs
 */

    Config::set('default_route' , 'default');
    Config::set('default_language' , 'en');
    Config::set('default_controller' , 'pages');
    Config::set('default_action' , 'index');



/*
 * Отдельно установлены константы. Думаю, что нужно отделять конф. настройки
 * для работы с классами и обычные константы для формирования адресов, к примаеру.
 */

    define('DS' , DIRECTORY_SEPARATOR);
    define('ROOT' , '..');
    define('VIEWS_PATH' , ROOT.DS.'views');
    define('PATH_FOR_IMG' , '/web/images/');
    define('PATH_TO_IMG_FOR_SLIDER' , '/images/');

/*
 * Здесь вносятся настройки для подклбчения к БД и таблицам
 */
    define('HOST' , 'localhost');
    define('USER' , 'root');
    define('DB' , 'RONIS_DB');
    define('PASS' , '');
    define('TABLE_BANNERS' , 'banners');
    define('TABLE_POSITION' , 'position');
    define('TABLE_MESSAGES' , 'messages');
    define('TABLE_PAGES' , 'pages');
    define('TABLE_USERS' , 'users');
    define('TABLE_TASK' , 'task');

/*
 * Здесь задаются параметры для сжатия картинок при загрузке
 */
    define('MAX_WIDTH' , 400);
    define('MAX_HEIGHT' , 400);
    define('MAX_LOADING_FILE_SIZE' , 875000);






