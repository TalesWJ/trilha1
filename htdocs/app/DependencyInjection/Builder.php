<?php

namespace App\DependencyInjection;

use App\Controllers\API\UserApiController;
use App\Models\UserAddressModel;
use App\Models\UserModel;
use App\Models\UserTransactionsModel;
use App\Models\ModelManager;
use App\Database\Database;
use App\Views\ViewManager;
use DI\Container;
use DI\ContainerBuilder;
use Jenssegers\Blade\Blade;
use Psr\Container\ContainerInterface;
use function DI\factory;

class Builder
{
    private static ContainerBuilder $builder;

    public static function buildContainer() : Container
    {
        self::$builder = new ContainerBuilder();
        self::$builder->addDefinitions([
            'UserModel' => factory(function () {
                return new UserModel();
            }),
            'UserAddressModel' => factory(function () {
                return new UserAddressModel();
            }),
            'UserTransactionsModel' => factory(function () {
                return new UserTransactionsModel();
            }),
            'ModelManager' => factory(function (ContainerInterface $c) {
                return new ModelManager($c->get('Database'));
            }),
            'Database' => factory(function () {
                return new Database();
            }),
            'UserApiController' => factory(function () {
                return new UserApiController();
            }),
            'Blade' => factory(function () {
                return new Blade(__DIR__ . '/../Views', __DIR__ . '/../Views/cache');
            }),
            'ViewManager' => factory(function (ContainerInterface $c) {
                return new ViewManager($c->get('Blade'));
            })
        ]);
        return self::$builder->build();
    }
}