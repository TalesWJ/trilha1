<?php

declare(strict_types=1);

namespace App\Controllers\Frontend;

use App\Models\UserModel;
use Jenssegers\Blade\Blade;
use Helper;

class UserController
{
    private Blade $blade;
    private UserModel $user;

    /**
     * UserController constructor.
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function __construct()
    {
        $this->blade = Helper::getContainer('ViewManager')->getViewObject();
        $this->user = Helper::getContainer('UserModel');
    }
}