<?php

require __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/lib/RouterAPI.php';
require_once __DIR__ . '/lib/RouterFE.php';
require_once __DIR__ . '/lib/Helper.php';
use App\Database\Database;
use Pecee\SimpleRouter\SimpleRouter;

WilliamCosta\DotEnv\Environment::load(__DIR__);
Database::config(getenv('DB_HOST'), getenv('DB_NAME'), getenv('DB_USER'), getenv('DB_PASS'));

session_set_cookie_params(0);
session_start();
SimpleRouter::start();


