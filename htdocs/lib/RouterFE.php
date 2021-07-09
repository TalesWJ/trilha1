<?php

use Pecee\SimpleRouter\SimpleRouter as Router;
use App\Controllers\Frontend\UserController;

Router::group(['namespace' => 'App\Controllers\Frontend'], function() {
   Router::get('/', [UserController::class, 'renderHome']);
   Router::post('/loginPost', [UserController::class, 'login']);
});