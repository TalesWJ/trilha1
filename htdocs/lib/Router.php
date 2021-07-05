<?php

use Pecee\SimpleRouter\SimpleRouter as Router;
use App\Controllers\API\UserApiController;
use App\Controllers\API\UserTransactionApiController;

Router::group(['namespace' => 'App\Controllers\API'], function() {
   Router::group(['prefix' => '/API'], function() {
       Router::group(['prefix' => '/users'], function() {
          Router::get('', [UserApiController::class, 'searchUsers']);
          Router::get('/search/{accNumber}', [UserApiController::class, 'searchUserByAccNumber']);
          Router::post('/create', [UserApiController::class, 'createUser']);
          Router::post('/login', [UserApiController::class, 'login']);
          Router::post('/getBalance', [UserApiController::class, 'getBalance']);
          Router::post('/updateBalance', [UserApiController::class, 'updateBalance']);
          Router::group(['prefix' => '/transactions'], function() {
              Router::post('/withdraw', [UserTransactionApiController::class, 'withdraw']);
              Router::post('/deposit', [UserTransactionApiController::class, 'deposit']);
              Router::post('/transfer', [UserTransactionApiController::class, 'transfer']);
          });
       });
   });
});