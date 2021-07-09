<?php

declare(strict_types=1);

namespace App\Controllers\Frontend;

use App\Models\UserModel;
use Jenssegers\Blade\Blade;
use Helper;

class UserController
{
    private Blade $view;
    private UserModel $user;

    /**
     * UserController constructor.
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function __construct()
    {
        $this->view = Helper::getContainer('ViewManager')->getViewObject();
        $this->user = Helper::getContainer('UserModel');
    }

    public function renderHome(?array $params = null)
    {
        if(!empty($params)) {
            echo $this->view->render('pages/login', $params);
        } else {
            echo $this->view->render('pages/login');
        }
    }

    public function login()
    {
        $acc_number = filter_input(INPUT_POST, 'acc_number', FILTER_SANITIZE_STRING);
        $password =  filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
        $data = [
            'acc_number' => $acc_number,
            'password' => $password
        ];

        $auth = Helper::apiRequest('/login', $data);
        if ($auth->message === $this->user::USER_PW_AUTH_OK) {
            $_SESSION['acc_number'] = $data['acc_number'];
            $_SESSION['token'] = $auth->token;
            Helper::response()->redirect('/');
        }
        $this->renderHome([
            'message' => $auth->message
        ]);
    }
}