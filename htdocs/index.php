<?php

require __DIR__ . '/vendor/autoload.php';
use \App\Database;

WilliamCosta\DotEnv\Environment::load(__DIR__);
Database::config(getenv('DB_HOST'), getenv('DB_NAME'), getenv('DB_USER'), getenv('DB_PASS'));

$db = new Database('users');
$id = $db->delete('id_users=14');

echo $id;


