<?php

namespace App\DependencyInjection;

use App\Controllers\API\UserApiController;
use App\Models\UserAddressModel;
use App\Models\UserModel;
use App\Models\UserTransactionsModel;
use App\Models\ModelManager;
use App\Database\Database;
use DI\Container;
use DI\ContainerBuilder;
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
            })
        ]);

        return self::$builder->build();
    }
}