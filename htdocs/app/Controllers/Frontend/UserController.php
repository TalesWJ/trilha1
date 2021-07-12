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

    /**
     * Renders home page depending on parameters
     *
     * @param array|null $params
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function renderHome(?array $params = null)
    {
        if(!empty($params)) {
            echo $this->view->render('pages/login', $params);
        } elseif (Helper::userAuthenticated()) {
            echo $this->view->render('pages/dashboard', ['balance' => Helper::userBalance()]);
        } else {
            echo $this->view->render('pages/login');
        }
    }

    /**
     * User login authentication
     *
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
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
            Helper::response()->redirect('/userAuthenticated');
        }
        $this->renderHome([
            'message' => $auth->message
        ]);
    }

    /**
     * Register user on frontend application.
     */
    public function register()
    {
        $user = [
            'name' => filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING),
            'cpf' => filter_input(INPUT_POST, 'cpf', FILTER_SANITIZE_STRING),
            'rg' => filter_input(INPUT_POST, 'rg', FILTER_SANITIZE_STRING),
            'dob' => filter_input(INPUT_POST, 'dob', FILTER_SANITIZE_STRING),
            'phone' => filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING),
            'password' => filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING)
        ];
        $address = [
            'zipcode' => filter_input(INPUT_POST, 'zipcode', FILTER_SANITIZE_STRING),
            'country' => filter_input(INPUT_POST, 'country', FILTER_SANITIZE_STRING),
            'state' => filter_input(INPUT_POST, 'state', FILTER_SANITIZE_STRING),
            'city' => filter_input(INPUT_POST, 'city', FILTER_SANITIZE_STRING),
            'street' => filter_input(INPUT_POST, 'street', FILTER_SANITIZE_STRING),
            'number' => filter_input(INPUT_POST, 'number', FILTER_SANITIZE_STRING),
            'complement' => filter_input(INPUT_POST, 'complement', FILTER_SANITIZE_STRING)
        ];

        $registrationData = [
            'user' => $user,
            'address' => $address
        ];

        $auth = Helper::apiRequest('/users/create', $registrationData);
        if ($auth->message === $this->user::USER_CREATED) {
            $this->renderHome([
                'acc_number' => $auth->acc_number,
                'message' => $auth->message
            ]);
        } else {
            $this->renderHome([
                'message' => $auth->message
            ]);
        }
    }

    /**
     * User logout
     */
    public function logout() {
        session_unset();
        session_destroy();
        Helper::response()->redirect('/');
    }
}