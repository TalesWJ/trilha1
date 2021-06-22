<?php

require __DIR__ . '/vendor/autoload.php';
use \App\Database\Database;

WilliamCosta\DotEnv\Environment::load(__DIR__);
Database::config(getenv('DB_HOST'), getenv('DB_NAME'), getenv('DB_USER'), getenv('DB_PASS'));

$db = new Database('users');
$id = $db->update('id_users=1', array(
    'name' => 'Fulano de TAL',
    'cpf' => '129.8126-74',
    'rg' => '19.431',
    'dob' => '05/10/1998',
    'phone' => '+55329881321'
));

echo $id;


