<?php

require __DIR__ . '/vendor/autoload.php';
use App\Database\Database;
use App\Models\CoreModel;

WilliamCosta\DotEnv\Environment::load(__DIR__);
Database::config(getenv('DB_HOST'), getenv('DB_NAME'), getenv('DB_USER'), getenv('DB_PASS'));

$coreModel = new CoreModel('users', [
    'id_users',
    'name',
    'cpf',
    'rg',
    'dob',
    'phone',
    'balance',
    'token',
    'acc_number',
    'acc_pw'
]);

$data = $coreModel->deleteData('id_users', '13');



