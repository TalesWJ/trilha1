<?php

use Pecee\SimpleRouter\SimpleRouter as Router;

Router::group(['namespace' => 'App\Controllers\API'], function() {
   Router::group(['prefix' => '/API'], function() {
       Router::group(['prefix' => '/users'], function() {
          // INSERIR ROTAS AQUI
       });
   });
});