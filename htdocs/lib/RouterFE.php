<?php

use Pecee\SimpleRouter\SimpleRouter as Router;
use App\Controllers\Frontend\UserController;
use App\Controllers\Frontend\UserTransactionController;

Router::group(['namespace' => 'App\Controllers\Frontend'], function() {
   Router::get('/', [UserController::class, 'renderHome']);
   Router::get('/logout', [UserController::class, 'logout']);
   Router::post('/loginPost', [UserController::class, 'login']);
   Router::post('/registerPost', [UserController::class, 'register']);
   Router::get('/userAuthenticated', [UserController::class, 'renderHome']);
   //Auth needed
   Router::group(['middleware' => App\Middlewares\AuthMiddleware::class], function() {
       Router::post('/transferPost', [UserTransactionController::class, 'transfer']);
       Router::post('/depositPost', [UserTransactionController::class, 'deposit']);
       Router::post('/withdrawPost', [UserTransactionController::class, 'withdraw']);
       Router::get('/extractGet', [UserTransactionController::class, 'extract']);
   });
});