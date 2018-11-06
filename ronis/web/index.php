<?php
session_start();
require_once('../lib/db.class.php');
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', '..');
define('VIEWS_PATH', ROOT . DS . 'views');

require_once('..' . DS . 'lib' . DS . 'init.php');

App::run($_SERVER['REQUEST_URI']);
