<?php

namespace App\DependencyInjection;

use App\Models\UserAddressModel;
use App\Models\UserModel;
use App\Models\UserTransactionsModel;
use DI\Container;
use DI\ContainerBuilder;
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
            })
        ]);

        return self::$builder->build();
    }
}