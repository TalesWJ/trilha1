<?php

namespace App\Middlewares;

use Pecee\Http\Middleware\IMiddleware;
use Pecee\Http\Request;
use App\Models\UserModel;
use Helper;
use Exception;

class AuthMiddlewareAPI implements IMiddleware
{
    public function __construct()
    {
        
    }

    public function handle(Request $request): void
    {

    }
}
