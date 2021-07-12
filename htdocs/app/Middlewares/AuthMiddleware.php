<?php

declare(strict_types=1);

namespace App\Middlewares;

use App\Models\UserModel;
use Pecee\Http\Middleware\IMiddleware;
use Helper;
use Pecee\Http\Request;

class AuthMiddleware implements IMiddleware
{
    private UserModel $user;

    public function __construct()
    {
        $this->user = Helper::getContainer('UserModel');
    }

    /**
     * Handles user authentication for Frontend Application
     *
     * @param Request $request
     */
    public function handle(Request $request): void
    {
        $token = $_SESSION['token'];
        $user = $this->user::selectDataByColumn('acc_number', $_SESSION['acc_number']);
        if (empty($token) || $token != $user[0]->token) {
            http_response_code(401);
            header('WWW-Authenticate: Bearer realm="Access Denied"');
            Helper::response()->redirect('/');
        }
        header('WWW-Authenticate: Bearer ' . $token);
        http_response_code(200);
    }
}
