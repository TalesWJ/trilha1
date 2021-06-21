<?php

require __DIR__ . '/vendor/autoload.php';
WilliamCosta\DotEnv\Environment::load(__DIR__);


$db = new PDO('mysql:host='. getenv('DB_HOST') .';dbname='. getenv('DB_NAME'), getenv('DB_USER'), getenv('DB_PASS'));
$stmt = $db->prepare('INSERT INTO users 
                    (name, cpf, rg, dob, phone, balance, token, acc_number, acc_pw) 
                     VALUES (:name, :cpf, :rg, :dob, :phone, :balance, :token, :acc_number, :acc_pw);');

$stmt->execute(array(
    ':name' => 'Tales Duque',
    ':cpf' => '129.629.816-74',
    ':rg' => '19.226.431',
    ':dob' => '22/11/1997',
    ':phone' => '+5532988117083',
    ':balance' => 500,
    ':token' => '123abc5',
    ':acc_number' => '123456',
    ':acc_pw' => 'senha'

));

echo $stmt->rowCount();

