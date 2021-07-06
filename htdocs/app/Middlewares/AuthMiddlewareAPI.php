<?php

declare(strict_types=1);

namespace App\Middlewares;

use Pecee\Http\Middleware\IMiddleware;
use Pecee\Http\Request;
use App\Models\UserModel;
use Helper;
use Exception;
use function PHPUnit\Framework\isEmpty;

class AuthMiddlewareAPI implements IMiddleware
{
    private UserModel $user;

    public function __construct()
    {
        $this->user = Helper::getContainer('UserModel');
    }

    /**
     * Check if user is authenticated
     *
     * @param Request $request
     */
    public function handle(Request $request): void
    {
        $auth_header = $request->getHeader('authorization');
        $auth_token = substr($auth_header, 7);

        try {
            $user = $this->user::selectDataByColumn('token', $auth_token);
            if (!empty($user)) {
                header('WWW-Authenticate: Bearer ' . $auth_token);
                http_response_code(200);
                if (empty($auth_token) || $auth_token !== $user[0]->token) {
                    header('WWW-Authenticate: Bearer realm="Access Denied"');
                    http_response_code(401);
                    Helper::apiResponse(
                        'Acesso negado - Token incorreto'
                    );
                }
            } else {
                throw new Exception('Nenhum usuário encontrado com o token informado.');
            }
        } catch (Exception $e) {
            $message = $e->getMessage();
            header('WWW-Authenticate: Bearer realm="Access Denied"');
            http_response_code(401);
            Helper::apiResponse(
                'Acesso negado - ' . $message
            );
        }
    }
}
