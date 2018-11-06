<?php

class UsersController extends Controller
{
    public function __construct($data = [])
    {
        parent::__construct($data);
        $this->model = new User();
    }

    public function about()
    {
        $this->data['about'] = $this->model->getTask();
    }

    public function enter()
    {
        Router::redirect('/admin');
    }

    /*
     * Метод будет выводить форму логина и обрабатывать его. Здесь же проверяю результаты валидации,
     * из модели: корректность внесенных данных и существования такого пользователя
     */
    public function admin_login()
    {
        if (isset($_POST) && isset($_POST['login']) && isset($_POST['password'])) {
            $user = $this->model->getByLogin($_POST['login']);
//            $hash = md5(Config::get('ARTEM_salt').$_POST['password']);
            if ($user['is_active'] && $_POST['password'] == $user['password']) {
                Session::set('login', $user['login']);
                Session::set('role', $user['role']);
            }
            Router::redirect('/admin/');
        }
        if (isset($_POST['Go']) && !isset($_POST['login']) || !isset($_POST['password'])) {
            App::getError()->setErrors('error', 'Enter the data / wrong login or password');
            $this->data['error'] = App::getError()->getErrorsAll();
        }
    }

    public function admin_logout()
    {
        Session::destroy();
        Router::redirect('/default');
    }
}

