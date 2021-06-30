<?php

use Pecee\SimpleRouter\SimpleRouter as Router;
use App\Controllers\API\UserApiController;

Router::group(['namespace' => 'App\Controllers\API'], function() {
   Router::group(['prefix' => '/API'], function() {
       Router::group(['prefix' => '/users'], function() {
          Router::get('', [UserApiController::class, 'searchUsers']);
          Router::get('/search/{accNumber}', [UserApiController::class, 'searchUserByAccNumber']);
          Router::post('/create', [UserApiController::class, 'createUser']);
       });
   });
});