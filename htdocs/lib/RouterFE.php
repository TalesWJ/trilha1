<?php

use Pecee\SimpleRouter\SimpleRouter as Router;
use App\Controllers\Frontend\UserController;

Router::group(['namespace' => 'App\Controllers\Frontend'], function() {
   Router::get('/', [UserController::class, 'renderHome']);
   Router::get('/logout', [UserController::class, 'logout']);
   Router::post('/loginPost', [UserController::class, 'login']);
   Router::post('/registerPost', [UserController::class, 'register']);
   //Auth needed
   Router::group(['middleware' => App\Middlewares\AuthMiddleware::class], function() {

   });
});